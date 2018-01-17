<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Search index for Point of Sale.
 */
class PosSearchIndex extends Search
{

    /**
     * @var array
     *            <pre>
     *            array(
     *            int,
     *            int,
     *            ...
     *            )</pre>
     */
    public $id_products = array();

    /**
     * @var bool
     */
    public $full = false;

    /**
     * @var bool
     */
    public $delete = false;

    /**
     * @var array
     *            <pre>
     *            array(
     *            string => int,
     *            string => int,
     *            ...
     *            )</pre>
     */
    protected $weights = array();

    /**
     * @var array
     *            <pre>
     *            array(
     *            PosProductIndex,
     *            ...
     *            )
     */
    protected $product_indexes = array();

    const PAGE_LIMIT = 50;

    /**
     * @param bool $full
     * @param bool $delete
     * @param int  $id_product
     */
    public function __construct($full = false, $delete = false, $id_product = 0)
    {
        $this->full = (bool) $full;
        $this->delete = (bool) $delete;
        if ($id_product > 0) {
            $this->id_products = array((int) $id_product);
        }
    }

    public function run()
    {
        ini_set('max_execution_time', 7200);
        $this->delete();
        if ($this->delete) {
            return true;
        }

        $this->loadWeights();
        if (empty($this->weights)) {
            return false;
        }

        $id_products_to_index = $this->full ? $this->getAvailableIdProducts() : array_diff($this->getAvailableIdProducts(), $this->getIndexedIdProducts());
        if (empty($id_products_to_index)) {
            return true;
        }

        foreach (array_chunk($id_products_to_index, self::PAGE_LIMIT) as $id_products) {
            $this->product_indexes = array();
            $this->loadProductIndexes($id_products);
            if (empty($this->product_indexes)) {
                continue;
            }
            foreach ($this->product_indexes as $product_index) {
                $product_index->indexing();
            }
        }
        $this->clearCache();
        return true;
    }

    /**
     * Only return id_products whose visibility are in scope.
     *
     * @return array
     *               <pre>
     *               array (
     *               int,
     *               int,
     *               ...
     *               )</pre>
     */
    protected function getAvailableIdProducts()
    {
        $cache_key = __CLASS__ . __FUNCTION__;
        if (!Cache::isStored($cache_key)) {
            $id_products_to_index = array();
            if (!empty($this->id_products)) {
                $id_products_to_index = $this->id_products;
            } else {
                $offset = 0;
                $page = 1;
                while (1) {
                    $query = new DbQuery();
                    $query->select('p.`id_product`');
                    $query->from('product', 'p');
                    $query->join(Shop::addSqlAssociation('product', 'p', true, null, true));
                    $query->where(!PosConfiguration::get('POS_PRODUCT_INACTIVE') ? 'product_shop.`active` = 1' : null);
                    $product_visibilities = PosConfiguration::getProductVisibilities();
                    $product_visibility = array();
                    foreach ($product_visibilities as $key => $value) {
                        if (Configuration::get($key)) {
                            $product_visibility[] = $value;
                        }
                    }
                    $query->where(!empty($product_visibility) ? 'p.`visibility` IN ("' . implode('","', $product_visibility) . '")' : null);
                    $query->limit(self::PAGE_LIMIT, $offset);
                    $products = Db::getInstance()->executeS($query);
                    if (empty($products)) {
                        break;
                    } else {
                        foreach ($products as $product) {
                            $id_products_to_index[] = (int) $product['id_product'];
                        }
                        $offset = ( ++$page - 1) * self::PAGE_LIMIT;
                    }
                }
            }
            Cache::store($cache_key, $id_products_to_index);
        }

        return Cache::retrieve($cache_key);
    }

