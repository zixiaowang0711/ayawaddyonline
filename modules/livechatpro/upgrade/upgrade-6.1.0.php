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
function upgrade_module_6_1_0($module)
{
	$result = true;

	if (!$module->active)
		$result = false;

	#$module->uninstall();
	#$module->install();

	try {
	
		if (!@Db::getInstance()->Execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$module->name."_tickets` (
	`id_ticket` INT(11) NOT NULL AUTO_INCREMENT,
	`id_employee` INT(11) NOT NULL DEFAULT '0',
	`id_staffprofile` INT(11) NULL DEFAULT NULL,
	`id_customer` INT(11) NULL DEFAULT NULL,
	`id_department` INT(11) NULL DEFAULT NULL,
	`subject` TEXT NULL,
	`priority` ENUM('Low','Medium','High') NULL DEFAULT NULL,
	`status` ENUM('Open','Answered','Customer-Reply','In-Progress','Closed') NULL DEFAULT NULL,
	`date_add` DATETIME NULL DEFAULT NULL,
	`last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id_ticket`),
	INDEX `id_staffprofile` (`id_staffprofile`),
	INDEX `id_customer` (`id_customer`),
	INDEX `id_department` (`id_department`),
	INDEX `id_employee` (`id_employee`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=7
;")) $result = false;


		if (!@Db::getInstance()->Execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$module->name."_ticketsreplyes` (
	`id_reply` INT(11) NOT NULL AUTO_INCREMENT,
	`id_ticket` INT(11) NULL DEFAULT NULL,
	`id_staffprofile` INT(11) NULL DEFAULT NULL,
	`id_customer` INT(11) NULL DEFAULT NULL,
	`reply_from` ENUM('Customer','Staff') NULL DEFAULT NULL,
	`message` LONGTEXT NULL,
	`date_add` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id_reply`),
	INDEX `id_ticket` (`id_ticket`),
	INDEX `id_customer` (`id_customer`),
	INDEX `id_staffprofile` (`id_staffprofile`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=40
;")) $result = false;

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