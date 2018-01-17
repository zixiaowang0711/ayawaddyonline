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

class ZHomeTab extends ObjectModel
{
    public $id;
    public $id_zhometab;
    public $id_zhomeblock;
    public $title;
    public $active = 1;
    public $position;
    public $block_type;
    public $product_filter;
    public $product_options;
    public $static_html;

    public static $definition = array(
        'table' => 'zhometab',
        'primary' => 'id_zhometab',
        'multilang' => true,
        'multilang_shop' => false,
        'fields' => array(
            'id_zhomeblock' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'position' => array('type' => self::TYPE_INT),
            'block_type' => array('type' => self::TYPE_STRING, 'size' => 128),
            'product_options' => array('type' => self::TYPE_STRING),
            'product_filter' => array('type' => self::TYPE_STRING, 'size' => 128),
            'title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName',
            'required' => true, 'size' => 254),
            'static_html' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
        ),
    );

    public function __construct($id_zhomeblock, $id_zhometab = null, $id_lang = null)
    {
        parent::__construct($id_zhometab, $id_lang);

        if ($id_zhomeblock) {
            $this->id_zhomeblock = $id_zhomeblock;
        }

        $this->product_options = $this->initProductOptions($this->product_options);

        if (!$this->position) {
            $this->position = 1 + $this->getMaxPosition();
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

    public static function getList($id_zhomeblock, $id_lang = null, $active = true)
    {
        $id_lang = is_null($id_lang) ? Context::getContext()->language->id : $id_lang;

        $query = 'SELECT *
            FROM `'._DB_PREFIX_.'zhometab` b
            LEFT JOIN `'._DB_PREFIX_.'zhometab_lang` bl ON b.`id_zhometab` = bl.`id_zhometab`
            WHERE b.`id_zhomeblock` = '.(int) $id_zhomeblock.'
            AND `id_lang` = '.(int) $id_lang.'
            '.($active ? 'AND `active` = 1' : '').'
            GROUP BY b.`id_zhometab`
            ORDER BY b.`position` ASC';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        return $result;
    }

    public function getMaxPosition()
    {
        $query = 'SELECT MAX(b.`position`)
            FROM `'._DB_PREFIX_.'zhometab` b
            WHERE b.`id_zhomeblock` = '.(int) $this->id_zhomeblock;

        return (int) Db::getInstance()->getValue($query);
    }

    public static function updatePosition($id_zhometab, $position)
    {
        $query = 'UPDATE `'._DB_PREFIX_.'zhometab`
            SET `position` = '.(int) $position.'
            WHERE `id_zhometab` = '.(int) $id_zhometab;

        Db::getInstance()->execute($query);
    }

    public function getProductsAutocompleteInfo($id_lang = null)
    {
        $id_lang = is_null($id_lang) ? Context::getContext()->language->id : $id_lang;

        $products = array();

        if (!empty($this->product_options['selected_products'])) {
            $query = 'SELECT p.`id_product`, p.`reference`, pl.name
                FROM `'._DB_PREFIX_.'product` p
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product` = p.`id_product` AND pl.`id_lang` = '.(int) $id_lang.')
                WHERE p.`id_product` IN ('.implode(array_map('intval', $this->product_options['selected_products']), ',').')';

            $rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

            foreach ($rows as $row) {
                $products[$row['id_product']] = trim($row['name']).(!empty($row['reference']) ? ' (ref: '.$row['reference'].')' : '');
            }
        }

        return $products;
    }
}
