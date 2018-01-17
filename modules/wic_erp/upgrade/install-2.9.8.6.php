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
function upgrade_module_2_9_8_6($object)
{
    $upgrade_version = '2.9.8.6';

    try {
        Db::getInstance()->execute('ALTER TABLE
                                    `'._DB_PREFIX_.'erp_order`
                                    DROP `tax_rate`');
    } catch (Exception $e) {
        $e->getMessage();
    }

    Configuration::updateValue('WIC_ERP_VERSION', $upgrade_version);
    return true;
}
