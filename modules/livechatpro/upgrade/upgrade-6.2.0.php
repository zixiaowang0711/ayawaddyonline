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
function upgrade_module_6_2_0($module)
{
	$result = true;

	if (!$module->active)
		$result = false;

	#$module->uninstall();
	#$module->install();

	try {
	
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name.'_settings` DROP `name_field`;')) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name.'_settings` DROP `email_field`;')) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name.'_settings` DROP `phone_field`;')) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name.'_settings` DROP `department_field`;')) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name.'_settings` DROP `question_field`;')) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name.'_settings` DROP `fields_are_not_mandatory`;')) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `name_field_online` ENUM('Y','N') NULL DEFAULT NULL AFTER `new_message_sound`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `name_field_online_mandatory` ENUM('Y','N') NULL DEFAULT NULL AFTER `name_field_online`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `name_field_offline` ENUM('Y','N') NULL DEFAULT NULL AFTER `name_field_online_mandatory`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `name_field_offline_mandatory` ENUM('Y','N') NULL DEFAULT NULL AFTER `name_field_offline`;")) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `email_field_online` ENUM('Y','N') NULL DEFAULT NULL AFTER `name_field_offline_mandatory`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `email_field_online_mandatory` ENUM('Y','N') NULL DEFAULT NULL AFTER `email_field_online`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `email_field_offline` ENUM('Y','N') NULL DEFAULT NULL AFTER `email_field_online_mandatory`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `email_field_offline_mandatory` ENUM('Y','N') NULL DEFAULT NULL AFTER `email_field_offline`;")) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `phone_field_online` ENUM('Y','N') NULL DEFAULT NULL AFTER `email_field_offline_mandatory`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `phone_field_online_mandatory` ENUM('Y','N') NULL DEFAULT NULL AFTER `phone_field_online`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `phone_field_offline` ENUM('Y','N') NULL DEFAULT NULL AFTER `phone_field_online_mandatory`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `phone_field_offline_mandatory` ENUM('Y','N') NULL DEFAULT NULL AFTER `phone_field_offline`;")) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `department_field_online` ENUM('Y','N') NULL DEFAULT NULL AFTER `phone_field_offline_mandatory`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `department_field_online_mandatory` ENUM('Y','N') NULL DEFAULT NULL AFTER `department_field_online`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `department_field_offline` ENUM('Y','N') NULL DEFAULT NULL AFTER `department_field_online_mandatory`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `department_field_offline_mandatory` ENUM('Y','N') NULL DEFAULT NULL AFTER `department_field_offline`;")) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `question_field_online` ENUM('Y','N') NULL DEFAULT NULL AFTER `department_field_offline_mandatory`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `question_field_online_mandatory` ENUM('Y','N') NULL DEFAULT NULL AFTER `question_field_online`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `question_field_offline` ENUM('Y','N') NULL DEFAULT NULL AFTER `question_field_online_mandatory`;")) $result = false;
		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `question_field_offline_mandatory` ENUM('Y','N') NULL DEFAULT NULL AFTER `question_field_offline`;")) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `popup_alert_on_income_chats` ENUM('Y','N') NULL DEFAULT NULL AFTER `show_names`;")) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name.'_settings` ADD `start_new_chat_after` TINYTEXT NULL AFTER `popup_alert_on_income_chats`;')) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name."_settings` ADD `new_chat_rings_to` ENUM('most-available','all') NULL DEFAULT NULL AFTER `staff_qualification`;")) $result = false;


		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name.'_themes` ADD `chat_bubble_staff_background` TINYTEXT NULL AFTER `chat_box_foreground`;')) $result = false;

		if (!@Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.$module->name.'_themes` ADD `chat_bubble_client_background` TINYTEXT NULL AFTER `chat_bubble_staff_background`;')) $result = false;

		// ++++++++++++++++++++++++++++++++++++++++++++++

		if (!@Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.$module->name.'_settings` 
			SET 
				name_field_online = "Y",
				name_field_online_mandatory = "Y",
				name_field_offline = "Y",
				name_field_offline_mandatory = "Y",
				email_field_online = "Y",
				email_field_online_mandatory = "Y",
				email_field_offline = "Y",
				email_field_offline_mandatory = "Y",
				phone_field_online = "N",
				phone_field_online_mandatory = "N",
				phone_field_offline = "N",
				phone_field_offline_mandatory = "N",
				department_field_online = "Y",
				department_field_online_mandatory = "Y",
				department_field_offline = "Y",
				department_field_offline_mandatory = "Y",
				question_field_online = "Y",
				question_field_online_mandatory = "Y",
				question_field_offline = "Y",
				question_field_offline_mandatory = "Y",
				popup_alert_on_income_chats = "N",
				start_new_chat_after = "0",
				new_chat_rings_to = "most-available"
			')) $result = false;

		if (!@Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.$module->name.'_themes` 
			SET 
				chat_bubble_staff_background = "CCCBD1",
				chat_bubble_client_background = "E0E3E7"
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