    /**
     * @param array $id_products
     *                           <pre>
     *                           array(
     *                           int,
     *                           int,
     *                           ...
     *                           )
     */
    protected function loadProductIndexes(array $id_products)
    {
        $db = Db::getInstance();
        $query = new DbQuery();
        $query->select('p.`id_product`');
        $query->select('pl.`id_lang`');
        $query->select('pl.`id_shop`');
        $query->select('l.`iso_code`');
        $select_fields = array(
            'reference' => 'p.`reference`',
            'supplier_reference' => 'p.`supplier_reference`',
            'ean13' => 'p.`ean13`',
            'upc' => 'p.`upc`',
            'pname' => 'pl.`name` AS `pname`',
            'description' => 'pl.`description`',
            'description_short' => 'pl.`description_short`',
            'cname' => 'cl.`name` AS `cname`',
            'mname' => 'm.`name` AS `mname`',
        );
        foreach ($select_fields as $key => $field) {
            if (isset($this->weights[$key])) {
                $query->select($field);
            }
        }
        $query->from('product', 'p');
        $query->leftJoin('product_lang', 'pl', 'p.`id_product` = pl.`id_product`');
        $query->join(Shop::addSqlAssociation('product', 'p', true, null, true));
        $query->leftJoin('category_lang', 'cl', 'cl.`id_category` = product_shop.`id_category_default` AND pl.`id_lang` = cl.`id_lang` AND cl.`id_shop` = product_shop.`id_shop`');
        $query->leftJoin('manufacturer', 'm', 'm.`id_manufacturer` = p.`id_manufacturer`');
        $query->leftJoin('lang', 'l', 'l.`id_lang` = pl.`id_lang`');
        $query->where('p.`id_product` IN (' . implode(',', array_map('intval', $id_products)) . ')');
        $query->where('pl.`id_shop` = product_shop.`id_shop`');
        $products_to_index = Db::getInstance()->executeS($query);
        foreach ($products_to_index as $product) {
            if (isset($this->weights['tags'])) {
                $product['tags'] = self::getTags($db, $product['id_product'], $product['id_lang']);
            }
            if (isset($this->weights['attributes'])) {
                $product['attributes'] = self::getAttributes($db, $product['id_product'], $product['id_lang']);
            }
            if (isset($this->weights['features'])) {
                $product['features'] = self::getFeatures($db, $product['id_product'], $product['id_lang']);
            }
            $attribute_codes = array_filter($this->getAttributeCodes($product['id_product'], 'trim'));
            $product = array_merge($product, $attribute_codes);
            $pos_product_index = new PosProductIndex($product['id_product'], $product['id_shop'], $product['id_lang']);
            foreach ($product as $key => $value) {
                if (isset($this->weights[$key])) {
                    $words = is_array($value) ? $value : $this->breakKeywordsIntoArray($value, $product['id_lang'], $product['iso_code']);
                    $pos_product_index->addWordIndexes($words, $this->weights[$key]);
                }
            }
            $this->product_indexes[] = $pos_product_index;
        }
    }

    protected function loadWeights()
    {
        $this->weights = array_filter(array(
            'pname' => Configuration::get('PS_SEARCH_WEIGHT_PNAME'),
            'cname' => Configuration::get('PS_SEARCH_WEIGHT_CNAME'),
            'mname' => Configuration::get('PS_SEARCH_WEIGHT_MNAME'),
            'reference' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'pa_reference' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'supplier_reference' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'pa_supplier_reference' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'ean13' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'pa_ean13' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'upc' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'pa_upc' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'description_short' => Configuration::get('PS_SEARCH_WEIGHT_SHORTDESC'),
            'description' => Configuration::get('PS_SEARCH_WEIGHT_DESC'),
            'tags' => Configuration::get('PS_SEARCH_WEIGHT_TAG'),
            'attributes' => Configuration::get('PS_SEARCH_WEIGHT_ATTRIBUTE'),
            'features' => Configuration::get('PS_SEARCH_WEIGHT_FEATURE'),
        ));
    }

    protected function delete()
    {
        $truncate = !Shop::isFeatureActive() || Shop::getContext() == Shop::CONTEXT_ALL;
        if ($this->full) {
            if ($truncate) {
                Db::getInstance()->execute('TRUNCATE ' . _DB_PREFIX_ . 'pos_search_index');
                Db::getInstance()->execute('TRUNCATE ' . _DB_PREFIX_ . 'pos_search_word');
            } else {
                Db::getInstance()->execute('DELETE `psi`, `psw` FROM `' . _DB_PREFIX_ . 'pos_search_index` psi INNER JOIN `' . _DB_PREFIX_ . 'pos_search_word` psw ON ( psw.`id_word` = psi.`id_word` AND psw.`id_shop` = ' . (int) Shop::getContextShopID() . ')');
            }
        } else {
            if ($this->id_products) {
                $id_products_to_delete = $this->id_products;
            } else {
                // Add missing indexes:
                // Chances are, some products don't need to index anymore (due to settings changed).
                // In this case, just simply delete those indexes.
                $id_products_to_index = $this->getAvailableIdProducts();
                $indexed_id_products = $this->getIndexedIdProducts();
                $id_products_to_delete = array_diff($indexed_id_products, $id_products_to_index);
            }
            if (!empty($id_products_to_delete)) {
                $query = 'DELETE FROM `' . _DB_PREFIX_ . 'pos_search_index` WHERE `id_product` IN (' . implode(',', $id_products_to_delete) . ')';
                Db::getInstance()->execute($query);
            }
        }
    }

