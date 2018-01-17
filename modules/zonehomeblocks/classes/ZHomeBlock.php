<?php
/**
 * 2007-2017 PrestaShop.
 *
 * PHP version 5
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2017 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

class ZHomeBlock extends ObjectModel
{
    public $id;
    public $id_zhomeblock;
    public $id_shop;
    public $title;
    public $active = 1;
    public $position;
    public $hook;
    public $block_type;
    public $custom_class;
    public $product_filter;
    public $product_options;
    public $static_html;

    public static $definition = array(
        'table' => 'zhomeblock',
        'primary' => 'id_zhomeblock',
        'multilang' => true,
        'multilang_shop' => false,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'position' => array('type' => self::TYPE_INT),
            'hook' => array('type' => self::TYPE_STRING, 'size' => 128),
            'block_type' => array('type' => self::TYPE_STRING, 'size' => 128),
            'custom_class' => array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'size' => 50),
            'product_options' => array('type' => self::TYPE_STRING),
            'product_filter' => array('type' => self::TYPE_STRING, 'size' => 128),
            'title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 254),
            'static_html' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
        ),
    );

    public function __construct($id_zhomeblock = null, $id_lang = null)
    {
        parent::__construct($id_zhomeblock, $id_lang);

        if (!$this->id_shop) {
            $this->id_shop = Context::getContext()->shop->id;
        }

        $this->product_options = $this->initProductOptions($this->product_options);

        if (!$this->position) {
            $this->position = 1 + $this->getMaxPosition(Tools::getValue('hook', false));
        }
    }

    public function save($null_values = false, $autodate = true)
    {
        $this->product_options = serialize($this->product_options);

        return (int) $this->id > 0 ? $this->update($null_values) : $this->add($autodate, $null_values);
    }

    public static function initProductOptions($str)
    {
        $options = Tools::unSerialize($str);
        if (!$options) {
            $options = array();
        }
        if (!isset($options['limit']) || (int) $options['limit'] < 1) {
            $options['limit'] = 10;
        }
        if (!isset($options['enable_slider'])) {
            $options['enable_slider'] = 0;
        }
        if (!isset($options['auto_scroll'])) {
            $options['auto_scroll'] = 0;
        }
        if (!isset($options['number_column'])) {
            $options['number_column'] = 5;
        }
        if (!isset($options['sort_order'])) {
            $options['sort_order'] = 'product.position.asc';
        }

        return $options;
    }

    public static function getList($id_lang = null, $hook = false, $active = true)
    {
        $id_lang = is_null($id_lang) ? Context::getContext()->language->id : $id_lang;
        $id_shop = Context::getContext()->shop->id;

        $query = 'SELECT *
            FROM `'._DB_PREFIX_.'zhomeblock` b
            LEFT JOIN `'._DB_PREFIX_.'zhomeblock_lang` bl ON b.`id_zhomeblock` = bl.`id_zhomeblock`
            WHERE b.`id_shop` = '.(int) $id_shop.'
                AND `id_lang` = '.(int) $id_lang.'
                '.($active ? 'AND `active` = 1' : '').'
                '.($hook ? 'AND `hook` LIKE \''.pSQL($hook).'\'' : '').'
            GROUP BY b.id_zhomeblock
            ORDER BY b.`position` ASC';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        return $result;
    }

    public function getMaxPosition($hook = false)
    {
        $id_shop = Context::getContext()->shop->id;
        $query = 'SELECT MAX(b.`position`)
            FROM `'._DB_PREFIX_.'zhomeblock` b
            WHERE b.`id_shop` = '.(int) $id_shop.'
            '.($hook ? 'AND `hook` LIKE \''.pSQL($hook).'\'' : '');

        return (int) Db::getInstance()->getValue($query);
    }

    public static function updatePosition($id_zhomeblock, $position)
    {
        $query = 'UPDATE `'._DB_PREFIX_.'zhomeblock`
			SET `position` = '.(int) $position.'
			WHERE `id_zhomeblock` = '.(int) $id_zhomeblock;

        Db::getInstance()->execute($query);
    }

    public function getProductsAutocompleteInfo($id_lang = null)
    {
        if (!$id_lang) {
            $id_lang = Context::getContext()->language->id;
        }

        $products = array();

        if (!empty($this->product_options['selected_products'])) {
            $implode_product_id = implode(array_map('intval', $this->product_options['selected_products']), ',');
            $query = 'SELECT p.`id_product`, p.`reference`, pl.name
                FROM `'._DB_PREFIX_.'product` p
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product` = p.`id_product` AND pl.`id_lang` = '.(int) $id_lang.')
                WHERE p.`id_product` IN ('.pSQL($implode_product_id).')
                ORDER BY FIELD(p.`id_product`, '.pSQL($implode_product_id).')';

            $rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

            foreach ($rows as $row) {
                $products[$row['id_product']] = trim($row['name']).(!empty($row['reference']) ? ' (ref: '.$row['reference'].')' : '');
            }
        }

        return $products;
    }

    public static function getProductsByArrayId($array_product_id = null, $id_lang = null)
    {
        if (empty($array_product_id)) {
            return false;
        }
        if (!$id_lang) {
            $id_lang = Context::getContext()->language->id;
        }
        $implode_product_id = implode(array_map('intval', $array_product_id), ',');

        $sql = new DbQuery();
        $sql->select(
            'p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`,
			pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, 
			pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image, il.`legend`, 
			m.`name` AS manufacturer_name,
			DATEDIFF(
				product_shop.`date_add`,
				DATE_SUB(
					NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ?
                    Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
				)
			) > 0 AS new'
        );

        $sql->from('product', 'p');
        $sql->join(Shop::addSqlAssociation('product', 'p'));
        $sql->leftJoin('product_lang', 'pl', 'p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int) $id_lang.Shop::addSqlRestrictionOnLang('pl'));
        $sql->leftJoin('image', 'i', 'i.`id_product` = p.`id_product`');
        $sql->join(Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1'));
        $sql->leftJoin('image_lang', 'il', 'i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int) $id_lang);
        $sql->leftJoin('manufacturer', 'm', 'm.`id_manufacturer` = p.`id_manufacturer`');

        $sql->where('p.`id_product` IN ('.pSQL($implode_product_id).')');

        $sql->orderBy('FIELD(p.`id_product`, '.pSQL($implode_product_id).')');

        $sql->groupBy('product_shop.id_product');

        if (Combination::isFeatureActive()) {
            $sql->select('MAX(product_attribute_shop.id_product_attribute) id_product_attribute');
            $sql->leftOuterJoin('product_attribute', 'pa', 'p.`id_product` = pa.`id_product`');
            $sql->join(Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on = 1'));
        }
        $sql->join(Product::sqlStock('p', Combination::isFeatureActive() ? 'product_attribute_shop' : 0));

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (!$result) {
            return false;
        }

        return Product::getProductsProperties((int) $id_lang, $result);
    }
}
