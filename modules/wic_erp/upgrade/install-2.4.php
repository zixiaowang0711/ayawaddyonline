<?php
/**
* Module My Easy ERP Web In Color 
* 
*  @author    Web In Color - addons@webincolor.fr
*  @version 2.4
*  @uses Prestashop modules
*  @since 1.0 - mai 2014
*  @package Wic ERP
*  @copyright Copyright &copy; 2014, Web In Color
*  @license   http://www.webincolor.fr
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

/** 
* object module available
*/
function upgrade_module_2_4($object)
{
    $upgrade_version = '2.4';

    $object->upgrade_detail[$upgrade_version] = array();
    try {
        if (!Db::getInstance()->execute(
                'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_order_attachement` (
					`id_erp_order_attachement` int(11) NOT NULL AUTO_INCREMENT,
					`id_erp_order` int(11) NOT NULL,
					`file_name` varchar(255) NOT NULL,
					`name` varchar(255) NOT NULL,
					`date_add` datetime NOT NULL,
				PRIMARY KEY (`id_erp_order_attachement`)
				) ENGINE='._MYSQL_ENGINE_.';
				ALTER TABLE
					`'._DB_PREFIX_.'erp_suppliers`
				ADD `minimum_price` DECIMAL(20,6) NULL DEFAULT \'0.00\' AFTER `email`,
				ADD `minimum_price_free_shipping` DECIMAL(20,6) NULL DEFAULT \'0.00\' AFTER `minimum_price`,
				ADD `shipping_price` DECIMAL(20,6) NULL DEFAULT \'0.00\' AFTER `minimum_price_free_shipping`;'
            )) {
            $object->upgrade_detail[$upgrade_version][] = $object->l('Can\'t add new field in methodtable');
        }
    } catch (Exception $e) {
        $e->getMessage();
    }

    Configuration::updateValue('WIC_ERP_VERSION', $upgrade_version);
    return true;
}
