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

if (Tools::substr(_PS_VERSION_, 0, 3) == '1.7')
{
	$defines = include dirname(__FILE__).'/../../app/config/parameters.php';
	@define('_DB_PREFIX_', $defines['parameters']['database_prefix']);
}

require_once dirname(__FILE__).'/classes/Lcp.php';

class LiveChatPro extends Module
{
	public $ps_version;
	public $context;
	public $hook1;
	public $hook2;
	public $hook3;
	public $module_token;
	public $ajax_token;
	public $employees_token;
	public $id_product;
	public $lcp;
	const LCP_TOKEN = 'livechatpro';

	public function dev()
	{

	}

	public function __construct()
	{
		$this->name                   = 'livechatpro';
		$this->tab                    = 'front_office_features';
		$this->version                = '7.3.1';
		$this->author                 = 'ProQuality';
		$this->module_key             = '6391aed91fe8d0f835635bad4919563e';
		$this->need_instance          = 0;
		$this->bootstrap              = true;
		$this->ps_versions_compliancy = array(
			'min' => '1.5',
			'max' => '1.7'
		);

		parent::__construct();

		$this->id_product = '18967';
		$this->displayName = $this->l('Live Chat Pro');
		$this->description = $this->l('Professional live chat with visitor tracking, browser tracking, geolocation.');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
		$this->ps_version = (Tools::substr(_PS_VERSION_, 0, 3));
		$this->hook1 = 'displayHeader';
		$this->hook2 = 'displayFooter';
		$this->hook3 = 'displayBackOfficeTop';
		$this->context->employee = new Employee($this->context->cookie->id_employee);
		$this->ajax_token = $this->getToken();
		$this->module_token = Tools::getAdminToken( 'AdminModules'.(int)Tab::getIdFromClassName('AdminModules').(int)$this->context->cookie->id_employee);
		$this->employees_token = Tools::getAdminToken( 'AdminEmployees'.(int)Tab::getIdFromClassName('AdminEmployees').(int)$this->context->cookie->id_employee);
		#$this->context = Context::getContext();
		#d($this->context);

		if (!empty($this->context->cookie->id_employee))
		{
			$employee_iso = Language::getIsoById($this->context->employee->id_lang);
			$_SESSION['lcp'] = array(
				'languages' => Language::getLanguages(false),
				'id_employee' => empty($this->context->cookie->id_employee) ? 0 : $this->context->cookie->id_employee,
				'employee_id_lang' => empty($this->context->employee->id_lang) ? 1 : $this->context->employee->id_lang,
				'employee_iso_code' => empty($employee_iso) ? Language::getIsoById(1) : Language::getIsoById($this->context->employee->id_lang),
				'visitor_iso_code' => empty($this->context->language->iso_code) ? Language::getIsoById(1) : $this->context->language->iso_code,
				'module_token' => $this->module_token,
				'employees_token' => $this->employees_token,
				'shop_domain' => Tools::getShopDomain(),
				'_path' => $this->_path,
				'module_version' => $this->version,
				'ps_version' => $this->ps_version,
				'db_prefix' => _DB_PREFIX_,
				'id_customer' => empty($this->context->cookie->id_customer) ? 0 : $this->context->cookie->id_customer,
				'id_product' => $this->id_product,
			);
		}
		else
		{
			#die(empty($this->context->language->iso_code) ? Language::getIsoById(1) : $this->context->language->iso_code);
			$_SESSION['lcp'] = array(
				'languages' => Language::getLanguages(false),
				'id_employee' => 0,
				'employee_id_lang' => 1,
				'employee_iso_code' => 'en',
				'visitor_iso_code' => empty($this->context->language->iso_code) ? Language::getIsoById(1) : $this->context->language->iso_code,
				'module_token' => $this->module_token,
				'employees_token' => '',
				'shop_domain' => Tools::getShopDomain(),
				'_path' => $this->_path,
				'module_version' => $this->version,
				'ps_version' => $this->ps_version,
				'db_prefix' => _DB_PREFIX_,
				'id_customer' => empty($this->context->cookie->id_customer) ? 0 : $this->context->cookie->id_customer,
				'id_product' => $this->id_product,
			);
		}


		#d($_SESSION['lcp']);
		try 
		{ 
			$this->context->smarty->registerPlugin('function', 'lang', 'smartyFunctionTranslate');
		} 
		catch (Exception $e)  
		{ 
			unset($e); 
		}

		$this->lcp = Lcp::i();

		$this->dev();
	}


