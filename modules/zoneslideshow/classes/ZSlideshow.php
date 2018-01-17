<?php
/**
 * 2007-2017 PrestaShop.
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

class ZSlideshow extends ObjectModel
{
    public $id;
    public $id_zslideshow;
    public $id_shop;
    public $active = 1;
    public $position;
    public $image;
    public $transition;
    public $title;
    public $link;
    public $caption;
    public $related_products;

    public static $definition = array(
        'table' => 'zslideshow',
        'primary' => 'id_zslideshow',
        'multilang' => true,
        'multilang_shop' => false,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'position' => array('type' => self::TYPE_INT),
            'image' => array('type' => self::TYPE_STRING, 'size' => 100, 'required' => true, 'validate' => 'isCleanHtml'),
            'transition' => array('type' => self::TYPE_STRING, 'size' => 50, 'validate' => 'isCatalogName'),
            'related_products' => array('type' => self::TYPE_STRING, 'size' => 100),
            'title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 254),
            'link' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrlOrEmpty', 'size' => 254),
            'caption' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
        ),
    );

    public function __construct($id_zslideshow = null, $id_lang = null)
    {
        parent::__construct($id_zslideshow, $id_lang);

        if (!$this->id_shop) {
            $this->id_shop = Context::getContext()->shop->id;
        }

        if ($this->related_products) {
            $this->related_products = Tools::unSerialize($this->related_products);
        }

        if (!$this->position) {
            $this->position = 1 + $this->getMaxPosition();
        }
    }

    public function save($null_values = false, $autodate = true)
    {
        if ($this->related_products) {
            $this->related_products = serialize($this->related_products);
        }

        return (int) $this->id > 0 ? $this->update($null_values) : $this->add($autodate, $null_values);
    }

    public static function getList($id_lang = null, $active = true)
    {
        $id_lang = is_null($id_lang) ? Context::getContext()->language->id : (int) $id_lang;
        $id_shop = Context::getContext()->shop->id;

        $query = 'SELECT *
            FROM `'._DB_PREFIX_.'zslideshow` s
            LEFT JOIN `'._DB_PREFIX_.'zslideshow_lang` sl ON s.`id_zslideshow` = sl.`id_zslideshow`
            WHERE s.`id_shop` = '.(int) $id_shop.'
            AND `id_lang` = '.(int) $id_lang.'
            '.($active ? 'AND `active` = 1' : '').'
            GROUP BY s.`id_zslideshow`
            ORDER BY s.`position` ASC';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        if ($result) {
            foreach ($result as &$row) {
                $row['related_products'] = Tools::unSerialize($row['related_products']);
            }
        }

        return $result;
    }

    public static function getMaxPosition()
    {
        $id_shop = Context::getContext()->shop->id;
        $query = 'SELECT MAX(s.`position`)
            FROM `'._DB_PREFIX_.'zslideshow` s
            WHERE s.`id_shop` = '.(int) $id_shop;

        return (int) Db::getInstance()->getValue($query);
    }

    public static function updatePosition($id_zslideshow, $position)
    {
        $query = 'UPDATE `'._DB_PREFIX_.'zslideshow`
			SET `position` = '.(int) $position.'
			WHERE `id_zslideshow` = '.(int) $id_zslideshow;

        Db::getInstance()->execute($query);
    }

    public function getProductsAutocompleteInfo($id_lang = null)
    {
        if (!$id_lang) {
            $id_lang = Context::getContext()->language->id;
        }

        $products = array();

        if (!empty($this->related_products)) {
            $query = 'SELECT p.`id_product`, p.`reference`, pl.name
                FROM `'._DB_PREFIX_.'product` p
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product` = p.`id_product` AND pl.`id_lang` = '.(int) $id_lang.')
                WHERE p.`id_product` IN ('.implode(array_map('intval', $this->related_products), ',').')';

            $rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

            foreach ($rows as $row) {
                $products[$row['id_product']] = trim($row['name']).(!empty($row['reference']) ? ' (ref: '.$row['reference'].')' : '');
            }
        }

        return $products;
    }

    public static function getProductsByArrayId($array_product_id = null, $id_lang = null, $limit = 2)
    {
        if (empty($array_product_id)) {
            return false;
        }
        if (!$id_lang) {
            $id_lang = Context::getContext()->language->id;
        }

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

        $sql->where('p.`id_product` IN ('.implode(array_map('intval', $array_product_id), ',').')');
        $sql->limit((int) $limit);

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
