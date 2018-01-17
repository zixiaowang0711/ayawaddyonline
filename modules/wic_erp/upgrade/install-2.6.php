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
function upgrade_module_2_6($object)
{
    $upgrade_version = '2.6';
    
    $id_lang_fr = Language::getIdByIso('fr');
    $admin_erp_import = array();
    
    try {
        Db::getInstance()->execute('ALTER TABLE
										`'._DB_PREFIX_.'erp_products`
									ADD `bundling` INT(11) NULL DEFAULT \'0\' AFTER `unit_order`');
        
        foreach (Language::getLanguages() as $language) {
            if ($language['id_lang'] == $id_lang_fr) {
                $admin_erp_import[$language['id_lang']] = 'ERP - Import cmdes fournisseurs';
            } else {
                $admin_erp_import[$language['id_lang']] = 'ERP Import order suppliers';
            }
        }
        
        if (!Tab::getIdFromClassName('AdminErpImport')) {
            $object->installModuleTab('AdminErpImport',
                                $admin_erp_import,
                                Configuration::get('WIC_ERP_PARENT_TAB'),
                                'logo.gif');
        }
    } catch (Exception $e) {
        $e->getMessage();
    }

    Configuration::updateValue('WIC_ERP_VERSION', $upgrade_version);
    return true;
}
