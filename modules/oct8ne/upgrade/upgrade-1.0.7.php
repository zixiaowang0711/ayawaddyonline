<?php
/**
 * Prestashop module for Oct8ne
 *
 * @category  Prestashop
 * @category  Module
 * @author    Prestaquality.com
 * @copyright 2016 Prestaquality
 * @license   Commercial license see license.txt
 * Support by mail  : info@prestaquality.com
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_0_7($module)
{
    $module->registerHook('displayHeader');
    Configuration::updateValue(Oct8ne::$POSITION_LOAD, 2);

    return true;
}
