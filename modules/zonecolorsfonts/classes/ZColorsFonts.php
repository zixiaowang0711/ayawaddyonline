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

class ZColorsFonts extends ObjectModel
{
    public $id;
    public $id_zcolorsfonts;
    public $id_shop;
    public $general;
    public $header;
    public $footer;
    public $content;
    public $product;
    public $fonts_import;
    public $fonts;
    public $custom_css;

    public static $definition = array(
        'table' => 'zcolorsfonts',
        'primary' => 'id_zcolorsfonts',
        'multilang' => false,
        'multilang_shop' => false,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'general' => array('type' => self::TYPE_STRING),
            'header' => array('type' => self::TYPE_STRING),
            'footer' => array('type' => self::TYPE_STRING),
            'content' => array('type' => self::TYPE_STRING),
            'product' => array('type' => self::TYPE_STRING),
            'fonts_import' => array('type' => self::TYPE_HTML),
            'fonts' => array('type' => self::TYPE_STRING, 'size' => 254),
            'custom_css' => array('type' => self::TYPE_HTML),
        ),
    );

    public function __construct($id_zcolorsfonts = null)
    {
        parent::__construct($id_zcolorsfonts);

        if (!$this->id_shop) {
            $this->id_shop = Context::getContext()->shop->id;
        }

        if ($this->general) {
            $this->general = Tools::unSerialize($this->general);
        }
        if ($this->header) {
            $this->header = Tools::unSerialize($this->header);
        }
        if ($this->footer) {
            $this->footer = Tools::unSerialize($this->footer);
        }
        if ($this->content) {
            $this->content = Tools::unSerialize($this->content);
        }
        if ($this->product) {
            $this->product = Tools::unSerialize($this->product);
        }
        if ($this->fonts) {
            $this->fonts = Tools::unSerialize($this->fonts);
        }
    }

    public function save($null_values = false, $autodate = true)
    {
        if ($this->general) {
            $this->general = serialize($this->general);
        }
        if ($this->header) {
            $this->header = serialize($this->header);
        }
        if ($this->footer) {
            $this->footer = serialize($this->footer);
        }
        if ($this->content) {
            $this->content = serialize($this->content);
        }
        if ($this->product) {
            $this->product = serialize($this->product);
        }
        if ($this->fonts) {
            $this->fonts = serialize($this->fonts);
        }

        return (int) $this->id > 0 ? $this->update($null_values) : $this->add($autodate, $null_values);
    }

    public static function getSettingsByShop()
    {
        $id_shop = Context::getContext()->shop->id;

        $id_zcolorsfonts = (int) Db::getInstance()->getValue(
            'SELECT id_zcolorsfonts
			FROM `'._DB_PREFIX_.'zcolorsfonts` m
			WHERE m.`id_shop` = '.(int) $id_shop
        );

        if ($id_zcolorsfonts) {
            return new self($id_zcolorsfonts);
        } else {
            return new self();
        }
    }
}
