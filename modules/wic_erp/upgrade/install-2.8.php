<?php
/**
* Module My Easy ERP Web In Color 
* 
*  @author    Web In Color - addons@webincolor.fr
*  @version 2.7.0
*  @uses Prestashop modules
*  @since 1.0 - mai 2014
*  @package Wic ERP
*  @copyright Copyright &copy; 2016, Web In Color
*  @license   http://www.webincolor.fr
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

/** 
* object module available
*/
function upgrade_module_2_8($object)
{
    $upgrade_version = '2.8';

    try {
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_order_receipt_dlc_bbd` (
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
                                        ) ENGINE='._MYSQL_ENGINE_.'  DEFAULT CHARSET=utf8;');
    } catch (Exception $e) {
        $e->getMessage();
    }

    Configuration::updateValue('WIC_ERP_VERSION', $upgrade_version);
    return true;
}
