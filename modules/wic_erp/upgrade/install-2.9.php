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
function upgrade_module_2_9($object)
{
    $upgrade_version = '2.9';
    
    $id_lang_fr = Language::getIdByIso('fr');
    $admin_erp_stock_transfert = array();
    
    try {
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_products_by_warehouse` (
                `id_erp_products` INT(11) NOT NULL ,
                `id_warehouse` INT(11) NOT NULL ,
                `min_quantity_by_warehouse` INT(11) NOT NULL ,
                `safety_stock_by_warehouse` INT(11) NOT NULL , 
                PRIMARY KEY `Id_product_by_warehouse` (`id_erp_products`, `id_warehouse`)) 
                ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
        
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'erp_order_detail_by_warehouse` (
                `id_erp_order_detail` INT(11) NOT NULL ,
                `id_warehouse` INT(11) NOT NULL ,
                `quantity` INT(11) NOT NULL , 
                PRIMARY KEY `Id_order_detail_by_warehouse` (`id_erp_order_detail`, `id_warehouse`)) 
                ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
        
        foreach (Language::getLanguages() as $language) {
            if ($language['id_lang'] == $id_lang_fr) {
                    $admin_erp_stock_transfert[$language['id_lang']] = 'ERP - Transfert de stocks';
                } else {
                    $admin_erp_stock_transfert[$language['id_lang']] = 'ERP Stock transfert';
                }
        }
        
        if (!Tab::getIdFromClassName('AdminErpStockTransfert')) {
            $object->installModuleTab('AdminErpStockTransfert',
                                $admin_erp_stock_transfert,
                                Configuration::get('WIC_ERP_PARENT_TAB'),
                                'logo.gif');
        }
    } catch (Exception $e) {
        $e->getMessage();
    }

    Configuration::updateValue('WIC_ERP_VERSION', $upgrade_version);
    return true;
}