    /**
     * @param string $string
     * @param int    $id_lang
     * @param string $iso_code
     *
     * @return array
     *               <pre>
     *               array(
     *               string,
     *               string,
     *               ...
     *               )</pre>
     */
    protected function breakKeywordsIntoArray($string, $id_lang, $iso_code)
    {
        $words = array();
        $sanitize_words = explode(' ', self::sanitize($string, (int) $id_lang, true, $iso_code));
        foreach ($sanitize_words as $word) {
            $word = Tools::substr($word, 0, PS_SEARCH_MAX_WORD_LENGTH);
            if (!empty($word)) {
                $words[] = $word;
            }
        }

        return $words;
    }

    /**
     * @param int $id_product
     *
     * @return array
     *               <pre>
     *               array(
     *               string => string,// field => keywords
     *               string => string,
     *               ...
     *               )</pre>
     */
    protected function getAttributeCodes($id_product)
    {
        $select_attribute_codes = array(
            'pa_reference' => 'GROUP_CONCAT(`reference` SEPARATOR " ") AS `pa_reference`',
            'pa_supplier_reference' => 'GROUP_CONCAT(`supplier_reference` SEPARATOR " ") AS `pa_supplier_reference`',
            'pa_ean13' => 'GROUP_CONCAT(`ean13` SEPARATOR " ") AS `pa_ean13`',
            'pa_upc' => 'GROUP_CONCAT(`upc` SEPARATOR " ") AS `pa_upc`',
        );
        $select_fields = array();
        foreach ($select_attribute_codes as $key => $select_field) {
            if (isset($this->weights[$key])) {
                $select_fields[] = $select_field;
            }
        }
        $product_attributes = array();
        if (!empty($select_fields)) {
            $query = new DbQuery();
            $query->select(implode(',', $select_fields));
            $query->from('product_attribute');
            $query->where('`id_product` = ' . (int) $id_product . ' ');
            $query->groupBy('`id_product`');
            $product_attributes = Db::getInstance()->getRow($query);
        }

        return !empty($product_attributes) ? $product_attributes : array();
    }

    /**
     * @return array
     *               <pre>
     *               array(
     *               int,
     *               int,
     *               ...
     *               )</pre>
     */
    protected function getIndexedIdProducts()
    {
        $sub_query = new DbQuery(); // Get id_words based on shops
        $sub_query->select('DISTINCT `id_word`');
        $sub_query->from('pos_search_word');
        $sub_query->where('`id_shop` IN (' . implode(',', Shop::getContextListShopID()) . ')');

        $query = new DbQuery(); // Get id_products based on id_words
        $query->select('DISTINCT `id_product`');
        $query->from('pos_search_index');
        $query->where('`id_word` IN (' . $sub_query->build() . ')');
        $id_products = Db::getInstance()->executeS($query, false);

        $indexed_id_products = array();
        if (!empty($id_products)) {
            foreach ($id_products as $id_product) {
                $indexed_id_products[] = (int) $id_product['id_product'];
            }
        }

        return $indexed_id_products;
    }

    protected function clearCache()
    {
        if (_PS_CACHE_ENABLED_) {
            switch (_PS_CACHING_SYSTEM_) {
                case 'CacheFs':
                    CacheFs::deleteCacheDirectory();
                    CacheFs::createCacheDirectories((int) Configuration::get('PS_CACHEFS_DIRECTORY_DEPTH'));
                    break;

                default:
                    Cache::getInstance()->flush();
                    break;
            }
        }
    }
}
