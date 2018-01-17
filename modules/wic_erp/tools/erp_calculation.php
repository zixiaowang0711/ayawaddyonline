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

include_once(dirname(__FILE__).'/../../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../../init.php');
include_once(dirname(__FILE__).'/../classes/ErpProducts.php');
include_once(dirname(__FILE__).'/../classes/ErpSuppliers.php');

if (Tools::getValue('token') != sha1(_COOKIE_KEY_.'wic_erp')) {
    die(Tools::displayError('The token is invalid, please check the export url in your module configuration.'));
} elseif (Tools::getValue('manual')) {
    ErpProducts::updateErpProducts();
    
    $sql = 'INSERT INTO `'._DB_PREFIX_.'erp_cron` (`id_erp_cron`, `date_add`) VALUES (NULL, \''.date('Y-m-d H:i:s').'\')';
    Db::getInstance()->Execute($sql);
} else {
    if (Configuration::get('WIC_ERP_STOCK_MANAGEMENT') == 'normal') {
        $sql = 'SELECT MAX(`id_erp_cron`), `date_add` FROM `'._DB_PREFIX_.'erp_cron` GROUP BY `id_erp_cron`';
        $result = Db::getInstance()->getRow($sql);

        if (isset($result['date_add']) && $result['date_add']) {
            if (strtotime($result['date_add'].'+'.Configuration::get('WIC_ERP_CRON_DAYS').' day') <= strtotime(date('Y-m-d H:i:s'))) {
                ErpProducts::updateErpProducts();

                $sql = 'INSERT INTO `'._DB_PREFIX_.'erp_cron` (`id_erp_cron`, `date_add`) VALUES (NULL, \''.date('Y-m-d H:i:s').'\')';
                Db::getInstance()->Execute($sql);
            } else {
                die(Tools::displayError('Your database is up to date'));
            }
        } else {
            ErpProducts::updateErpProducts();

            $sql = 'INSERT INTO `'._DB_PREFIX_.'erp_cron` (`id_erp_cron`, `date_add`) VALUES (NULL, \''.date('Y-m-d H:i:s').'\')';
            Db::getInstance()->Execute($sql);
        }
    } else {
        die(Tools::displayError('No Cron Job with this mode'));
    }
}
