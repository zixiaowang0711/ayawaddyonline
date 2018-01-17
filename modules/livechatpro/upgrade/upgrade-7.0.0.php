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
function upgrade_module_7_0_0($module)
{
	$result = true;

	if (!$module->active)
		$result = false;

	#$module->uninstall();
	#$module->install();

	try {
	
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name.'_settings` DROP `position_options`;')) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name.'_settings` DROP `hook`;')) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `show_avatars` ENUM('Y','N') NULL DEFAULT NULL AFTER `show_names`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `visitors_can_upload_files` ENUM('Y','N') NULL DEFAULT NULL AFTER `code`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `chat_type_admin` ENUM('Slide','Popup') NULL DEFAULT NULL AFTER `chat_type`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `slide_with_image` ENUM('Y','N') NULL DEFAULT NULL AFTER `chat_type_admin`;")) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` CHANGE `position` `fixed_position` ENUM('Y','N') NULL DEFAULT NULL;")) $result = false;

		// ++++++++++++++++++++++++++++++++++++++++++++++

		if (!@Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.$module->name.'_settings` 
			SET 
				show_avatars = "N",
				visitors_can_upload_files = "N",
				slide_with_image = "N",
				fixed_position = "Y"
			')) $result = false;

	} 
	catch (Exception $e) 
	{
		$result = false;
	}

	/* clear cache */
	if (version_compare(_PS_VERSION_, '1.5.5.0', '>='))
		Tools::clearSmartyCache();

	return $result;
}