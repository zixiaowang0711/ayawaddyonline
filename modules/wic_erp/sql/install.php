<?php
/**
 * Module My Easy ERP Web In Color
 *
 *  @author    Web In Color - addons@webincolor.fr
 *  @version 2.6
 *  @uses Prestashop modules
 *  @since 1.0 - mai 2014
 *  @package Wic ERP
 *  @copyright Copyright &copy; 2014, Web In Color
 *  @license   http://www.webincolor.fr
 */

$sql = array(
        'erp_order'                    => 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_order` (
  												`id_erp_order` int(11) unsigned NOT NULL AUTO_INCREMENT,
  												`id_supplier` int(11) unsigned NOT NULL,
                                                                                                `id_shop` int(11) unsigned DEFAULT \'0\',
  												`supplier_name` varchar(64) NOT NULL,
  												`id_erp_order_state` int(11) unsigned NOT NULL,
  												`id_currency` int(11) unsigned NOT NULL,
  												`reference` varchar(64) NOT NULL,
  												`date_add` datetime NOT NULL,
  												`date_upd` datetime NOT NULL,
  												`date_delivery_expected` datetime NOT NULL,
  												`total_te` decimal(20,6) DEFAULT \'0.000000\',
  												`total_with_discount_te` decimal(20,6) DEFAULT \'0.000000\',
  												`total_tax` decimal(20,6) DEFAULT \'0.000000\',
  												`total_ti` decimal(20,6) DEFAULT \'0.000000\',
  												`discount_rate` decimal(20,6) DEFAULT \'0.000000\',
  												`discount_value_te` decimal(20,6) DEFAULT \'0.000000\',
                                                                                                `shipping_cost` decimal(20,6) DEFAULT \'0.000000\',
                                                                                                `shipping_tax_rate` decimal(20,6) DEFAULT \'0.000000\',
  												PRIMARY KEY (`id_erp_order`),
  												KEY `id_supplier` (`id_supplier`),
  												KEY `reference` (`reference`)
												) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',
        'erp_order_detail'            => 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_order_detail` (
  												`id_erp_order_detail` int(11) unsigned NOT NULL AUTO_INCREMENT,
  												`id_erp_order` int(11) unsigned NOT NULL,
  												`id_product` int(11) unsigned NOT NULL,
  												`id_product_attribute` int(11) unsigned NOT NULL,
  												`reference` varchar(32) NOT NULL,
  												`supplier_reference` varchar(32) NOT NULL,
  												`name` varchar(128) NOT NULL,
  												`ean13` varchar(13) DEFAULT NULL,
  												`upc` varchar(12) DEFAULT NULL,
  												`id_currency` int(11) unsigned NOT NULL,
  												`exchange_rate` decimal(20,6) DEFAULT \'0.000000\',
  												`unit_price_te` decimal(20,6) DEFAULT \'0.000000\',
  												`quantity_ordered` int(11) unsigned NOT NULL,
  												`quantity_received` int(11) unsigned NOT NULL,
  												`price_te` decimal(20,6) DEFAULT \'0.000000\',
  												`discount_rate` decimal(20,6) DEFAULT \'0.000000\',
  												`discount_value_te` decimal(20,6) DEFAULT \'0.000000\',
  												`price_with_discount_te` decimal(20,6) DEFAULT \'0.000000\',
  												`tax_rate` decimal(20,6) DEFAULT \'0.000000\',
  												`tax_value` decimal(20,6) DEFAULT \'0.000000\',
  												`price_ti` decimal(20,6) DEFAULT \'0.000000\',
  												`tax_value_with_order_discount` decimal(20,6) DEFAULT \'0.000000\',
  												`price_with_order_discount_te` decimal(20,6) DEFAULT \'0.000000\',
  												PRIMARY KEY (`id_erp_order_detail`),
 												KEY `id_erp_order` (`id_erp_order`),
  												KEY `id_product` (`id_product`),
 												KEY `id_product_attribute` (`id_product_attribute`),
  												KEY `id_product_product_attribute` (`id_product`,`id_product_attribute`)
												) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',
        'erp_order_history'            => 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_order_history` (
  												`id_erp_order_history` int(11) unsigned NOT NULL AUTO_INCREMENT,
  												`id_erp_order` int(11) unsigned NOT NULL,
  												`id_employee` int(11) unsigned NOT NULL,
  												`employee_lastname` varchar(32) DEFAULT \'\',
  												`employee_firstname` varchar(32) DEFAULT \'\',
  												`id_state` int(11) unsigned NOT NULL,
  												`date_add` datetime NOT NULL,
  												PRIMARY KEY (`id_erp_order_history`),
  												KEY `id_erp_order` (`id_erp_order`),
 												KEY `id_employee` (`id_employee`),
  												KEY `id_state` (`id_state`)
												) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',
        'erp_order_receipt_history'    => 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_order_receipt_history` (
  												`id_erp_order_receipt_history` int(11) unsigned NOT NULL AUTO_INCREMENT,
  												`id_erp_order_detail` int(11) unsigned NOT NULL,
  												`id_employee` int(11) unsigned NOT NULL,
  												`employee_lastname` varchar(32) DEFAULT \'\',
  												`employee_firstname` varchar(32) DEFAULT \'\',
  												`id_erp_order_state` int(11) unsigned NOT NULL,
  												`quantity` int(11) unsigned NOT NULL,
  												`date_add` datetime NOT NULL,
 												PRIMARY KEY (`id_erp_order_receipt_history`),
  												KEY `id_erp_order_detail` (`id_erp_order_detail`),
  												KEY `id_erp_order_state` (`id_erp_order_state`)
												) ENGINE='._MYSQL_ENGINE_.'  DEFAULT CHARSET=utf8;',
        'erp_order_receipt_dlc_bbd'    => 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_order_receipt_dlc_bbd` (
  												`id_erp_order_receipt_dlc_bbd` int(11) unsigned NOT NULL AUTO_INCREMENT,
  												`id_erp_order_receipt_history` int(11) unsigned NOT NULL,
                                                                                                `batch_number` varchar(50) DEFAULT \'\',
                                                                                                `dlc` datetime,
                                                                                                `bbd` datetime,
                                                                                                `current_stock` tinyint(1) NOT NULL DEFAULT \'0\',
                                                                                                `quantity` int(11) unsigned NOT NULL,
  												`date_add` datetime NOT NULL,
 												PRIMARY KEY (`id_erp_order_receipt_dlc_bbd`),
  												KEY `id_erp_order_receipt_history` (`id_erp_order_receipt_history`),
  												KEY `batch_number` (`batch_number`),
                                                                                                KEY `dlc` (`dlc`),
                                                                                                KEY `bbd` (`bbd`)
												) ENGINE='._MYSQL_ENGINE_.'  DEFAULT CHARSET=utf8;',                                                                                            
        'erp_order_state'            => 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_order_state` (
  												`id_erp_order_state` int(11) unsigned NOT NULL AUTO_INCREMENT,
  												`delivery_note` tinyint(1) NOT NULL DEFAULT \'0\',
  												`editable` tinyint(1) NOT NULL DEFAULT \'0\',
  												`receipt_state` tinyint(1) NOT NULL DEFAULT \'0\',
  												`pending_receipt` tinyint(1) NOT NULL DEFAULT \'0\',
  												`enclosed` tinyint(1) NOT NULL DEFAULT \'0\',
  												`color` varchar(32) DEFAULT NULL,
  												PRIMARY KEY (`id_erp_order_state`)
												) ENGINE='._MYSQL_ENGINE_.'  DEFAULT CHARSET=utf8;',
        'erp_order_state_lang'        => 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_order_state_lang` (
  												`id_erp_order_state` int(11) unsigned NOT NULL,
  												`id_lang` int(11) unsigned NOT NULL,
  												`name` varchar(128) DEFAULT NULL,
  												PRIMARY KEY (`id_erp_order_state`,`id_lang`)
												) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',
        'erp_suppliers'                => 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_suppliers` (
  												`id_erp_suppliers` int(11) unsigned NOT NULL AUTO_INCREMENT,
  												`id_employee` int(11) unsigned NOT NULL,
  												`id_supplier` int(11) unsigned NOT NULL,
                                                                                                `id_lang` int(11) unsigned NOT NULL,
  												`delivery` int(11) DEFAULT NULL,
  												`delivery_change` int(11) DEFAULT NULL,
  												`email` varchar(128) DEFAULT NULL,
  												`manual_configuration` int(1) DEFAULT NULL,
                                                                                                `vat_exemption` int(1) DEFAULT NULL,
  												`date_add` datetime NOT NULL,
  												`date_upd` datetime NOT NULL,
  												PRIMARY KEY (`id_erp_suppliers`)
												) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',
        'erp_products'                => 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_products` (
  												`id_erp_products` int(11) unsigned NOT NULL AUTO_INCREMENT,
  												`id_product` int(11) unsigned NOT NULL,
  												`id_product_attribute` int(11) unsigned NOT NULL,
  												`id_employee` int(11) unsigned NOT NULL,
  												`min_quantity` int(11) DEFAULT NULL,
  												`safety_stock` int(11) DEFAULT NULL,
  												`unit_order` int(11) DEFAULT NULL,
  												`manual_configuration` int(1) DEFAULT NULL,
                                                                                                `min_quantity_by_warehouse` TEXT DEFAULT NULL,
                                                                                                `safety_stock_by_warehouse` TEXT DEFAULT NULL,
  												`date_add` datetime NOT NULL,
  												`date_upd` datetime NOT NULL,
  												PRIMARY KEY (`id_erp_products`),
  												KEY `id_product` (`id_product`),
  												KEY `id_product_attribute` (`id_product_attribute`)
												) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',
        'erp_products_by_warehouse'     => 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_products_by_warehouse` (
                                                                                                `id_erp_products` INT(11) NOT NULL ,
                                                                                                `id_warehouse` INT(11) NOT NULL ,
                                                                                                `min_quantity_by_warehouse` INT(11) NOT NULL ,
                                                                                                `safety_stock_by_warehouse` INT(11) NOT NULL ,
                                                                                                PRIMARY KEY `id_product_by_warehouse` (`id_erp_products`, `id_warehouse`)) 
                                                                                                ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',
        'erp_order_detail_by_warehouse'     => 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_order_detail_by_warehouse` (
                                                                                                `id_erp_order_detail` INT(11) NOT NULL ,
                                                                                                `id_warehouse` INT(11) NOT NULL ,
                                                                                                `quantity` INT(11) NOT NULL ,
                                                                                                PRIMARY KEY `id_order_detail_by_warehouse` (`id_erp_order_detail`, `id_warehouse`)) 
                                                                                                ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',
        'erp_cron'                => 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_cron` (
  												`id_erp_cron` int(11) unsigned NOT NULL AUTO_INCREMENT,
  												`date_add` datetime NOT NULL,
  												PRIMARY KEY (`id_erp_cron`)
												) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',
        );