	public function install()
	{
		#include(dirname(__FILE__).'/sql/install.php');
		
	$sql_array = '';
	$sql_execution = '';
	# creating table livechatpro_archive
	$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_archive` (
	`id_archive` INT(11) NOT NULL AUTO_INCREMENT,
	`id_chat` INT(11) NULL DEFAULT NULL,
	`id_visitor` TINYTEXT NULL,
	`id_staffprofile` INT(11) NULL DEFAULT NULL,
	`id_department` INT(11) NULL DEFAULT NULL,
	`action` TINYTEXT NULL,
	`internal` TINYTEXT NULL,
	`date_add` DATETIME NULL DEFAULT NULL,
	`in_chat` ENUM('Y','N','P') NULL DEFAULT NULL,
	`chat_request_from` ENUM('Staff','Client') NULL DEFAULT NULL,
	`awaiting_response_from_staff` ENUM('Y','N') NULL DEFAULT NULL,
	`visitor_typing` ENUM('Y','N') NULL DEFAULT NULL,
	`staff_typing` ENUM('Y','N') NULL DEFAULT NULL,
	`name` TINYTEXT NULL,
	`email` TINYTEXT NULL,
	`phone` TINYTEXT NULL,
	`company` TINYTEXT NULL,
	`language` TINYTEXT NULL,
	`country` TINYTEXT NULL,
	`ip` TINYTEXT NULL,
	`host` TINYTEXT NULL,
	`duration` TIME NULL DEFAULT NULL,
	`messages` LONGTEXT NULL,
	`last_message` LONGTEXT NULL,
	`log_entries` INT(11) NULL DEFAULT NULL,
	`is_archive` ENUM('Y','N') NULL DEFAULT NULL,
	`last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id_archive`),
	INDEX `id_chat` (`id_chat`),
	INDEX `id_staffprofile` (`id_staffprofile`),
	INDEX `id_department` (`id_department`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=329
;";

		# creating table livechatpro_departments
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_departments` (
	`id_department` INT(11) NOT NULL AUTO_INCREMENT,
	`status` ENUM('Active','Inactive') NULL DEFAULT NULL,
	`name` TINYTEXT NULL,
	PRIMARY KEY (`id_department`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=4
;";

		# creating table livechatpro_emoticons
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_emoticons` (
	`id_emoticon` INT(11) NOT NULL AUTO_INCREMENT,
	`code` TINYTEXT NULL,
	`filename` TINYTEXT NULL,
	`alias` ENUM('Y','N') NULL DEFAULT 'N',
	PRIMARY KEY (`id_emoticon`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=65
;";

		# creating table livechatpro_iconsets
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_iconsets` (
	`id_iconset` INT(11) NOT NULL AUTO_INCREMENT,
	`name` TINYTEXT NULL,
	`is_default` INT(1) NULL DEFAULT '0',
	PRIMARY KEY (`id_iconset`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=3
;";

		# creating table livechatpro_iconsets_lang
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_iconsets_lang` (
	`id_iconset` INT(11) NULL DEFAULT NULL,
	`id_lang` INT(11) NULL DEFAULT NULL,
	`iso_code` TINYTEXT NULL,
	`offline_img` TEXT NULL,
	`online_img` TEXT NULL,
	INDEX `id_iconset` (`id_iconset`),
	INDEX `id_lang` (`id_lang`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;";

		# creating table livechatpro_logs
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_logs` (
	`id_log` INT(11) NOT NULL AUTO_INCREMENT,
	`id_staffprofile` INT(11) NOT NULL DEFAULT '0',
	`id_visitor` TINYTEXT NULL,
	`message` TEXT NULL,
	`date_add` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id_log`),
	INDEX `id_staffprofile` (`id_staffprofile`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=360
;";

		# creating table livechatpro_messages
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_messages` (
	`id_message` INT(11) NOT NULL AUTO_INCREMENT,
	`id_visitor` TINYTEXT NOT NULL,
	`id_department` INT(11) NULL DEFAULT NULL,
	`date_add` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`status` ENUM('Read','Unread') NULL DEFAULT NULL,
	`name` TINYTEXT NULL,
	`email` TINYTEXT NULL,
	`phone` TINYTEXT NULL,
	`department` TINYTEXT NULL,
	`question` MEDIUMTEXT NULL,
	`ip` TINYTEXT NULL,
	PRIMARY KEY (`id_message`),
	INDEX `id_department` (`id_department`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=21
;";

		# creating table livechatpro_mousetracking
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_mousetracking` (
	`id_mousetracking` INT(11) NOT NULL AUTO_INCREMENT,
	`id_visitor` TINYTEXT NULL,
	`tracking_data` LONGTEXT NOT NULL,
	`last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id_mousetracking`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;";

		# creating table livechatpro_onlineusers
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_onlineusers` (
	`id_user` INT(11) NOT NULL AUTO_INCREMENT,
	`id_staffprofile` INT(11) NOT NULL,
	`id_visitor` TINYTEXT NULL,
	`type` ENUM('Internal','External') NULL DEFAULT NULL,
	`last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id_user`),
	INDEX `id_staffprofile` (`id_staffprofile`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=25
;";

		# creating table livechatpro_onlinevisitors
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_onlinevisitors` (
	`id_visitor` INT(11) NOT NULL AUTO_INCREMENT,
	`session_id` TINYTEXT NULL,
	`country` TINYTEXT NULL,
	`city` TINYTEXT NULL,
	`province` TINYTEXT NULL,
	`language` TINYTEXT NULL,
	`visits` TINYTEXT NULL,
	`current_page` TINYTEXT NULL,
	`host` TINYTEXT NULL,
	`ip` TINYTEXT NULL,
	`browser` TINYTEXT NULL,
	`timezone` TINYTEXT NULL,
	`resolution` TINYTEXT NULL,
	`online_time` TINYTEXT NULL,
	`referrer` TINYTEXT NULL,
	`page_count` TINYTEXT NULL,
	`os` TINYTEXT NULL,
	`last_visit` DATETIME NULL DEFAULT NULL,
	`last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id_visitor`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=18
;";


		# creating table livechatpro_onlinevisitors
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_predefinedmessages` (
	`id_predefinedmessage` INT(11) NOT NULL AUTO_INCREMENT,
	`id_lang` INT(11) NULL DEFAULT NULL,
	`iso_code` TINYTEXT NULL,
	`title` TEXT NULL,
	`message` LONGTEXT NULL,
	`last_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id_predefinedmessage`),
	INDEX `id_lang` (`id_lang`)
)
ENGINE=InnoDB
AUTO_INCREMENT=14
;";

		# creating table livechatpro_ratings
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_ratings` (
	`id_rating` INT(11) NOT NULL AUTO_INCREMENT,
	`id_archive` INT(11) NULL DEFAULT NULL,
	`id_staffprofile` INT(11) NULL DEFAULT NULL,
	`id_visitor` TINYTEXT NULL,
	`status` ENUM('Read','Unread') NULL DEFAULT NULL,
	`internal` TINYTEXT NULL,
	`politness` DECIMAL(10,1) NULL DEFAULT NULL,
	`qualification` DECIMAL(10,1) NULL DEFAULT NULL,
	`date_add` DATETIME NULL DEFAULT NULL,
	`name` TINYTEXT NULL,
	`email` TINYTEXT NULL,
	`company` TINYTEXT NULL,
	`comment` MEDIUMTEXT NULL,
	PRIMARY KEY (`id_rating`),
	INDEX `id_staffprofile` (`id_staffprofile`),
	INDEX `id_archive` (`id_archive`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=13
;";

		# creating table livechatpro_settings
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_settings` (
	`id_setting` INT(11) NOT NULL AUTO_INCREMENT,
	`id_iconset` INT(11) NULL DEFAULT NULL,
	`id_theme` INT(11) NULL DEFAULT NULL,
	`name` TINYTEXT NULL,
	`host_type` ENUM('Self','Remote') NULL DEFAULT NULL,
	`offline_messages_go_to` TINYTEXT NULL,
	`new_chat_sound` TINYTEXT NULL,
	`new_message_sound` TINYTEXT NULL,
	`name_field_online` ENUM('Y','N') NULL DEFAULT NULL,
	`name_field_online_mandatory` ENUM('Y','N') NULL DEFAULT NULL,
	`name_field_offline` ENUM('Y','N') NULL DEFAULT NULL,
	`name_field_offline_mandatory` ENUM('Y','N') NULL DEFAULT NULL,
	`email_field_online` ENUM('Y','N') NULL DEFAULT NULL,
	`email_field_online_mandatory` ENUM('Y','N') NULL DEFAULT NULL,
	`email_field_offline` ENUM('Y','N') NULL DEFAULT NULL,
	`email_field_offline_mandatory` ENUM('Y','N') NULL DEFAULT NULL,
	`phone_field_online` ENUM('Y','N') NULL DEFAULT NULL,
	`phone_field_online_mandatory` ENUM('Y','N') NULL DEFAULT NULL,
	`phone_field_offline` ENUM('Y','N') NULL DEFAULT NULL,
	`phone_field_offline_mandatory` ENUM('Y','N') NULL DEFAULT NULL,
	`department_field_online` ENUM('Y','N') NULL DEFAULT NULL,
	`department_field_online_mandatory` ENUM('Y','N') NULL DEFAULT NULL,
	`department_field_offline` ENUM('Y','N') NULL DEFAULT NULL,
	`department_field_offline_mandatory` ENUM('Y','N') NULL DEFAULT NULL,
	`question_field_online` ENUM('Y','N') NULL DEFAULT NULL,
	`question_field_online_mandatory` ENUM('Y','N') NULL DEFAULT NULL,
	`question_field_offline` ENUM('Y','N') NULL DEFAULT NULL,
	`question_field_offline_mandatory` ENUM('Y','N') NULL DEFAULT NULL,
	`chat_type` ENUM('Slide','Popup') NULL DEFAULT NULL,
	`chat_type_admin` ENUM('Slide','Popup') NULL DEFAULT NULL,
	`slide_with_image` ENUM('Y','N') NULL DEFAULT NULL,
	`orientation` TINYTEXT NULL,
	`offset` TINYTEXT NULL,
	`start_minimized` ENUM('Y','N') NULL DEFAULT NULL,
	`hide_when_offline` ENUM('Y','N') NULL DEFAULT NULL,
	`show_names` ENUM('Y','N') NULL DEFAULT NULL,
	`show_avatars` ENUM('Y','N') NULL DEFAULT NULL,
	`popup_alert_on_income_chats` ENUM('Y','N') NULL DEFAULT NULL,
	`start_new_chat_after` TINYTEXT NULL,
	`staff_qualification` ENUM('Y','N') NULL DEFAULT NULL,
	`new_chat_rings_to` ENUM('most-available','all') NULL DEFAULT NULL,
	`fixed_position` ENUM('Y','N') NULL DEFAULT NULL,
	`code` TEXT NULL,
	`visitors_can_upload_files` ENUM('Y','N') NULL DEFAULT NULL,
	`sync_chat_interval_backend` INT(11) NULL DEFAULT NULL,
	`sync_chat_interval_frontend` INT(11) NULL DEFAULT NULL,
	`realm_id` TINYTEXT NULL,
	`realm_key` TINYTEXT NULL,
	`is_default` INT(1) NULL DEFAULT '0',
	`is_primary` INT(1) NULL DEFAULT '0',
	PRIMARY KEY (`id_setting`),
	INDEX `id_icons` (`id_iconset`),
	INDEX `id_skin` (`id_theme`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=3
;";

		# creating table livechatpro_settings_lang
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_settings_lang` (
	`id_setting` INT(11) NULL DEFAULT NULL,
	`id_lang` INT(11) NULL DEFAULT NULL,
	`iso_code` TINYTEXT NULL,
	`offline_header_message` LONGTEXT NULL,
	`online_header_message` LONGTEXT NULL,
	`offline_welcome_message` LONGTEXT NULL,
	`online_welcome_message` LONGTEXT NULL,
	INDEX `id_setting` (`id_setting`),
	INDEX `id_lang` (`id_lang`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;";


		# creating table livechatpro_staffprofiles
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_staffprofiles` (
	`id_staffprofile` INT(11) NOT NULL AUTO_INCREMENT,
	`id_employee` INT(11) NOT NULL,
	`avatar` TINYTEXT NOT NULL,
	`departments` TINYTEXT NOT NULL,
	`welcome_message` TEXT NULL,
	`signature` TEXT NULL,
	`is_active` ENUM('Y','N') NULL DEFAULT NULL,
	PRIMARY KEY (`id_staffprofile`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=5
;";

		# creating table livechatpro_themes
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_themes` (
	`id_theme` INT(11) NOT NULL AUTO_INCREMENT,
	`name` TINYTEXT NULL,
	`width` TINYTEXT NULL,
	`height` TINYTEXT NULL,
	`corners_radius` TINYTEXT NULL,
	`chat_box_background` TINYTEXT NULL,
	`chat_box_foreground` TINYTEXT NULL,
	`chat_bubble_staff_background` TINYTEXT NULL,
	`chat_bubble_client_background` TINYTEXT NULL,
	`chat_box_border` ENUM('Y','N') NULL DEFAULT NULL,
	`chat_box_border_color` TINYTEXT NULL,
	`header_offline_background` TINYTEXT NULL,
	`header_online_background` TINYTEXT NULL,
	`header_offline_foreground` TINYTEXT NULL,
	`header_online_foreground` TINYTEXT NULL,
	`submit_button_background` TINYTEXT NULL,
	`submit_button_foreground` TINYTEXT NULL,
	`is_default` INT(1) NULL DEFAULT '0',
	PRIMARY KEY (`id_theme`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=3
;";

	# creating table livechatpro_themes
	$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_tickets` (
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
;";

	# creating table livechatpro_themes
	$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_ticketsreplyes` (
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
;";


		# creating table livechatpro_visitedpages
		$sql_array[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->name."_visitedpages` (
	`id_visitedpage` INT(11) NOT NULL AUTO_INCREMENT,
	`id_visitor` TINYTEXT NULL,
	`date_add` DATETIME NOT NULL,
	`duration` TINYTEXT NOT NULL,
	`url` TEXT NOT NULL,
	`referrer` TEXT NOT NULL,
	`last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id_visitedpage`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=27
;";

		# inserting departments
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_departments` (`id_department`, `status`, `name`) VALUES (1, 'Active', '".$this->l('Support')."');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_departments` (`id_department`, `status`, `name`) VALUES (2, 'Inactive', '".$this->l('Sales')."');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_departments` (`id_department`, `status`, `name`) VALUES (3, 'Active', '".$this->l('Billing')."');";

		# inserting emoticons
		$sql_array[]  = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (1, ':)', 'happy.gif', 'N');";
		$sql_array[]  = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (2, ':-)', 'happy.gif', 'Y');";
		$sql_array[]  = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (3, ':(', 'sad.gif', 'N');";
		$sql_array[]  = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (4, ':-(', 'sad.gif', 'Y');";
		$sql_array[]  = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (5, ';)', 'winking.gif', 'N');";
		$sql_array[]  = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (6, ':d', 'big-grin.gif', 'N');";
		$sql_array[]  = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (7, ';;)', 'batting-eyelashes.gif', 'N');";
		$sql_array[]  = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (8, '>:d<', 'big-hug.gif', 'N');";
		$sql_array[]  = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (9, ':-/', 'confused.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (10, ':x', 'love-struck.gif', 'N');";
		#$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (11, ':\">', 'blushing.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (12, ':p', 'tongue.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (13, ':*', 'kiss.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (14, '=((', 'broken-heart.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (15, ':-0', 'surprise.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (16, 'x(', 'angry.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (17, ':>', 'smug.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (18, 'b-)', 'cool.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (19, ':-s', 'worried.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (20, '#:-s', 'whew.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (21, '>:)', 'devil.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (22, ':((', 'crying.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (23, ':))', 'laughing.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (24, ':|', 'straight-face.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (25, '/:)', 'raised-eyebrows.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (26, '=))', 'rolling-on-the-floor.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (27, 'o:-)', 'angel.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (28, ':-b', 'nerd.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (29, '=;', 'talk-to-the-hand.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (30, ':-c', 'call-me.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (31, ':)]', 'on-the-phone.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (32, '~x(', 'at-wits-end.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (33, ':-h', 'wave.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (34, ':-t', 'time-out.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (35, '8->', 'day-dreaming.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (36, 'i-)', 'sleepy.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (37, '8-|', 'rolling-eyes.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (38, 'l-)', 'loser.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (39, ':-&', 'sick.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (40, ':-$', 'dont-tell-anyone.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (41, '[-(', 'no-talking.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (42, ':o)', 'clown.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (43, '8-}', 'silly.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (44, '<:-p', 'party.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (45, '(:|', 'yawn.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (46, '=p~', 'drooling.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (47, ':-?', 'thinking.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (48, '#-o', 'doh.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (49, '=d>', 'applause.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (50, ':-ss', 'nail-biting.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (51, '@-)', 'hypnotized.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (52, ':^o', 'liar.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (53, ':-w', 'waiting.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (54, ':-<', 'sigh.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (55, '>:p', 'phbbbbt.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (56, '<):)', 'cowboy.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (57, 'x_x', 'i-dont-want-to-see.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (58, ':!!', 'hurry-up.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (59, '\\m/', 'rock-on.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (60, ':-q', 'thumbs-down.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (61, ':-bd', 'thumbs-up.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (62, '^#(^', 'it-wasnt-me.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (63, ':ar!', 'pirate.gif', 'N');";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_emoticons` (`id_emoticon`, `code`, `filename`, `alias`) VALUES (64, ':-*', 'kiss.gif', 'Y');";

		$languages = Language::getLanguages(false);

		# inserting iconsets
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_iconsets` (`id_iconset`, `name`, `is_default`) VALUES (1, 'set1', 1);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_iconsets` (`id_iconset`, `name`, `is_default`) VALUES (2, 'mycustomiconset1', 0);";

		# inserting iconsets_lang
		foreach ($languages as $value)
		{
			$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name.'_iconsets_lang` (`id_iconset`, `id_lang`, `iso_code`, `offline_img`, `online_img`) VALUES (1, '.pSQL(Language::getIdByIso($value['iso_code'])).", '".pSQL($value['iso_code'])."', '556cb4ddaab46.png', '556cb4ddab31a.png');";
			$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name.'_iconsets_lang` (`id_iconset`, `id_lang`, `iso_code`, `offline_img`, `online_img`) VALUES (2, '.pSQL(Language::getIdByIso($value['iso_code'])).", '".pSQL($value['iso_code'])."', '55728fda162b5.png', '55728fda15646.png');";
		}

		# inserting settings
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_settings` (`id_setting`, `id_iconset`, `id_theme`, `name`, `host_type`, `offline_messages_go_to`, `new_chat_sound`, `new_message_sound`, `name_field_online`, `name_field_online_mandatory`, `name_field_offline`, `name_field_offline_mandatory`, `email_field_online`, `email_field_online_mandatory`, `email_field_offline`, `email_field_offline_mandatory`, `phone_field_online`, `phone_field_online_mandatory`, `phone_field_offline`, `phone_field_offline_mandatory`, `department_field_online`, `department_field_online_mandatory`, `department_field_offline`, `department_field_offline_mandatory`, `question_field_online`, `question_field_online_mandatory`, `question_field_offline`, `question_field_offline_mandatory`, `chat_type`, `chat_type_admin`, `slide_with_image`, `orientation`, `offset`, `start_minimized`, `hide_when_offline`, `show_names`, `popup_alert_on_income_chats`, `start_new_chat_after`, `staff_qualification`, `new_chat_rings_to`, `fixed_position`, `show_avatars`, `code`, `visitors_can_upload_files`, `sync_chat_interval_backend`, `sync_chat_interval_frontend`, `realm_id`, `realm_key`, `is_default`, `is_primary`) VALUES (1, 0, 1, 'default', 'Self', 'demo@demo.com', 'new-chat-default.mp3', 'new-message-default.mp3', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Slide', 'Popup', 'N', 'bottom-right', '50',  'Y', 'N', 'Y', 'N', '0', 'Y', 'most-available', 'Y', 'Y', NULL, 'Y', 5, 5, '', '', 1, 1);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_settings` (`id_setting`, `id_iconset`, `id_theme`, `name`, `host_type`, `offline_messages_go_to`, `new_chat_sound`, `new_message_sound`, `name_field_online`, `name_field_online_mandatory`, `name_field_offline`, `name_field_offline_mandatory`, `email_field_online`, `email_field_online_mandatory`, `email_field_offline`, `email_field_offline_mandatory`, `phone_field_online`, `phone_field_online_mandatory`, `phone_field_offline`, `phone_field_offline_mandatory`, `department_field_online`, `department_field_online_mandatory`, `department_field_offline`, `department_field_offline_mandatory`, `question_field_online`, `question_field_online_mandatory`, `question_field_offline`, `question_field_offline_mandatory`, `chat_type`, `chat_type_admin`, `slide_with_image`, `orientation`, `offset`, `start_minimized`, `hide_when_offline`, `show_names`, `popup_alert_on_income_chats`, `start_new_chat_after`, `staff_qualification`, `new_chat_rings_to`, `fixed_position`, `show_avatars`, `code`, `visitors_can_upload_files`, `sync_chat_interval_backend`, `sync_chat_interval_frontend`, `realm_id`, `realm_key`, `is_default`, `is_primary`) VALUES (2, 1, 2, 'defaultpopup', 'Self', 'demo@demo.com', 'new-chat-default.mp3', 'new-message-default.mp3', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Popup', 'Popup', 'N', 'left-top', '250', 'Y', 'N', 'Y', 'N', '0', 'Y', 'most-available', 'Y', 'Y', NULL, 'Y', 5, 5, '', '', 0, 0);";

		# inserting settings_lang
		foreach ($languages as $value)
		{
			$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name.'_settings_lang` (`id_setting`, `id_lang`, `iso_code`, `offline_header_message`, `online_header_message`, `offline_welcome_message`, `online_welcome_message`) VALUES (1, '.pSQL(Language::getIdByIso($value['iso_code'])).", '".pSQL($value['iso_code'])."', 'Contact us', 'Talk to us', 'We`re not around right now. But you can send us an email and we`ll get back to you, asap.', 'Questions? We`re here. Send us a message!');";
			$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name.'_settings_lang` (`id_setting`, `id_lang`, `iso_code`, `offline_header_message`, `online_header_message`, `offline_welcome_message`, `online_welcome_message`) VALUES (2, '.pSQL(Language::getIdByIso($value['iso_code'])).", '".pSQL($value['iso_code'])."', 'Contact us', 'Talk to us', 'We`re not around right now. But you can send us an email and we`ll get back to you, asap.', 'Questions? We`re here. Send us a message!');";
		}

		# inserting staffprofile
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_staffprofiles` (`id_staffprofile`, `id_employee`, `avatar`, `departments`, `welcome_message`, `signature`, `is_active`) VALUES (1, 1, 'male9-40x40.png', '1,2', '".$this->l('Hello!')."', '".$this->l('Good Bye!')."', 'Y');";

		# inserting themes
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_themes` (`id_theme`, `name`, `width`, `height`, `corners_radius`, `chat_box_background`, `chat_box_foreground`, `chat_bubble_staff_background`, `chat_bubble_client_background`, `chat_box_border`, `chat_box_border_color`, `header_offline_background`, `header_online_background`, `header_offline_foreground`, `header_online_foreground`, `submit_button_background`, `submit_button_foreground`, `is_default`) VALUES (1, 'default', '260', '350', '5', 'ffffff', '222222', 'cccbd1', 'e0e3e7', 'Y', 'cccccc', 'BF3723', '3A99D1', 'FFFFFF', 'FFFFFF', '3A99D1', 'FFFFFF', 1);";
		$sql_array[] = 'INSERT INTO `'._DB_PREFIX_.$this->name."_themes` (`id_theme`, `name`, `width`, `height`, `corners_radius`, `chat_box_background`, `chat_box_foreground`, `chat_bubble_staff_background`, `chat_bubble_client_background`, `chat_box_border`, `chat_box_border_color`, `header_offline_background`, `header_online_background`, `header_offline_foreground`, `header_online_foreground`, `submit_button_background`, `submit_button_foreground`, `is_default`) VALUES (2, 'defaultpopup', '260', '400', '5', 'ffffff', '222222', 'cccbd1', 'e0e3e7', 'Y', 'cccccc', 'BF3723', '3A99D1', 'FFFFFF', 'FFFFFF', '3A99D1', 'FFFFFF', 0);";

		
		foreach ($sql_array as $sql)
			$sql_execution[] = Db::getInstance()->Execute($sql);

		Configuration::updateValue(Tools::strtoupper($this->name).'_SETTINGS', serialize(array(
			'name' => $this->name,
			'ajax_token' => $this->ajax_token,
			'module_token' => $this->module_token,
			'employees_token' => $this->employees_token,
		)));

		$sql_execution = $sql_execution; // workaround for ps validator

		return parent::install()
				&& $this->registerHook($this->hook1)
				&& $this->registerHook($this->hook2)
				&& $this->registerHook($this->hook3)
				&& (!in_array(false, $sql_execution))
				&& $this->lcp->syncStaffProfiles() 
				&& $this->lcp->syncEmailTemplates();
	}

	public function uninstall()
	{
		#include(dirname(__FILE__).'/sql/uninstall.php');
		$sql_array = '';
		$sql_execution = '';

		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_archive`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_departments`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_emoticons`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_iconsets`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_iconsets_lang`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_logs`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_messages`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_mousetracking`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_onlineusers`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_onlinevisitors`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_predefinedmessages`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_ratings`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_settings`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_settings_lang`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_staffprofiles`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_themes`;';
		$sql_array[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.$this->name.'_visitedpages`;';

		foreach ($sql_array as $sql)
			$sql_execution[] = Db::getInstance()->Execute($sql);

		return parent::uninstall() && Configuration::deleteByName($this->name);
	}


	public function getContent()
	{
		$vars = $this->lcp->getModuleVars();
		$this->context->smarty->assign($vars);

		$this->context->smarty->assign(array(
			'settings'        => $this->lcp->getSettings(),
			'iconsets'        => $this->lcp->getIconsets(),
			'themes'          => $this->lcp->getThemes(),
			'departments'     => $this->lcp->getDepartments(),
			'languages'       => Language::getLanguages(false),
			'avatars'         => $this->lcp->getAvatars(),
			'visitor_details' => '',
		));

		return $this->display(dirname(__FILE__), '/views/templates/admin/index.tpl');
	}

	public static function getToken()
	{
		return Tools::encrypt(self::LCP_TOKEN);
	}

	public function arrayToObject($array)
	{
		$obj = new stdClass;

		foreach ($array as $k => $v) 
		{
			if (Tools::strlen($k))
			{
				if (is_array($v))
					$obj->{$k} = $this->arrayToObject($v);
				else
					$obj->{$k} = $v;
			}
		}
		return $obj;
	} 

	public function hookBackOfficeTop()
	{
		if ($this->lcp->checkModuleConflict() == false)
			return false;

		$this->context->controller->addJquery();
		$this->context->controller->addJqueryUI('ui.tabs');
		$this->context->controller->addJqueryUI('ui.dialog');

		$vars = $this->lcp->getModuleVars();
		$this->context->smarty->assign($vars);

		$assigned_js_vars = '<script>
		var lcp_init = "back",
			lcp_session = JSON.parse(JSON.stringify('.Tools::jsonEncode($this->arrayToObject(${'_SESSION'}['lcp'])).')),
			lcp_module_name = "'.$vars['module_name'].'",
			lcp_db_prefix = "'.$vars['db_prefix'].'",
			lcp_id_staffprofile = "'.$vars['id_staffprofile'].'",
			lcp_employee_is_superadmin = "'.$vars['employee_is_superadmin'].'",
			lcp_employee_iso = "'.$vars['employee_iso'].'",
			lcp_internal = "'.@iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $vars['internal']).'",
			lcp_path = "'.$vars['module_path'].'",
			lcp_url = "'.$vars['module_url'].'",
			lcp_grid_path = "'.$vars['module_url'].'ajax.php?token='.$vars['ajax_token'].'&type=",
			lcp_token = "'.$vars['ajax_token'].'",
			lcp_employees_token = "'.$vars['employees_token'].'",
			lcp_dev_modules_link = "'.$vars['dev_modules_link'].'",
			lcp_support_link = "'.$vars['support_link'].'",
			lcp_doc_link = "'.$vars['doc_link'].'",
			lcp_video_link = "'.$vars['video_link'].'",
			lcp_ps_version = "'.$vars['ps_version'].'",
			lcp_staff_avatar = "'.$vars['staff_avatar'].'",
			lcp_avatars = '.(empty($vars['avatars_json']) ? '""' : 'JSON.parse(JSON.stringify('.$vars['avatars_json'].'))').',
			lcp_departments = '.(empty($vars['departments_json']) ? '""' : 'JSON.parse(JSON.stringify('.$vars['departments_json'].'))').',
			lcp_primary_settings = '.(empty($vars['primary_settings_json']) ? '""' : 'JSON.parse(JSON.stringify('.$vars['primary_settings_json'].'))').',
			lcp_emoticons = '.(empty($vars['emoticons_json']) ? '""' : 'JSON.parse(JSON.stringify('.$vars['emoticons_json'].'))').';';

		$assigned_js_vars .= $this->context->smarty->fetch(dirname(__FILE__).'/views/templates/hook/translations.js.tpl');
		$assigned_js_vars .= '</script>';

		//return $assigned_js_vars.'<script src="'.$this->_path.'views/js/init.js?type=back" type="text/javascript"></script>';
		return $assigned_js_vars.'<script src="'.$this->_path.'views/js/lcp.js" type="text/javascript"></script><link rel="stylesheet" type="text/css" href="'.$this->_path.'views/css/lcp.css">';
	}



	public function hookHeader()
	{
		$this->context->controller->addJquery();
		$this->context->controller->addJqueryUI('ui.dialog');

		$vars = $this->lcp->getModuleVars();
		$this->context->smarty->assign($vars);

		$assigned_js_vars = '<script>
		var lcp_init = "front",
			lcp_session = JSON.parse(JSON.stringify('.Tools::jsonEncode($this->arrayToObject(${'_SESSION'}['lcp'])).')),
			lcp_module_name = "'.$vars['module_name'].'",
			lcp_db_prefix = "'.$vars['db_prefix'].'",
			lcp_path = "'.$vars['module_path'].'",
			lcp_url = "'.$vars['module_url'].'",
			lcp_current_url = "'.$vars['lcp_current_url'].'",
			lcp_token = "'.$vars['ajax_token'].'",
			lcp_grid_path = "'.$vars['module_url'].'ajax.php?token='.$vars['ajax_token'].'&type=",
			lcp_id_customer = "'.$vars['id_customer'].'",
			lcp_emoticons = JSON.parse(JSON.stringify('.$vars['emoticons_json'].')),
			lcp_primary_settings = JSON.parse(JSON.stringify('.$vars['primary_settings_json'].'));';

		$assigned_js_vars .= $this->context->smarty->fetch(dirname(__FILE__).'/views/templates/hook/translations.js.tpl');
		$assigned_js_vars .= '</script>';

		if ($this->ps_version == '1.7')
		{
			$this->context->controller->registerJavascript('lcp', 'modules/'.$this->name.'/views/js/lcp.js',  array( 'position' => 'bottom', 'inline' => false, 'priority' => '0') );

			$this->context->controller->addCSS($this->_path.'views/css/lcp.css', 'all');
			
			return $assigned_js_vars;
		}
		else
		{
			//return $assigned_js_vars.'<script src="'.$this->_path.'views/js/init.js?type=front" type="text/javascript"></script>';
			return $assigned_js_vars.'<script src="'.$this->_path.'views/js/lcp.js" type="text/javascript"></script><link rel="stylesheet" type="text/css" href="'.$this->_path.'views/css/lcp.css">';
		}		
	}





} /* end class */







?>