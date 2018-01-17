<?php
/**
* 2013 - 2017 HiPresta
*
* MODULE Facebook Connect
*
* @version   1.1.0
* @author    HiPresta <suren.mikaelyan@gmail.com>
* @link      http://www.hipresta.com
* @copyright HiPresta 2017
* @license   PrestaShop Addons license limitation
*
*/

function upgrade_module_1_1_0($module)
{
    Configuration::updateValue('HI_SC_FB_CLEAN_DB', false);
    $module->createTabs('AdminHiScFacebook', 'AdminHiScFacebook', 'CONTROLLER_TABS_HI_SC_FB', 0);
    $module->proceedDb();
    $module->removeDirAndFile(_PS_MODULE_DIR_.$module->name.'/views/templates/admin/admin1.6.tpl');
    $module->removeDirAndFile(_PS_MODULE_DIR_.$module->name.'/views/templates/hook/hooknav.tpl');
    $module->removeDirAndFile(_PS_MODULE_DIR_.$module->name.'/views/templates/hook/sidebar.tpl');
    $module->removeDirAndFile(_PS_MODULE_DIR_.$module->name.'/views/templates/hook/facebookCustomHook.tpl');
    $module->removeDirAndFile(_PS_MODULE_DIR_.$module->name.'/ajax');
    $module->removeDirAndFile(_PS_MODULE_DIR_.$module->name.'/css');
    $module->removeDirAndFile(_PS_MODULE_DIR_.$module->name.'/images');
    $module->deleteTableColumn('hifacebookusers', 'birthday');
    Configuration::updateValue('HI_SC_FB_SDK', true)

     /*Ps 1.7*/
    $module->registerHook('displayNav2');
    return true;
}
