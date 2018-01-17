<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * PosCategory for Point of Sale.
 */
class PosCategory extends Category
{

    /**
     * Cache total products.
     *
     * @var array
     */
    protected static $cache_total_products = array();

    /**
     *
     * @return array
     * @see getAllChildren()
     */
    public static function getTreeCategories()
    {
        $category = new self(PosCategory::getRootCategory()->id);
        return $category->getAllChildren();
    }

    /**
     * Return an array of all children of the current category
     *
     * @param int $id_lang
     * @return array
     * <pre>
     * array(
     *  int => array(
     *      id_category => int,
     *      name => string,
     *      description => string,
     *      id_parent => int
     *  ),
     *  ...
     * )
     */
    public function getAllChildren($id_lang = null)
    {
        if (is_null($id_lang)) {
            $id_lang = Context::getContext()->language->id;
        }
        $query = new DbQuery();
        $query->select('c.`id_category`');
        $query->select('cl.`name`');
        $query->select('cl.`description`');
        $query->select('c.`id_parent`');
        $query->from('category', 'c');
        $query->innerjoin('category_lang', 'cl', 'cl.`id_category` = c.`id_category` AND cl.`id_lang` = ' . (int) $id_lang);
        $query->innerjoin('category_shop', 'cs', 'cs.`id_category` = c.`id_category` AND cs.`id_shop` = ' . (int) Context::getContext()->shop->id);
        $query->where('c.`nleft` > ' . (int) $this->nleft);
        $query->where('c.`nright` < ' . (int) $this->nright);
        $query->groupBy('c.`id_category`');
        $query->orderBy('c.`position`');
        if (!Configuration::get('POS_FILTER_CATEGORY_HIDDEN')) {
            $query->where('c.`active` = 1');
        }
        return Db::getInstance()->executeS($query);
    }

