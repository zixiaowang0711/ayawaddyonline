<?php
/**
* ProQuality (c) All rights reserved.
*
* DISCLAIMER
*
* Do not edit, modify or copy this file.
* If you wish to customize it, contact us at addons4prestashop@gmail.com.
*
* @author    Andrei Cimpean (ProQuality) <addons4prestashop@gmail.com>
* @copyright 2015-2016 ProQuality
* @license   Do not edit, modify or copy this file
*/

if (!defined('_PS_VERSION_'))
	exit;

/**
 * Function used to update your module from previous versions to the version 5.0.0,
 * Don't forget to create one file per version.
 */
function upgrade_module_6_0_0($module)
{
	if (!$module->active)
		return false;

	$module->uninstall();
	$module->install();

	return true;
}