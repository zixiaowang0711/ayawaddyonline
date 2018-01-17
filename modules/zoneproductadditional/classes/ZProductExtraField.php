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

class ZProductExtraField extends ObjectModel
{
    public $id;
    public $id_zproduct_extra_field;
    public $id_shop;
    public $active = 1;
    public $scope = 'All Products';
    public $categories = '';
    public $products = '';
    public $position;
    public $title;
    public $content;
    public $custom_class;

    public static $definition = array(
        'table' => 'zproduct_extra_field',
        'primary' => 'id_zproduct_extra_field',
        'multilang' => true,
        'multilang_shop' => false,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'scope' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 50),
            'categories' => array('type' => self::TYPE_STRING, 'size' => 255),
            'products' => array('type' => self::TYPE_STRING, 'size' => 255),
            'position' => array('type' => self::TYPE_INT),
            'custom_class' => array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'size' => 50),
            'title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'size' => 255, 'required' => true),
            'content' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
        ),
    );

    public function __construct($id_zproduct_extra_field = null, $id_lang = null)
    {
        parent::__construct($id_zproduct_extra_field, $id_lang);

        if (!$this->id_shop) {
            $this->id_shop = Context::getContext()->shop->id;
        }

        if (!$this->position) {
            $this->position = 1 + $this->getMaxPosition();
        }
    }

    public static function getList($id_lang)
    {
        $id_shop = Context::getContext()->shop->id;
        $query = 'SELECT *
            FROM `'._DB_PREFIX_.'zproduct_extra_field` e
            LEFT JOIN `'._DB_PREFIX_.'zproduct_extra_field_lang` el ON e.`id_zproduct_extra_field` = el.`id_zproduct_extra_field`
            WHERE e.`id_shop` = '.(int) $id_shop.'
            '.($id_lang ? 'AND `id_lang` = '.(int) $id_lang : '').'
            '.(!$id_lang ? 'GROUP BY e.`id_zproduct_extra_field`' : '').'
            '.'ORDER BY e.`position` ASC';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        return $result;
    }

    public static function getMaxPosition()
    {
        $id_shop = Context::getContext()->shop->id;
        $query = 'SELECT MAX(e.`position`)
            FROM `'._DB_PREFIX_.'zproduct_extra_field` e
            WHERE e.`id_shop` = '.(int) $id_shop;

        return (int) Db::getInstance()->getValue($query);
    }

    public static function updatePosition($id_zproduct_extra_field, $position)
    {
        $query = 'UPDATE `'._DB_PREFIX_.'zproduct_extra_field`
			SET `position` = '.(int) $position.'
			WHERE `id_zproduct_extra_field` = '.(int) $id_zproduct_extra_field;

        Db::getInstance()->execute($query);
    }

    public static function getCategoriesByIdField($id_zproduct_extra_field = 0)
    {
        if ($id_zproduct_extra_field == 0) {
            return array();
        }

        $product_extrafield = new self($id_zproduct_extra_field);
        if ($product_extrafield->categories != '') {
            return array_map('intval', explode(',', $product_extrafield->categories));
        } else {
            return array();
        }
    }

    public static function getProductsByIdField($id_zproduct_extra_field = 0)
    {
        if ($id_zproduct_extra_field == 0) {
            return array();
        }

        $id_shop = Context::getContext()->shop->id;
        $id_lang = Context::getContext()->language->id;
        $products = array();

        $product_extrafield = new self($id_zproduct_extra_field);
        if ($product_extrafield->products != '') {
            $sql = 'SELECT p.`id_product`, `reference`, pl.name
			FROM `'._DB_PREFIX_.'product` p
			LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product` = p.`id_product` AND pl.`id_lang` = '.(int) $id_lang.')
			WHERE p.`id_product` IN ('.pSQL($product_extrafield->products).') AND pl.`id_shop` = '.$id_shop;

            $rows = Db::getInstance()->executeS($sql);

            foreach ($rows as $row) {
                $products[$row['id_product']] = trim($row['name']).(!empty($row['reference']) ? ' (ref: '.$row['reference'].')' : '');
            }
        }

        return $products;
    }

    public static function getFieldsByProductId($id_product)
    {
        if (!$id_product) {
            return false;
        }

        $id_lang = Context::getContext()->language->id;
        $sql = 'SELECT e.*, el.`title`, el.`content`
			FROM `'._DB_PREFIX_.'zproduct_extra_field` e
			LEFT JOIN `'._DB_PREFIX_.'zproduct_extra_field_lang` el 
				ON (el.`id_zproduct_extra_field` = e.`id_zproduct_extra_field` AND el.`id_lang` = '.(int) $id_lang.')
			WHERE e.`active` = 1
            ORDER BY e.position';

        $rows = Db::getInstance()->executeS($sql);

        $results = array();
        foreach ($rows as $row) {
            switch ($row['scope']) {
                case 'All Products':
                    $results[] = $row;
                    break;
                case 'Some Products':
                    if (in_array($id_product, explode(',', $row['products']))) {
                        $results[] = $row;
                    }
                    break;
                case 'Some Categories':
                    $product_categories = Product::getProductCategories($id_product);
                    if (array_intersect(explode(',', $row['categories']), $product_categories)) {
                        $results[] = $row;
                    }
                    break;
            }
        }

        if (empty($results)) {
            return false;
        } else {
            return $results;
        }
    }
}