    /**
     *
     * @param array $id_categories
     * @param type $page
     * @return array
     * <pre>
     *  array (
     *      products => array (
     *          int => array(
     *              id_product => int
     *              reference => string
     *              link_rewrite => string
     *              id_shop => int
     *              id_product_attribute => int
     *              has_combinations => boolean
     *              price => float
     *              image_url => string
     *              name => string
     *              short_name => string
     *          )
     *          ...
     *       )
     *      total_products => int
     *  )
     */
    public static function searchProductsById(array $id_categories = array(), $page = 1)
    {
        $offset = (int) ($page - 1) > 0 ? $page - 1 : 0;
        $context = Context::getcontext();
        $eligible_products = self::getIdProducts($id_categories);
        if (empty($eligible_products)) {
            return array(
                'total_products' => 0,
                'products' => array()
            );
        }
        $product_visibilities = PosProduct::getProductVisibilities();
        $product_query = new DbQuery();
        $product_query->select('p.`id_product`');
        $product_query->select('p.`reference`');
        $product_query->select('pl.`link_rewrite`');
        $product_query->select('product_shop.`id_shop`');
        $product_query->select('pl.`name` AS `name`');
        $product_query->select('IFNULL(i.`id_image`,0) AS `id_image`');
        $product_query->select('IFNULL(pas.`id_product_attribute`,0) AS `id_product_attribute`');
        $product_query->select('IF(pas.`id_product_attribute` > 0, 1, 0) AS `has_combinations`');
        $product_query->from('product', 'p');
        $product_query->leftJoin('product_attribute_shop', 'pas', 'p.`id_product` = pas.`id_product` AND pas.id_shop='.(int) $context->shop->id);
        $product_query->join(PosShop::addSqlAssociation('product', 'p'));
        $product_query->leftJoin('product_lang', 'pl', 'p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int) $context->language->id . ' AND pl.`id_shop` = ' . (int) $context->shop->id);
        $product_query->leftJoin('image', 'i', 'i.`id_product` = p.`id_product`AND i.`cover` = 1');
        $product_query->leftJoin('image_shop', 'image_shop', 'image_shop.`id_image` = i.`id_image` AND image_shop.id_shop=' . (int) $context->shop->id);
        $product_query->leftJoin('image_lang', 'il', 'image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $context->language->id);
        $product_query->where('p.`id_product` IN (' . implode(',', $eligible_products) . ')');
        $product_query->where(!empty($product_visibilities) ? 'p.`visibility` IN ("' . implode('","', $product_visibilities) . '")' : null);
        $product_query->groupBy('p.`id_product`');
        $product_query->limit((int) PosConstants::POS_FILTER_PRODUCT_LIMIT, (int) $offset * PosConstants::POS_FILTER_PRODUCT_LIMIT);
        $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($product_query);
        $use_tax = Configuration::get('PS_TAX') && !Product::getTaxCalculationMethod((int) $context->cookie->id_customer);
        foreach ($products as &$product) {
            $product['price'] = Product::getPriceStatic($product['id_product'], $use_tax, null, 2);
            $product['image_url'] = $context->link->getImageLink($product['link_rewrite'], $product['id_image'], PosImageType::getFormattedName('small'));
            unset($product['id_image']);
            if (Tools::strlen($product['name']) <= (int) Configuration::get('POS_PRODUCT_NAME_LENGTH') + 3) {
                $product['short_name'] = $product['name'];
            } else {
                $product['short_name'] = trim(Tools::substr($product['name'], 0, (int) Configuration::get('POS_PRODUCT_NAME_LENGTH'))) . '...';
            }
        }
        if (empty(self::$cache_total_products[implode('-', $id_categories)])) {
            self::$cache_total_products[implode('-', $id_categories)] = self::getNumberProductsByCategories($id_categories, $context->shop->id);
        }

        return array(
            'total_products' => self::$cache_total_products[implode('-', $id_categories)],
            'products' => $products
        );
    }
        
    /**
     * @param array $id_categories
     *
     * @return array
     * <pre>
     * array(
     *      int => int
     *      int => int
     *  ...
     *  )
     */
    public static function getIdProducts(array $id_categories = array())
    {
        $query = new DbQuery();
        $query->select('DISTINCT cp.`id_product`');
        $query->from('category_product', 'cp');
        $query->where(!empty($id_categories) ? 'cp.`id_category` IN (' . implode(',', $id_categories) . ')' : null);
        $products = Db::getInstance()->executeS($query);
        $id_products = array();
        if (!empty($products)) {
            foreach ($products as $product) {
                $id_products[] = $product['id_product'];
            }
        }

        return $id_products;
    }

    /**
     * Get total products of a list categories
     * @param array $id_categories
     * @param int $id_shop
     * <pre>
     *   array(
     *       id_category => int,
     *       ...
     *   )
     * @return int
     * */
    public static function getNumberProductsByCategories(array $id_categories, $id_shop = 0)
    {
        $query = new DbQuery();
        $query->select('COUNT(DISTINCT(cp.`id_product`))');
        $query->from('category_product', 'cp');
        $query->leftJoin('product_shop', 'ps', 'ps.id_product = cp.id_product');
        $query->where('cp.`id_category` IN (' . implode(',', $id_categories) . ')');
        if ($id_shop > 0) {
            $query->where('ps.`id_shop` = ' . (int) $id_shop);
        }
        return Db::getInstance()->getValue($query, true, false);
    }

    /**
     * @param int    $id_lang                language id
     * @param string $keyword                searched string
     * @param array  $excluded_id_categories
     *
     * @return array corresponding categories
     * <pre>
     * array(
     *   int => array(
     *      id_category => int,
     *      name => string,
     *   )
     * ...
     */
    public static function search($id_lang, $keyword, array $excluded_id_categories = array())
    {
        $sql = new DbQuery();
        $sql->select('cl.`id_category`, cl.`name`');
        $sql->from('category_lang', 'cl');
        $sql->where('cl.`name` LIKE \'%' . pSQL($keyword) . '%\'');
        $sql->where('`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('cl'));
        $sql->where('cl.`id_category` != ' . (int) Configuration::get('PS_HOME_CATEGORY'));
        if ($excluded_id_categories) {
            $sql->where('cl.`id_category` NOT IN (' . implode(',', $excluded_id_categories) . ')');
        }

        return Db::getInstance()->executeS($sql);
    }

    /**
     *
     * @param int $id_lang
     * @return array
     * <pre>
     * array(
     *  int => array(
     *      id_category => int,
     *      name => string
     *  )
     * ...
     * )
     */
    public static function getDefaultCategories($id_lang = null)
    {
        $context = Context::getContext();
        if (is_null($id_lang)) {
            $id_lang = $context->language->id;
        }
        $default_id_categories = array_unique(Configuration::get('POS_DEFAULT_CATEGORIES') ? explode(',', Configuration::get('POS_DEFAULT_CATEGORIES')) : array(Configuration::get('PS_HOME_CATEGORY')));
        $default_categories = array();
        if (is_array($default_categories) && !empty($default_id_categories)) {
            $sql = new DbQuery();
            $sql->select('cl.`id_category`, cl.`name`');
            $sql->from('category_lang', 'cl');
            $sql->where('`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('cl'));
            $sql->where('cl.`id_category` IN (' . implode(',', $default_id_categories) . ')');
            $default_categories = Db::getInstance()->executeS($sql);
        }
        return $default_categories;
    }
}
