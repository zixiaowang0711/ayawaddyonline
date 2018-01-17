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

require_once dirname(__FILE__).'/LcpDb.php';
require_once dirname(__FILE__).'/LcpTools.php';

class Lcp
{
	protected static $instance;
	public $timeout;
	public $timeout_for_online_users;
	public $id_staffprofile; /* from session */
	public $_path; /* from session */
	public $name;
	public $smarty;
	public $lcp_session;
	const LCP_TOKEN = 'livechatpro';

	public static function i()
	{
		if (!isset(self::$instance))
			self::$instance = new Lcp();
		return self::$instance;
	}

	public static function getInstance()
	{
		return self::i();
	} 

	public function __construct()
	{	
		date_default_timezone_set(@date_default_timezone_get());	
		$this->lcp_session = @${'_SESSION'}['lcp'];

		if ($this->lcp_session['ps_version'] == '1.7')
			@define('_DB_PREFIX_', $this->lcp_session['db_prefix']);

		$this->name = 'livechatpro';
		$this->id_staffprofile   = $this->getCurrentStaffProfileId($this->lcp_session['id_employee']);
		$this->_path  = $this->lcp_session['_path'];
		$this->timeout = $this->setTimeout('offline'); #seconds
		$this->timeout_for_online_users = $this->setTimeout('online'); #seconds
		
		if (!class_exists('Smarty'))
		{
			require_once dirname(__FILE__).'/../libraries/smarty/Smarty.class.php';
			$this->smarty = new Smarty;
			$this->smarty->force_compile = true;
			$this->smarty->debugging = false;
			$this->smarty->caching = true;
			$this->smarty->cache_lifetime = 120;
			$this->smarty->setCompileDir(dirname(__FILE__).'/../libraries/smarty/compile');
			$this->smarty->setCacheDir(dirname(__FILE__).'/../libraries/smarty/cache');	
		}
		else
			$this->smarty = new Smarty;

		try 
		{ 
			#$this->smarty->registerPlugin('function', 'split_array', 'smartyFunctionSplitArray'); 
			$this->smarty->registerPlugin('function', 'lang', 'smartyFunctionTranslate');
		} 
		catch (Exception $e) 
		{ 
			unset($e); 
		}
	}


	public static function getToken()
	{
		return LcpTools::encrypt(self::LCP_TOKEN);
	}

	public static function isTokenValid($token)
	{
		return (self::getToken() === $token);
	}


	public function fetch($tpl)
	{
		return $this->smarty->fetch($tpl);
	}

	public function display($tpl)
	{
		return $this->smarty->display($tpl);
	}

	public function l($string = '', $filename = '')
	{
		// for testing only
		return $string;

		/*if (empty($filename))
			$filename = __FILE__;

		$_LANG = @$_LANG;

		if (!empty($string))
		{
			$core_dir_count = LcpTools::strlen(dirname(__FILE__).'/../');
			$filename2      = LcpTools::substr($filename, $core_dir_count);
			$filename       = str_replace('\\', '/', $filename2);

			$key = '{'.$filename.'}{'.md5($string).'}';

			if (!empty($_LANG[$key]))
				return $_LANG[$key];
			else
			{
				return;
				//$this->lcp_session['missing_translations'] = array_merge($this->lcp_session['missing_translations'], array('The template language var was not found: $_LANG["'.$key.' => '.$string.'"]'));
				//if ($this->lcp_session['missing_translations']) { echo "<pre>"; print_r($this->lcp_session['missing_translations']); echo "</pre>"; unset($this->lcp_session['missing_translations']);  die('Language error'); }
			}
		}*/
	}



	public function getAvatar($data = '')
	{
		if (empty($data['id_employee']))
			return false;
		else
		{
			$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_staffprofiles` lsp WHERE lsp.`id_employee` = "'.(int)$data['id_employee'].'"');
			
			if ($result) 
				return $result[0]['avatar'];
			else 
				return false;
		}
	}
	


	public function getAvatars($type = 'all')
	{
		$files = array();

		foreach (glob(dirname(__FILE__).'/../views/img/avatars/*') as $key => $filename)
		{
			$filename_exp = explode('/../views/img/avatars/', $filename);

			if ($filename_exp[1] != 'index.php')
			{
				if (empty($type) || $type == 'all')
				{
					$files[$key]['full'] = $filename;

					$files[$key]['ext'] = $filename_exp[1];
				}
				elseif ($type == 'for_datatables')
				{
					//$files[$key] = $filename_exp[1];
					$files[$filename_exp[1]] = $filename_exp[1];
				}
			}
		}

		return $files;
	}

	public function addLog($data)
	{
		$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_logs 
		(
			id_staffprofile,
			id_visitor,
			message
		) 
		VALUES 
		(
			"'.pSQL($data['id_staffprofile']).'",
			"'.pSQL($data['id_visitor']).'",
			"'.pSQL($data['message']).'"
		)';
		
		LcpDb::getInstance()->execute($sql);
		return true;
	}

	public function getEmoticons($with_aliases = false)
	{
		if ($with_aliases == false)
			$emoticons = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_emoticons` le WHERE le.alias = "N"');
		else
			$emoticons = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_emoticons`');

		return $emoticons;
	}



	public function syncFrontChatDialog($data = '')
	{
		# smarty assign module variables
		$module_vars = $this->getModuleVars();
		
		# if !isset id_visitor we get the id_visitor from session
		if (empty($data['id_visitor']))
		{
			$id_visitor = LcpDb::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.$this->name.'_onlinevisitors WHERE session_id = "'.pSQL($this->getSessionId()).'"');
			$data['id_visitor'] = empty($id_visitor[0]['id_visitor']) ? '' : $id_visitor[0]['id_visitor'];	
		}

		# get the required info
		$primary_settings = $module_vars['primary_settings'];
		$visitor_chat_status = $this->getVisitorChatStatus($data['id_visitor']);
		$departments = $this->getDepartments('Active');
		$status = $this->getChatStatus();
		$theme = $this->getTheme(array('id_theme' => $primary_settings['id_theme']));
		$chat_type = $primary_settings['chat_type'];
		$iconset = $this->getIconset(array('id_iconset' => $primary_settings['id_iconset']));
		$visitor_iso_code = empty($this->lcp_session['visitor_iso_code']) ? 'en' : $this->lcp_session['visitor_iso_code'];
		$iconset_img = ($status == 'offline') ? @$iconset['offline_img'][$visitor_iso_code] : @$iconset['online_img'][$visitor_iso_code];
		$visitor_online_archive_details = $this->getVisitorOnlineArchiveDetails(array('id_visitor' => $data['id_visitor']));
		$visitor_archive_details = $this->getVisitorArchiveDetails(array('id_visitor' => $data['id_visitor']));
		$customer_details = @$this->getCustomerDetails(array('id_customer' => $data['id_customer']));
		$department = @$this->getDepartment(array('id_department' => $visitor_online_archive_details['id_department']));

		# get the cookie for chat_toggle
		if ($chat_type == 'Popup') $chat_toggled = 'up';
		else $chat_toggled = $this->getCookie('chat_toggled');

		$assigned_vars = array(
			'status' => $status,
			'iconset' => @$iconset,
			'iconset_img' => @$iconset_img,
			'chat_toggled' => $chat_toggled,
			'customer_details' => $customer_details,
			'visitor_chat_status' => $visitor_chat_status,
			'visitor_online_archive_details' => $visitor_online_archive_details,
			'visitor_archive_details' => $visitor_archive_details,
			'action' => !empty($visitor_archive_details['action']) ? $visitor_archive_details['action'] : '',
			'chat_type' => $chat_type,
			'theme' => $theme,
			'departments' => $departments,
			'department_name' => $department['name'],
		);

		#smarty assign the required info
		$this->smarty->assign($module_vars);
		$this->smarty->assign($assigned_vars);

		return $assigned_vars;
	}


	public function syncChatDialog()
	{
		# get the module vars 
		$module_vars = $this->getModuleVars();
		
		# get the required variables
		$primary_settings = $module_vars['primary_settings'];
		$result_new_chats = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_archive la WHERE la.in_chat = "P" AND la.awaiting_response_from_staff = "Y" AND (la.id_staffprofile = "'.(int)$this->id_staffprofile.'" OR la.id_staffprofile = "0")');
		$result_new_messages = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_archive la WHERE la.in_chat = "Y" AND la.awaiting_response_from_staff = "Y" AND (la.id_staffprofile = "'.(int)$this->id_staffprofile.'" OR la.id_staffprofile = "0")');
		$resunt_online_chats = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_archive la WHERE (la.in_chat = "Y" OR la.in_chat = "P") AND (la.id_staffprofile = "'.(int)$this->id_staffprofile.'" OR la.id_staffprofile = "0")');
		$online_external_users         = $this->getOnlineUsers('External');
		$online_internal_users         = $this->getOnlineUsers('Internal');
		$count_online_external_users   = $this->countOnlineUsers('External');
		$count_online_internal_users   = $this->countOnlineUsers('Internal');
		$count_online_visitors         = $this->countOnlineVisitors();
		$count_active_archives         = $this->countArchives('Y');
		$count_pending_archives        = $this->countArchives('P');
		$count_active_pending_archives = $count_active_archives + $count_pending_archives;
		$active_archives               = $this->getArchives('Y');
		$pending_archives              = $this->getArchives('P');
		$newly_closed_archives         = array();//$this->getArchives('N', false, true);
		$active_pending_archives       = array_merge($active_archives, $pending_archives, $newly_closed_archives);
		$emoticons                     = $this->getEmoticons();
		$staff_chat_status             = $this->getStaffChatStatus($this->id_staffprofile);
		$predefined_messages           = $this->getPredefinedMessages();
		$employee                      = $this->getEmployeeById($this->lcp_session['id_employee']);

		$assigned_vars = array(
			'primary_settings'              => $primary_settings,
			'new_chats'                     => $result_new_chats[0]['COUNT(*)'],
			'new_messages'                  => $result_new_messages[0]['COUNT(*)'],
			'new_chats_and_messages'        => ($result_new_chats[0]['COUNT(*)'] + $result_new_messages[0]['COUNT(*)']),
			'online_chats'                  => $resunt_online_chats[0]['COUNT(*)'],
			'staff_chat_status'             => $staff_chat_status,
			'predefined_messages'           => $predefined_messages,
			'id_staffprofile'               => $this->id_staffprofile,
			'employee'                      => $employee,
			'online_external_users'         => $online_external_users,
			'online_internal_users'         => $online_internal_users,
			'count_online_external_users'   => $count_online_external_users,
			'count_online_internal_users'   => $count_online_internal_users,
			'count_total_online_users'      => $count_online_external_users + $count_online_internal_users,
			'count_online_visitors'         => $count_online_visitors,
			'active_pending_archives'       => $active_pending_archives,
			'count_active_pending_archives' => $count_active_pending_archives,
			'count_pending_archives'        => $count_pending_archives,
			'emoticons'                     => $emoticons,
		);

		# smarty assign the variables
		$this->smarty->assign($module_vars);
		$this->smarty->assign($assigned_vars);
	
		return $assigned_vars;
	}


	public function getPredefinedMessages()
	{
		$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_predefinedmessages`');
		
		if ($result) 
			return $result;
		else
			return false;
	}

	public function checkModuleConflict()
	{
		if (LcpTools::substr(LcpTools::getValue('configure'), 0, 3) == 'pm_')
			return false;
		else
			return true;
	}

	public function getEmployeeIdByStaffProfile($id_staffprofile = '')
	{
		$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_staffprofiles` lsp WHERE lsp.id_staffprofile = "'.(int)$id_staffprofile.'"');
		
		if ($result) 
			return $result[0]['id_employee'];
		else
			return false;
	}



	public function getVisitorOnlineArchiveDetails($data)
	{
		$sql = 'SELECT *
			FROM `'._DB_PREFIX_.$this->name.'_archive` la
			LEFT JOIN `'._DB_PREFIX_.$this->name.'_staffprofiles` lsp ON (la.`id_staffprofile` = lsp.`id_staffprofile`)
			WHERE la.id_visitor = "'.pSQL($data['id_visitor']).'" AND la.in_chat = "Y"';
		#d($sql);
		$archive_details = LcpDb::getInstance()->executeS($sql);

		#d($archive_details);

		if ($archive_details)
		{
			$archive_details = $archive_details[0];

			$archive_details['messages'] = $archive_details['messages'];
		}

		return $archive_details;
	}


	public function getVisitorArchiveDetails($data)
	{
		$sql = 'SELECT *
			FROM `'._DB_PREFIX_.$this->name.'_archive` la
			LEFT JOIN `'._DB_PREFIX_.$this->name.'_staffprofiles` lsp ON (la.`id_staffprofile` = lsp.`id_staffprofile`)
			WHERE la.id_visitor = "'.pSQL($data['id_visitor']).'" ORDER BY la.id_archive DESC LIMIT 1';

		$archive_details = LcpDb::getInstance()->executeS($sql);

		if ($archive_details)
		{
			$archive_details = $archive_details[0];

			$archive_details['signature'] = LcpTools::jsonEncode($archive_details['signature']);
			#$archive_details['messages'] = $archive_details['messages'];
		}
		return $archive_details;
	}

	public function getStaffChatStatus($id_staffprofile = '')
	{
		if (empty($id_staffprofile))
			$id_staffprofile = $this->id_staffprofile;

		$internal_users = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_onlineusers` lou WHERE lou.type = "Internal" AND lou.id_staffprofile = "'.pSQL($id_staffprofile).'"');

		if (empty($internal_users))
			return 'offline';
		else
			return 'online';
	}

	public function getChatStatus()
	{
		$internal_users = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_onlineusers` lou WHERE lou.type = "Internal"');

		if (empty($internal_users))
			return 'offline';
		else
			return 'online';
	}

	public function changeStaffStatus($data = '')
	{
		if ($this->checkIfStaffProfileIsActive($this->id_staffprofile) == false)
			return false;

		if ($data['status'] == 'Offline')
			LcpDb::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.$this->name.'_onlineusers WHERE id_staffprofile = "'.(int)$this->id_staffprofile.'"');
		else
		{
			if ($this->checkIfStaffProfileIdInOnlineUsers($this->id_staffprofile) == false)
			{
				$data['id_staffprofile'] = $this->id_staffprofile;
				$data['id_visitor'] = '0';
				$data['type'] = 'Internal';
				$this->addOnlineUser($data);
			}
		}

		return true;
	}

	/*id_staffprofile, id_visitor, type*/
	public function addOnlineUser($data = '')
	{
		$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_onlineusers
			(
				id_staffprofile,
				id_visitor,
				type
			) 
			VALUES 
			(
				"'.pSQL($data['id_staffprofile']).'", 
				"'.pSQL($data['id_visitor']).'", 
				"'.pSQL($data['type']).'"
			)';

		LcpDb::getInstance()->execute($sql);
	}



	public function checkIfEmployeeIsSuperAdmin($data = '')
	{
		$result = LcpDb::getInstance()->executeS(
			'SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_staffprofiles lsp 
			LEFT JOIN `'._DB_PREFIX_.'employee` e ON (lsp.`id_employee` = e.`id_employee`)
			WHERE lsp.id_staffprofile = "'.(int)$data['id_staffprofile'].'" AND e.id_profile = "1"'
			);

		$res = $result[0]['COUNT(*)'];

		if (empty($res))
			return false;
		else
			return true;
	}


	public function checkIfStaffProfileIsActive($id_staffprofile)
	{
		#d('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_staffprofiles lsp WHERE id_staffprofile = "'.(int)$id_staffprofile.'" AND is_active = "Y"');
		$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_staffprofiles lsp WHERE id_staffprofile = "'.(int)$id_staffprofile.'" AND is_active = "Y"');

		$res = $result[0]['COUNT(*)'];

		if (empty($res))
			return false;
		else
			return true;
	}


	public function checkIfStaffProfileIdInOnlineUsers($id_staffprofile)
	{
		$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_onlineusers lou WHERE id_staffprofile = "'.(int)$id_staffprofile.'"');

		$res = $result[0]['COUNT(*)'];

		if (empty($res))
			return false;
		else
			return true;
	}

	public function getThemes()
	{
		return LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_themes`');
	}

	public function getTheme($data)
	{
		$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_themes` lt WHERE lt.id_theme = "'.(int)$data['id_theme'].'"');
		
		if ($result) 
			return $result[0];
		else
			return false;
	}

	public function getIconsets()
	{
		return LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_iconsets`');
	}

	public function getIconset($data)
	{
		$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_iconsets` li WHERE li.id_iconset = "'.(int)$data['id_iconset'].'"');

		$arr_info = array();

		foreach ($result as $value)
		{
			foreach ($this->lcp_session['languages'] as $value2)
			{
				$iconsets_lang = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_iconsets_lang` lil WHERE lil.id_iconset = "'.pSQL($value['id_iconset']).'" AND lil.iso_code = "'.pSQL($value2['iso_code']).'"');
				
				if (!empty($iconsets_lang))
				{
					$value['offline_img'][$value2['iso_code']] = $iconsets_lang[0]['offline_img'];
					$value['online_img'][$value2['iso_code']] = $iconsets_lang[0]['online_img'];
				}
			}
			$arr_info[] = $value;
		}

		$result = $arr_info;
		
		if ($result) 
			return $result[0];
		else
			return false;
	}

	public function http_or_https()
	{
		return empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
	}

	public function getSettings()
	{
		$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_settings`');

		$arr_info = array();

		if ($result)
		{
			foreach ($result as $value)
			{
				$value['code'] = $value['code'];
				$arr_info[] = $value;
			}
		}

		return $arr_info;
	}

	public function getSetting($data)
	{
		$result = LcpDb::getInstance()->executeS('
			SELECT *, ls.name AS name, ls.is_default AS is_default
			FROM `'._DB_PREFIX_.$this->name.'_settings` ls
			LEFT JOIN `'._DB_PREFIX_.$this->name.'_iconsets` li ON (ls.`id_iconset` = li.`id_iconset`)
			LEFT JOIN `'._DB_PREFIX_.$this->name.'_themes` lt ON (ls.`id_theme` = lt.`id_theme`)
			WHERE ls.id_setting = "'.(int)$data['id_setting'].'"
		');

		$arr_info = array();

		foreach ($result as $value)
		{
			foreach ($this->lcp_session['languages'] as $value2)
			{
				$settings_lang = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_settings_lang` lsl WHERE lsl.id_setting = "'.pSQL($value['id_setting']).'" AND lsl.iso_code = "'.pSQL($value2['iso_code']).'"');
				
				if (!empty($settings_lang))
				{
					$value['offline_header_message'][$value2['iso_code']] = $settings_lang[0]['offline_header_message'];
					$value['online_header_message'][$value2['iso_code']] = $settings_lang[0]['online_header_message'];
					$value['offline_welcome_message'][$value2['iso_code']] = $settings_lang[0]['offline_welcome_message'];
					$value['online_welcome_message'][$value2['iso_code']] = $settings_lang[0]['online_welcome_message'];
				}
			}
			$arr_info[] = $value;
		}

		$result = $arr_info;

		//fac update la id settings la primary in baza de date
		LcpDb::getInstance()->Execute('UPDATE '._DB_PREFIX_.$this->name.'_settings SET is_primary = "0"');

		LcpDb::getInstance()->Execute('UPDATE '._DB_PREFIX_.$this->name.'_settings SET is_primary = "1" WHERE id_setting = "'.(int)$data['id_setting'].'"');		

		if ($result) 
			return $result[0];
		else
			return false;
	}

	public function getPrimarySettings($data = '')
	{
		#d($this->lcp_session);
		$iso_code = (strstr($_SERVER['REQUEST_URI'], 'admin') == false || strstr($_SERVER['REQUEST_URI'], 'backoffice') == false) ? $this->lcp_session['visitor_iso_code'] :  $this->lcp_session['employee_iso_code'];
		/*if (empty($this->lcp_session['employee_iso_code']))
			$iso_code = $this->lcp_session['visitor_iso_code'];
		else
			$iso_code = $this->lcp_session['employee_iso_code'];*/

		$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_settings` ls
			LEFT JOIN `'._DB_PREFIX_.$this->name.'_settings_lang` lsl ON (ls.`id_setting` = lsl.`id_setting`) 
			LEFT JOIN `'._DB_PREFIX_.$this->name.'_themes` lt ON (ls.`id_theme` = lt.`id_theme`) 
			WHERE ls.is_primary = "1" AND lsl.iso_code = "'.pSQL($iso_code).'"');

		if (!$result)
		{
			$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_settings` ls
			LEFT JOIN `'._DB_PREFIX_.$this->name.'_settings_lang` lsl ON (ls.`id_setting` = lsl.`id_setting`) 
			LEFT JOIN `'._DB_PREFIX_.$this->name.'_themes` lt ON (ls.`id_theme` = lt.`id_theme`) 
			WHERE ls.is_primary = "1" ORDER BY lsl.id_lang DESC LIMIT 1');
		}

		#d($result);

		if ($result) 
			return $result[0];
		else
			return false;
	}

	public function getDepartment($data)
	{
		$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_departments` WHERE id_department = "'.(int)$data['id_department'].'"');
		
		if ($result) 
			return $result[0];
		else
			return false;
	}

	public function getDepartments($status = 'Active', $type = 'all')
	{
		$arr_info = array();

		if (empty($status))
			$status = 'all';

		if ($status == 'all')
			$status_condition = '';
		else
			$status_condition = 'WHERE ld.status = "'.$status.'"';

		$departments = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_departments` ld '.$status_condition);

		if ($type == 'all')
			return $departments;

		elseif ($type == 'for_datatables')
		{
			foreach ($departments as $value)
				$arr_info[$value['name']] = $value['name'];

			return $arr_info;
		}
	}

	public function syncEmailTemplates()
	{	
		$status = '';

		foreach ($this->lcp_session['languages'] as $value)
		{
			if ($value['iso_code'] != 'en')
				$status[] = $this->copyr(dirname(__FILE__).'/../mails/en/', dirname(__FILE__).'/../mails/'.$value['iso_code'].'/');
		}

		return true;
	}


	public function copyr($src, $dst) 
	{
		$dir = opendir($src); 
		
		@mkdir($dst); 
		
		while (false !== ($file = readdir($dir))) 
		{
			if (($file != '.' ) && ( $file != '..')) 
			{
				if (is_dir($src.'/'.$file)) 
					$this->copyr($src.'/'.$file, $dst.'/'.$file); 
				else 
					copy($src.'/'.$file, $dst.'/'.$file); 
			} 
		} 
		closedir($dir);
	}


	public function syncStaffProfiles()
	{
		$employees = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'employee`');
		$employees_ids = array();

		foreach ($employees as $value)
		{
			if ($this->checkIfEmployeeInStaffProfile($value['id_employee']) == false)
			{
				//adaugam staffprofile-ul
				$this->addStaffProfile(array(
					'id_employee' => $value['id_employee'],
					'avatar' => 'sample-40x40.png',
					'departments' => '1',
					'welcome_message' => 'Hello!',
					'signature' => '',
					'is_active' => 'Y',
				));

			}

			$employees_ids[] = $value['id_employee'];
		}

		$staffprofiles_ids_to_delete = $this->getStaffProfilesIdsToDelete($employees_ids);

		foreach ($staffprofiles_ids_to_delete as $value)
		{
			$this->deleteStaffProfile(array(
				'id_staffprofile' => $value
			));
		}
		return true;
	}


	public function getStaffProfile($data)
	{
		$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'employee` e
			LEFT JOIN `'._DB_PREFIX_.$this->name.'_staffprofiles` lsp ON (e.`id_employee` = lsp.`id_employee`)
			WHERE e.id_employee = "'.(int)$data['id_employee'].'"');
		return $result[0];
	}



	public function setTimeout($for_users = 'offline')
	{
		/*$user_agents = array(
			'android'    => 'Mozilla/5.0 (Linux; Android 4.2; Nexus 7 Build/JOP40C) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Safari/535.19',
			'iphone6'    => 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0_1 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A523 Safari/8536.25',
			'blackberry' => 'Mozilla/5.0 (BB10; Touch) AppleWebKit/537.10+ (KHTML, like Gecko) Version/10.0.9.2372 Mobile Safari/537.10+',
			'pc'         => 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko',
		);*/

		require_once(dirname(__FILE__).'/../libraries/Mobile_Detect2.php');
		$mobile_detect = new Mobile_Detect2;

		#$this->mobile_detect->setUserAgent($user_agents['pc']); // for testing 
		$device_type = ($mobile_detect->isMobile() ? ($mobile_detect->isTablet() ? 'tablet' : 'phone') : 'computer');
		#d($device_type);
		
		if ($for_users == 'offline')
		{
			if ($device_type == 'computer') $timeout = 90;
			else $timeout = 86400;
		}
		else
		{
			if ($device_type == 'computer') $timeout = 120;
			else $timeout = 86400;
		}
		return $timeout;
	}

	public function getRating($data)
	{
		$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_ratings` lr WHERE lr.`id_rating` = "'.(int)$data['id_rating'].'"');
		
		if ($result) 
			return $result[0];
		else
			return false;
	}

	public function getVisitor($data = '')
	{
		if (empty($data['id_visitor']))
			return false;
		else
		{
			$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_onlinevisitors` lov WHERE lov.`id_visitor` = "'.pSQL($data['id_visitor']).'"');
			
			if ($result) 
				return $result[0];
			else
				return false;
		}
	}

	public function getUser($data = '')
	{
		if (empty($data['id_user']))
			return false;
		else
		{
			$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_onlineusers` lou WHERE lou.`id_user` = "'.pSQL($data['id_user']).'"');
			
			if ($result) 
				return $result[0];
			else
				return false;
		}
	}

	public function getVisitorVisitedPages($data = '')
	{
		if (empty($data['id_visitor']))
		{
			$data = array();

			$data['id_visitor'] = $this->getLastVisitorId();
		}

		return LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_visitedpages` lv WHERE lv.`id_visitor` = "'.pSQL($data['id_visitor']).'"');
	}

	public function getMessage($data)
	{
		$result = LcpDb::getInstance()->executeS('
			SELECT *, (
			SELECT `name`
			FROM  `'._DB_PREFIX_.$this->name.'_departments` ld
			WHERE ld.`id_department` = lm.`id_department`
			) `department_name`
			FROM `'._DB_PREFIX_.$this->name.'_messages` lm WHERE lm.`id_message` = "'.(int)$data['id_message'].'"');

		if ($result) 
			return $result[0];
		else
			return false;
	}

	public function getTicket($data)
	{
		$result = LcpDb::getInstance()->executeS('
			SELECT *, (
				SELECT `name`
				FROM  `'._DB_PREFIX_.$this->name.'_departments` ld
				WHERE ld.`id_department` = lt.`id_department` 
				LIMIT 1
			) `department_name`,
			(
				SELECT CONCAT(firstname, " ", lastname)
				FROM  `'._DB_PREFIX_.'employee` e
				WHERE e.`id_employee` = lt.`id_employee` 
				LIMIT 1
			) `staff_name`,
			(
				SELECT `email` 
				FROM  `'._DB_PREFIX_.'employee` e
				WHERE e.`id_employee` = lt.`id_employee` 
				LIMIT 1
			) `staff_email`,
			(
				SELECT CONCAT(firstname, " ", lastname)  
				FROM  `'._DB_PREFIX_.'customer` c
				WHERE c.`id_customer` = lt.`id_customer` 
				LIMIT 1
			) `customer_name`,
			(
				SELECT `email` 
				FROM  `'._DB_PREFIX_.'customer` c
				WHERE c.`id_customer` = lt.`id_customer` 
				LIMIT 1
			) `customer_email` 
			FROM `'._DB_PREFIX_.$this->name.'_tickets` lt 
			WHERE lt.`id_ticket` = "'.(int)$data['id_ticket'].'"');

		if ($result) 
		{
			$result = $result[0];
			$result['replyes'] = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_ticketsreplyes` ltr WHERE ltr.`id_ticket` = "'.(int)$result['id_ticket'].'" ORDER BY id_reply DESC');
			
			$departments = $this->getDepartments();

			$this->smarty->assign(array(
				'departments' => $departments,
				'ticket_details' => $result,
			));
			return $result;
		}
		else
			return false;
	}

	public function getCurrentStaffProfileId($id_employee)
	{
		#d('SELECT id_staffprofile FROM `'._DB_PREFIX_.$this->name.'_staffprofiles` lsp WHERE lsp.id_employee = "'.(int)$id_employee.'"');
		$result = LcpDb::getInstance()->executeS('SELECT id_staffprofile FROM `'._DB_PREFIX_.$this->name.'_staffprofiles` lsp WHERE lsp.id_employee = "'.(int)$id_employee.'"');
		#d($result);
		if ($result) 
			return $result[0]['id_staffprofile'];
		else
			return 0;
	}



	public function getModuleVars()
	{
		#d($_SESSION);
		$module_dir = dirname(__FILE__).'/../';
		$module_templates_dir = dirname(__FILE__).'/../views/templates/';
		$module_templates_back_dir = dirname(__FILE__).'/../views/templates/admin/';
		$module_templates_front_dir = dirname(__FILE__).'/../views/templates/front/';
		$module_link = 'index.php?controller=AdminModules&configure='.$this->name.'&token='.$this->lcp_session['module_token'].'&module_name='.$this->name;
		$emoticons = $this->getEmoticons();
		$avatars = $this->getAvatars('for_datatables');
		$departments  = $this->getDepartments('all', 'for_datatables'); #d($departments);
		$module_url = $this->http_or_https().$this->lcp_session['shop_domain'].$this->lcp_session['_path'];
		$staff_avatar = $this->getAvatar(array('id_employee' => $this->lcp_session['id_employee']));
		$primary_settings = Lcp::i()->getPrimarySettings();
		$doc_iso = file_exists($module_dir.$this->name.'/readme_'.$this->lcp_session['employee_iso_code'].'.pdf') ? $this->lcp_session['employee_iso_code'] : 'en';
		$employee_details = $this->getEmployeeById($this->lcp_session['id_employee']);

		$vars = array(
			'dev_modules_link' => 'http://addons.prestashop.com/'.$this->lcp_session['employee_iso_code'].'/93_proquality',
			'support_link' => 'http://addons.prestashop.com/'.$this->lcp_session['employee_iso_code'].'/contact-community.php?id_product='.$this->lcp_session['id_product'],
			'doc_link' => '../modules/'.$this->name.'/readme_'.$doc_iso.'.pdf',
			'video_link' => 'https://www.youtube.com/watch?v=YWo1dP_x_rU',
			'emoticons' => $emoticons,
			'emoticons_json' => LcpTools::jsonEncode($this->arrayToObject($emoticons)),
			'avatars_json' => LcpTools::jsonEncode($this->arrayToObject($avatars)),
			'departments_json' => LcpTools::jsonEncode($this->arrayToObject($departments)),
			'primary_settings' => $primary_settings,
			'primary_settings_json' => LcpTools::jsonEncode($this->arrayToObject($primary_settings)),
			'module_version' => $this->lcp_session['module_version'],
			'ps_version' => $this->lcp_session['ps_version'],
			'module_name' => $this->name,
			'module_path' => $this->lcp_session['_path'],
			'module_url' => $module_url,
			'module_dir' => $module_dir,
			'module_templates_dir' => $module_templates_dir,
			'module_templates_back_dir' => $module_templates_back_dir,
			'module_templates_front_dir' => $module_templates_front_dir,
			'shop_domain' => $this->lcp_session['shop_domain'],
			'module_token' => $this->lcp_session['module_token'],
			'ajax_token' => $this->getToken(),
			'employees_token' => $this->lcp_session['employees_token'],
			'module_link' => $module_link,
			'db_prefix' => _DB_PREFIX_,
			'id_customer' => $this->lcp_session['id_customer'],
			'id_employee' => $this->lcp_session['id_employee'],
			'internal' => $employee_details['firstname'].' '.$employee_details['lastname'],
			'id_staffprofile' => $this->id_staffprofile,
			'staff_avatar' => $staff_avatar,
			'employee_is_superadmin' => ($this->checkIfEmployeeIsSuperAdmin(array('id_staffprofile' => $this->id_staffprofile)) == true) ? 'Y' : 'N',
			'employee_iso' => $this->lcp_session['employee_iso_code'],
			'employee_id_lang' => $this->lcp_session['employee_id_lang'],
			'http_or_https' => $this->http_or_https(),
			'lcp_current_url' => $this->http_or_https()."$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
		);

		return $vars;
	}

	public function popupChat()
	{
		#$this->lcp_session = $lcp_session;
		#echo "<pre>"; print_r($lcp_session.'aa');
		$vars = $this->getModuleVars();
		$this->smarty->assign($vars);

		$assigned_js_vars = '<script>
		var lcp_init = "front",
			lcp_session = JSON.parse(JSON.stringify('.LcpTools::jsonEncode($this->arrayToObject(${'_SESSION'}['lcp'])).')),
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

		$assigned_js_vars .= $this->smarty->fetch(dirname(__FILE__).'/../views/templates/hook/translations.js.tpl');
		$assigned_js_vars .= '</script>';

		//return $assigned_js_vars.'<script src="'.$this->_path.'views/js/init.js?type=front" type="text/javascript"></script>';
		return '<script src="'.$this->_path.'libraries/jquery/jquery-1.10.2.js" type="text/javascript"></script>
		<script src="'.$this->_path.'libraries/jquery/ui/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>'.
		$assigned_js_vars.
		'<script src="'.$this->_path.'views/js/lcp.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="'.$this->_path.'views/css/lcp.css">';
	}

	public function generateChatWidgetFront()
	{
		$status = $this->getChatStatus();

		$primary_settings = $this->getPrimarySettings();

		$theme = $this->getTheme(array('id_theme' => $primary_settings['id_theme']));

		$iconset = $this->getIconset(array('id_iconset' => $primary_settings['id_iconset']));

		$iconset_img = ($status == 'offline') ? $iconset['offline_img'][$this->lcp_session['visitor_iso_code']] : $iconset['online_img'][$this->lcp_session['visitor_iso_code']];

		$this->syncFrontChatDialog();

		$html = '';

		if ($primary_settings['chat_type'] == 'Popup')
		{
			if ($primary_settings['orientation'] == 'left-top') $offset = 'top: '.$primary_settings['offset'].'px; left: 0px;';
			else if ($primary_settings['orientation'] == 'left-bottom') $offset = 'bottom: '.$primary_settings['offset'].'px; left: 0px;';
			else if ($primary_settings['orientation'] == 'right-top') $offset = 'top: '.$primary_settings['offset'].'px; right: 0px;';
			else if ($primary_settings['orientation'] == 'right-bottom') $offset = 'bottom: '.$primary_settings['offset'].'px; right: 0px;';

			$position = ($primary_settings['fixed_position'] == 'Y') ? 'position: fixed !important;' : '';

			$display = '';

			if ($primary_settings['hide_when_offline'] == 'Y')
				$display = 'display: none;';

			$html .= '<input type="hidden" name="id_visitor" id="id_visitor" size="20" value="">	
					<div id="popup_chat_window" style="cursor: hand; cursor: pointer; z-index: 9998; '.$position.' '.$display.' '.$offset.'">
						<a onclick=\'lcp.popupWindow("'.$this->_path.'ajax.php?type=popupChat", "Chat", "'.($theme['width']).'", "'.($theme['height'] + 35).'"); \' href="javascript:{}">
							<img id="lcp_popup_img_fixed" border="0" src="'.$this->_path.'views/img/iconsets/'.$iconset_img.'">
						</a>
					</div>
					<script type="text/javascript">$("#lcp_popup_img").attr("src", "'.$this->_path.'views/img/iconsets/'.$iconset_img.'");</script>';
		}
		else
		{
			if ($primary_settings['orientation'] == 'bottom-left') $offset = 'left: '.$primary_settings['offset'].'px; bottom: 0px;';
			else if ($primary_settings['orientation'] == 'bottom-right') $offset = 'right: '.$primary_settings['offset'].'px; bottom: 0px;';

			$html .= '<div id="slide_chat_window" style="z-index: 9998; position: fixed !important; '.$offset.'">'.$this->smarty->fetch(dirname(__FILE__).'/../views/templates/front/chat_window.tpl').'</div>';
		}
		return $html;
	}


	public function generateChatWidgetAdmin()
	{
		$staff_chat_status = $this->getStaffChatStatus();

		$primary_settings = $this->getPrimarySettings();
		#d($primary_settings);
		$employee = $this->getEmployeeById($this->lcp_session['id_employee']);

		if ($this->lcp_session['ps_version'] == '1.5')
		{
			if ($staff_chat_status == 'offline') $notification_style = 'margin-top: 12px; margin-left: 5px; float: left; color: #ffffff;';
			else $notification_style = 'margin-top: 12px; margin-left: 5px; float: left; color: #7ab726;';
		}
		else
		{
			if ($staff_chat_status == 'offline') $notification_style = 'margin-left: 40px; color: #b3b3b3;';
			else $notification_style = 'margin-left: 40px; color: #7ab726;';
		}

		$this->syncChatDialog();

		$html = '<a id="dialog_chat_a" href="javascript:{}" style="'.$notification_style.'"><span class="lcp blink-container">(<span id="awaiting_response_chat_dialogs">0</span>) <span class="icon-comment fa fa-comment" style="margin-top: 10px;"></span></span></a>
		'.(($primary_settings['chat_type_admin'] == 'Popup') ? '<div id="dialog_chat" title="'.$this->l('Staff:').' '.$employee['firstname'].' '.$employee['lastname'].'" style="overflow-x: hidden; display:none;">'.$this->smarty->fetch(dirname(__FILE__).'/../views/templates/admin/popup_chat_window.tpl').'</div>' : $this->smarty->fetch(dirname(__FILE__).'/../views/templates/admin/slide_chat_window.tpl'));
				

		return $html;
	}


	public function getPredefinedMessage($data)
	{
		$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_predefinedmessages` lpm WHERE lpm.`id_predefinedmessage` = "'.(int)$data['id_predefinedmessage'].'"');

		if ($result) 
			return $result[0];
		else
			return false;
	}


	public function getArchive($data)
	{
		$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_archive` WHERE id_archive = "'.(int)$data['id_archive'].'" LIMIT 1');
		
		if ($result) 
			return $result[0];
		else
			return false;
	}


	public function isChatInvitation($data)
	{
		$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_archive la WHERE la.in_chat = "P" AND la.chat_request_from = "Staff" AND la.id_visitor = "'.pSQL($data['id_visitor']).'"');

		$res = $result[0]['COUNT(*)'];

		if (!empty($res)) 
			return true;
		else 
			return false;
	}

	public function syncOnlineUsers()
	{
		LcpDb::getInstance()->Execute('UPDATE '._DB_PREFIX_.$this->name.'_onlineusers SET last_update = DATE_FORMAT(NOW(),\'%Y-%m-%d %H:%i:%s\') WHERE id_staffprofile = "'.(int)$this->id_staffprofile.'"');

		////////////////////////////////////////////////////////////////////////////////////////////////////

		$internals_to_delete = LcpDb::getInstance()->executeS('SELECT id_staffprofile, id_user FROM `'._DB_PREFIX_.$this->name.'_onlineusers` WHERE UNIX_TIMESTAMP(last_update) < '.pSQL(time() - $this->timeout));

		foreach ($internals_to_delete as $value)
		{
			LcpDb::getInstance()->Execute('UPDATE '._DB_PREFIX_.$this->name.'_archive SET in_chat = "N", awaiting_response_from_staff = "N", is_archive = "Y" WHERE id_staffprofile = "'.(int)$value['id_staffprofile'].'"');
			#LcpDb::getInstance()->Execute('UPDATE '._DB_PREFIX_.$this->name.'_staffprofiles SET status = "Inactive" WHERE id_staffprofile = "'.(int)$value['id_staffprofile'].'"');
			LcpDb::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.$this->name.'_onlineusers WHERE id_user = "'.(int)$value['id_user'].'"');
		}

		////////////////////////////////////////////////////////////////////////////////////////////////////

		$online_external_users = $this->getOnlineUsers('External');

		if (!empty($online_external_users))
		{
			$online_external_users_id_visitors_list = '';
			foreach ($online_external_users as $value)
				$online_external_users_id_visitors_list .= '"'.(int)$value['id_visitor'].'",';
			$online_external_users_id_visitors_list = LcpTools::substr($online_external_users_id_visitors_list, 0, -1);
			//$online_external_users_id_visitors[] = $value['id_visitor'];
		}
		#d($online_external_users);
		if (empty($online_external_users))
			$visitors_to_delete = LcpDb::getInstance()->executeS('SELECT id_visitor FROM `'._DB_PREFIX_.$this->name.'_onlinevisitors` WHERE UNIX_TIMESTAMP(last_update) < '.pSQL(time() - $this->timeout));
		else
		{
			$visitors_to_delete_offline = LcpDb::getInstance()->executeS('SELECT id_visitor FROM `'._DB_PREFIX_.$this->name.'_onlinevisitors` WHERE id_visitor NOT IN ('.$online_external_users_id_visitors_list.') AND UNIX_TIMESTAMP(last_update) < '.pSQL(time() - $this->timeout));
			$visitors_to_delete_online = LcpDb::getInstance()->executeS('SELECT id_visitor FROM `'._DB_PREFIX_.$this->name.'_onlinevisitors` WHERE id_visitor IN ('.$online_external_users_id_visitors_list.') AND UNIX_TIMESTAMP(last_update) < '.pSQL(time() - $this->timeout_for_online_users));
			$visitors_to_delete = array_merge($visitors_to_delete_online, $visitors_to_delete_offline);
		}

		////////////////////////////////////////////////////////////////////////////////////////////////////

		foreach ($visitors_to_delete as $value)
		{
			LcpDb::getInstance()->Execute('UPDATE '._DB_PREFIX_.$this->name.'_archive SET in_chat = "N", awaiting_response_from_staff = "N", is_archive = "Y" WHERE id_visitor = "'.pSQL($value['id_visitor']).'"');
			LcpDb::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.$this->name.'_onlineusers WHERE id_visitor = "'.pSQL($value['id_visitor']).'"');
			LcpDb::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.$this->name.'_onlinevisitors WHERE id_visitor = "'.pSQL($value['id_visitor']).'"');
			LcpDb::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.$this->name.'_visitedpages WHERE id_visitor = "'.pSQL($value['id_visitor']).'"');
		}

		return true;

	}

	public function syncOnlineVisitors($data = '')
	{
		LcpDb::getInstance()->Execute('UPDATE '._DB_PREFIX_.$this->name.'_onlinevisitors SET last_update = DATE_FORMAT(NOW(),\'%Y-%m-%d %H:%i:%s\') WHERE id_visitor = "'.pSQL($data['id_visitor']).'"');

		$internals_to_delete = LcpDb::getInstance()->executeS('SELECT id_staffprofile FROM `'._DB_PREFIX_.$this->name.'_onlineusers` WHERE UNIX_TIMESTAMP(last_update) < '.pSQL(time() - $this->timeout));

		foreach ($internals_to_delete as $value)
		{
			LcpDb::getInstance()->Execute('UPDATE '._DB_PREFIX_.$this->name.'_archive SET in_chat = "N", awaiting_response_from_staff = "N", is_archive = "Y" WHERE id_staffprofile = "'.(int)$value['id_staffprofile'].'"');
			#LcpDb::getInstance()->Execute('UPDATE '._DB_PREFIX_.$this->name.'_staffprofiles SET status = "Inactive" WHERE id_staffprofile = "'.(int)$value['id_staffprofile'].'"');
			LcpDb::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.$this->name.'_onlineusers WHERE id_staffprofile = "'.(int)$value['id_staffprofile'].'"');
		}

		return true;
	}	


	public function getVisitorArvhives($data = '')
	{
		return LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_archive` la WHERE la.`id_visitor` = "'.pSQL($data['id_visitor']).'"');
	}


	public function getArchives($in_chat = 'Y', $group_by_ip = false, $newly_closed = false)
	{
		$primary_settings = $this->getPrimarySettings();

		$archives = LcpDb::getInstance()->executeS(
		'SELECT *, 
		(
			SELECT `name` FROM  `'._DB_PREFIX_.$this->name.'_departments` ld WHERE ld.`id_department` = la.`id_department`
		) `department_name`
		FROM `'._DB_PREFIX_.$this->name.'_archive` la
			WHERE 
			'.(($primary_settings['new_chat_rings_to'] == 'all') ? '(la.id_staffprofile = "'.(int)$this->id_staffprofile.'" OR la.id_visitor = "i'.(int)$this->id_staffprofile.'" OR la.id_staffprofile = "0")' : '(la.id_staffprofile = "'.(int)$this->id_staffprofile.'" OR la.id_visitor = "i'.(int)$this->id_staffprofile.'")').'  
			'.(!empty($in_chat) ? 'AND (la.in_chat = "'.pSQL($in_chat).'")' : '').' 
			'.(($newly_closed == true) ? 'AND (UNIX_TIMESTAMP(la.last_update) > '.pSQL(strtotime('-60 minutes')).' AND la.action = "chatClosedFromClient")' : '').'
			'.(($group_by_ip == true) ? ' GROUP BY la.ip ' : '').' 
			');
		
		return $archives;
	}


	public function showThemesDropdown()
	{
		$themes         = $this->getThemes();
		$this->smarty->assign(array(
			'themes' => $themes,
		));
		return $this->display(dirname(__FILE__).'/../views/templates/admin/ajax.themes.tpl');
	}

	public function deleteTheme($data)
	{
		if ($this->isDemo() == true)
			return 'ERROR_DEMO_MODE';
		
		LcpDb::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.$this->name.'_themes WHERE id_theme = "'.(int)$data['id_theme'].'"');

		return true;
	}

	public function getTranslations($data)
	{
		return $this->smarty->assign('sections', $this->getLanguageVars($data['iso_code']));
	}

	public function getLanguageVars($iso_code)
	{
		$iso_code = (empty($iso_code)) ? $this->lcp_session['employee_iso_code'] : $iso_code;

		if (!file_exists(dirname(__FILE__).'/../translations/lang_'.$iso_code.'.php'))
			@fopen(dirname(__FILE__).'/../translations/lang_'.$iso_code.'.php', 'w');

		require_once(dirname(__FILE__).'/../translations/lang_'.$iso_code.'.php');
		require_once(dirname(__FILE__).'/../libraries/filetree/php_file_tree.php');

		$location = str_replace('\\', '/', dirname(__FILE__).'/../views/templates/');
		$allowed_ext = array('tpl');
		$excluded_dirs = array();//array($location."_temp_");
		
		php_file_tree($location, "javascript:alert('You clicked on [link]');", $allowed_ext, $excluded_dirs);
		$array_file_tree = ${'_SESSION'}['array_file_tree'];
		unset(${'_SESSION'}['array_file_tree']);

		$match = $match2 = array();
		#d($array_file_tree);
		// citesc toate fisierele si din fisiere iau variabilele de limba
		$language_file_contents = '<?php '."\n";
		foreach ($array_file_tree as $key => $value) 
		{
			//linux
			$value = str_replace('\/', '/', $value);
			$value = str_replace('\\', '/', $value);
			//windows
			//$value = str_replace('/', '\\', $value);
			#echo $value."<br>";
			#echo substr($value, -3)."<br>";
			
			$content = LcpTools::file_get_contents($value);
			$website_dir_count = LcpTools::strlen($location);
			$section_file_name = LcpTools::substr($value, $website_dir_count);
			#d($content);
			#d(LcpTools::substr($value, 3));
			if (LcpTools::substr($value, -3) == 'php') 
			{
				preg_match_all("/(lang\(')(.*)(', \$filename)/sU", $content, $patterns);
				#preg_match_all('/(lang\(")(.*)(", basename\(__FILE__\))/sU',$content,$patterns); //lang cu ghilimele duble
			} 
			elseif (LcpTools::substr($value, -3) == 'tpl' || LcpTools::substr($value, -3) == 'htm') 
			{

				preg_match_all("/({lang s=')(.*)(')/sU", $content, $patterns);
				#preg_match_all('/({lang s=")(.*)("})/sU',$content,$patterns); //lang cu ghilimele duble
				
			}

			$match[$section_file_name] = $patterns[2];
			$match[$section_file_name]['section_file_name'] = $section_file_name;
			$match[$section_file_name]['section_expressions'] = count($patterns[2]);

			////////////////////////////////////////////////////////////////////////////////////////////////////
			/*foreach($match[$section_file_name] as $key2 => $value2) {
				$match2[$section_file_name][] = $value2;
				// doar daca este numeric aici il trec in fisierul de limba
				if (is_numeric($key2)) 
					$language_file_contents .= '$_LANG[\'{'.$section_file_name.'}{'.md5($value2).'}\'] = \''.$value2.'\';'."\n";
			}*/
			////////////////////////////////////////////////////////////////////////////////////////////////////

		} //end foreach

		#d($match);
		////////////////////////////////////////////////////////////////////////////////////////////////////
		//o singura data populez fisierul de limba cu variabilele din fisiere
		/*$file_write = dirname(__FILE__)."/../translations/lang_en.php";
		$handle = fopen($file_write, 'w') or die("can't open file");
		fwrite($handle, $language_file_contents);
		fclose($handle);*/
		////////////////////////////////////////////////////////////////////////////////////////////////////
		$_LANG  = @$_LANG;
		// se verifica in fisierele de limba daca exista traducerile si la alea care exista le afisam
		foreach ($match as $key => $value) 
		{			
			foreach ($value as $key2 => $value2) 
			{
				$key3 = '{'.$value['section_file_name'].'}{'.md5($value2).'}';
				
				if (is_numeric($key2)) 
				{
					$value3 = @$_LANG[$key3];
					$match2[$value['section_file_name']][$key2]['text_from_files'] = $value2;
					$match2[$value['section_file_name']][$key2]['text_from_lang_file'] = preg_replace('#<br\s*/?>#i', '', $value3);
				} 
				else 
					$match2[$value['section_file_name']][$key2] = $value2;
			}
		}

		#d($match2);
		return $match2;
		
		#$this->smarty->assign("sections", $match2);

	}


	public function getIdByIso($iso_code)
	{
		#d($_SESSION);
		$id_lang = '';
		foreach ($_SESSION['lcp']['languages'] as $key => $value)
		{
			if ($iso_code == $value['iso_code'])
			{
				$id_lang = $value['id_lang'];
				break;
			}
		}
		return $id_lang;
	}

	public function saveTranslations($data)
	{
		$language_file_contents = '<?php '."\n";

		foreach ($data['section_file_name'] as $key => $value) 
		{
			$section_file_name          = $value;
			$section_text_from_files    = $data['section_text_from_files'][$key];

			$language_file_contents .= '$_LANG[\'{'.$section_file_name.'}{'.md5($section_text_from_files).'}\'] = \'';
			$language_file_contents .= nl2br(addslashes($data['language_variables'][$key]));
			$language_file_contents .= '\';'."\n";
		} 

		if (($handle = fopen(dirname(__FILE__).'/../translations/lang_'.$data['iso_code'].'.php', 'w')) !== false) 
		{ 
			fwrite($handle, $language_file_contents);
			fclose($handle);
		}

		return true;
	}

	public function saveTheme($data)
	{
		$is_default = ($data['id_theme'] >= 1 && $data['id_theme'] <= 2) ? true : false;
		
		if ($this->isDemo() == true && $is_default == true)
			return 'ERROR_DEMO_MODE';

		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_themes lt SET ';
		$i   = 1;
		foreach ($data as $key => $value)
		{
			$sql .= pSQL($key).' = "'.pSQL($value).'"'.',';
			$i++;
		}
		$sql = LcpTools::substr($sql, 0, -1);
		$sql .= ' WHERE lt.id_theme = "'.(int)$data['id_theme'].'"';

		LcpDb::getInstance()->Execute($sql);

		return true;
	}

	public function saveAsTheme($data)
	{
		$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_themes
        (
			name,
			width,
			height,
			corners_radius,
			chat_box_background,
			chat_box_foreground,
			chat_bubble_staff_background,
			chat_bubble_client_background
			chat_box_border,
			chat_box_border_color,
			header_offline_background,
			header_online_background,
			header_offline_foreground,
			header_online_foreground,
			submit_button_background,
			submit_button_foreground,
			is_default
		)
		VALUES
		(
			"'.pSQL($data['name']).'",
			"'.pSQL($data['width']).'",
			"'.pSQL($data['height']).'",
			"'.pSQL($data['corners_radius']).'",
			"'.pSQL($data['chat_box_background']).'",
			"'.pSQL($data['chat_box_foreground']).'",
			"'.pSQL($data['chat_bubble_staff_background']).'",
			"'.pSQL($data['chat_bubble_client_background']).'",
			"'.pSQL($data['chat_box_border']).'",
			"'.pSQL($data['chat_box_border_color']).'",
			"'.pSQL($data['header_offline_background']).'",
			"'.pSQL($data['header_online_background']).'",
			"'.pSQL($data['header_offline_foreground']).'",
			"'.pSQL($data['header_online_foreground']).'",
			"'.pSQL($data['submit_button_background']).'",
			"'.pSQL($data['submit_button_foreground']).'",
			"0"
		)';

		LcpDb::getInstance()->Execute($sql);

		return true;
	}


	public function checkIfSettingAndIsoCodeExists($data = '')
	{
		$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_settings_lang lsl WHERE id_setting = "'.(int)$data['id_setting'].'" AND iso_code = "'.pSQL($data['iso_code']).'"');

		$res = $result[0]['COUNT(*)'];

		if (empty($res))
			return false;
		else
			return true;
	}

	public function saveSettings($data)
	{
		$is_default_setting = ($data['id_setting'] >= 1 && $data['id_setting'] <= 2) ? true : false;
		
		if ($this->isDemo() == true && $is_default_setting == true)
			return 'ERROR_DEMO_MODE';

		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_settings ls SET ';
		$i   = 1;
		foreach ($data as $key => $value)
		{
			if ($key != 'offline_header_message' && $key != 'online_header_message' && $key != 'offline_welcome_message' && $key != 'online_welcome_message' && $key != 'iso_codes')
			{
				if ($key == 'code')
					$sql .= pSQL($key).' = "'.pSQL($value, true).'"'.',';
				else
					$sql .= pSQL($key).' = "'.pSQL($value).'"'.',';

				$i++;
			}
		}
		$sql = LcpTools::substr($sql, 0, -1);
		$sql .= ' WHERE ls.id_setting = "'.(int)$data['id_setting'].'"';

		LcpDb::getInstance()->Execute($sql);

		foreach ($data['iso_codes'] as $key => $value)
		{
			if ($this->checkIfSettingAndIsoCodeExists(array('id_setting' => $data['id_setting'], 'iso_code' => $value)) == true)
			{
				$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_settings_lang SET 
				offline_header_message = "'.pSQL($data['offline_header_message'][$value], true).'",
				online_header_message = "'.pSQL($data['online_header_message'][$value], true).'", 
				offline_welcome_message = "'.pSQL($data['offline_welcome_message'][$value], true).'", 
				online_welcome_message = "'.pSQL($data['online_welcome_message'][$value], true).'" 
				WHERE id_setting = "'.(int)$data['id_setting'].'" AND iso_code = "'.pSQL($value).'"';
			}
			else
			{
				$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_settings_lang
				(
					id_setting,
					id_lang,
					iso_code,
					offline_header_message,
					online_header_message,
					offline_welcome_message,
					online_welcome_message
				)
				VALUES 
				(
					"'.pSQL($data['id_setting']).'",
					"'.pSQL($this->getIdByIso($value)).'",
					"'.pSQL($value).'",
					"'.pSQL($data['offline_header_message'][$value], true).'",
					"'.pSQL($data['online_header_message'][$value], true).'",
					"'.pSQL($data['offline_welcome_message'][$value], true).'",
					"'.pSQL($data['online_welcome_message'][$value], true).'"
				)';
			}
			LcpDb::getInstance()->Execute($sql);
		}

		return true;
	}

	public function saveAsSettings($data)
	{
		$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_settings
		(
			id_iconset,
			id_theme,
			name,
			host_type,
			offline_messages_go_to,
			new_chat_sound,
			new_message_sound,

			name_field_online,
			name_field_online_mandatory,
			name_field_offline,
			name_field_offline_mandatory,

			email_field_online,
			email_field_online_mandatory,
			email_field_offline,
			email_field_offline_mandatory,

			phone_field_online,
			phone_field_online_mandatory,
			phone_field_offline,
			phone_field_offline_mandatory,

			department_field_online,
			department_field_online_mandatory,
			department_field_offline,
			department_field_offline_mandatory,

			question_field_online,
			question_field_online_mandatory,
			question_field_offline,
			question_field_offline_mandatory,

			chat_type,
			chat_type_admin,
			slide_with_image,
			orientation,
			offset,
			start_minimized,
			hide_when_offline,
			show_names,
			show_avatars,
			popup_alert_on_income_chats,
			start_new_chat_after,
			staff_qualification,
			new_chat_rings_to,
			fixed_position,
			code,
			visitors_can_upload_files,
			sync_chat_interval_backend,
			sync_chat_interval_frontend,
			realm_id,
			realm_key,
			is_default,
			is_primary
		)
		VALUES 
		(
			"'.pSQL($data['id_iconset']).'",
			"'.pSQL($data['id_theme']).'",
			"'.pSQL($data['name']).'",
			"'.pSQL($data['host_type']).'",
			"'.pSQL($data['offline_messages_go_to']).'",
			"'.pSQL($data['new_chat_sound']).'",
			"'.pSQL($data['new_message_sound']).'",

			"'.pSQL($data['name_field_online']).'",
			"'.pSQL($data['name_field_online_mandatory']).'",
			"'.pSQL($data['name_field_offline']).'",
			"'.pSQL($data['name_field_offline_mandatory']).'",

			"'.pSQL($data['email_field_online']).'",
			"'.pSQL($data['email_field_online_mandatory']).'",
			"'.pSQL($data['email_field_offline']).'",
			"'.pSQL($data['email_field_offline_mandatory']).'",

			"'.pSQL($data['phone_field_online']).'",
			"'.pSQL($data['phone_field_online_mandatory']).'",
			"'.pSQL($data['phone_field_offline']).'",
			"'.pSQL($data['phone_field_offline_mandatory']).'",

			"'.pSQL($data['department_field_online']).'",
			"'.pSQL($data['department_field_online_mandatory']).'",
			"'.pSQL($data['department_field_offline']).'",
			"'.pSQL($data['department_field_offline_mandatory']).'",

			"'.pSQL($data['question_field_online']).'",
			"'.pSQL($data['question_field_online_mandatory']).'",
			"'.pSQL($data['question_field_offline']).'",
			"'.pSQL($data['question_field_offline_mandatory']).'",

			"'.pSQL($data['chat_type']).'",
			"'.pSQL($data['chat_type_admin']).'",
			"'.pSQL($data['slide_with_image']).'",
			"'.pSQL($data['orientation']).'",
			"'.pSQL($data['offset']).'",
			"'.pSQL($data['start_minimized']).'",
			"'.pSQL($data['hide_when_offline']).'",
			"'.pSQL($data['show_names']).'",
			"'.pSQL($data['show_avatars']).'",
			"'.pSQL($data['popup_alert_on_income_chats']).'",
			"'.pSQL(@$data['start_new_chat_after']).'",
			"'.pSQL($data['staff_qualification']).'",
			"'.pSQL($data['new_chat_rings_to']).'",
			"'.pSQL($data['fixed_position']).'",
			"'.pSQL(@$data['code'], true).'",
			"'.pSQL($data['visitors_can_upload_files']).'",
			"'.pSQL($data['sync_chat_interval_backend']).'",
			"'.pSQL($data['sync_chat_interval_frontend']).'",
			"'.pSQL($data['realm_id']).'",
			"'.pSQL($data['realm_key']).'",
			"0",
			"0"
		)';

		LcpDb::getInstance()->Execute($sql);

		//get id_setting
		$id_setting = $this->getLastSettingId();

		//si lang
		foreach ($data['iso_codes'] as $value)
		{
			$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_settings_lang
			(
				id_setting,
				id_lang,
				iso_code,
				offline_header_message,
				online_header_message,
				offline_welcome_message,
				online_welcome_message
			)
			VALUES 
			(
				"'.pSQL($id_setting).'",
				"'.pSQL($this->getIdByIso($value)).'",
				"'.pSQL($value).'",
				"'.pSQL($data['offline_header_message'][$value], true).'",
				"'.pSQL($data['online_header_message'][$value], true).'",
				"'.pSQL($data['offline_welcome_message'][$value], true).'",
				"'.pSQL($data['online_welcome_message'][$value], true).'"
			)';
			
			LcpDb::getInstance()->Execute($sql);
		}

		return true;
	}

	public function getLastSettingId()
	{
		$sql = 'SELECT id_setting FROM '._DB_PREFIX_.$this->name.'_settings ORDER BY id_setting DESC LIMIT 1';

		$result = LcpDb::getInstance()->executeS($sql);

		if ($result)
			return $result[0]['id_setting'];
		else
			return 0;
	}	

	public function getLastIconsetId()
	{
		$sql = 'SELECT id_iconset FROM '._DB_PREFIX_.$this->name.'_iconsets ORDER BY id_iconset DESC LIMIT 1';

		$result = LcpDb::getInstance()->executeS($sql);

		if ($result)
			return $result[0]['id_iconset'];
		else
			return 0;
	}


	public function deleteSettings($data)
	{
		// nu stergem defaulturile
		if ($this->isDemo() == true)
			return 'ERROR_DEMO_MODE';

		LcpDb::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.$this->name.'_settings WHERE id_setting = "'.(int)$data['id_setting'].'"');
		LcpDb::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.$this->name.'_settings_lang WHERE id_setting = "'.(int)$data['id_setting'].'"');

		return true;
	}

	public function showSettingsDropdown()
	{
		$settings       = $this->getSettings();
		$this->smarty->assign(array(
			'settings' => $settings,
		));
		return $this->display(dirname(__FILE__).'/../views/templates/admin/ajax.settings.tpl');
	}

	public function showIconsetsDropdown()
	{
		$iconsets       = $this->getIconsets();
		$this->smarty->assign(array(
			'iconsets' => $iconsets,
		));
		return $this->display(dirname(__FILE__).'/../views/templates/admin/ajax.iconsets.tpl');
	}

	public function isDemo()
	{
		$is_demo = (stristr($_SERVER['SERVER_NAME'], 'prestashopaddonsmodules.com') || stristr($_SERVER['SERVER_NAME'], '4prestashop.com')) ? true : false;
		//$is_demo = ( stristr($_SERVER['SERVER_NAME'], 'prestashopaddonsmodules.com') || stristr($_SERVER['SERVER_NAME'], '4prestashop.com') ) ? false : true;
		
		return $is_demo;
	}

	public function deleteIconset($data)
	{
		if ($this->isDemo() == true)
			return 'ERROR_DEMO_MODE';		

		$iconset = $this->getIconset($data);

		foreach ($this->lcp_session['languages'] as $value)
		{
			$offline_img_file = dirname(__FILE__).'/../views/img/iconsets/'.$iconset['offline_img'][$value['iso_code']];
			$online_img_file  = dirname(__FILE__).'/../views/img/iconsets/'.$iconset['online_img'][$value['iso_code']];

			//sterg iconitele
			@unlink($offline_img_file);
			@unlink($online_img_file);
		}

		LcpDb::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.$this->name.'_iconsets WHERE id_iconset = "'.(int)$data['id_iconset'].'"');
		LcpDb::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.$this->name.'_iconsets_lang WHERE id_iconset = "'.(int)$data['id_iconset'].'"');

		return true;
	}


	public function checkIfIconsetAndIsoCodeExists($data = '')
	{
		$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_iconsets_lang lil WHERE id_iconset = "'.(int)$data['id_iconset'].'" AND iso_code = "'.pSQL($data['iso_code']).'"');

		$res = $result[0]['COUNT(*)'];

		if (empty($res))
			return false;
		else
			return true;
	}


	public function saveIconset($data)
	{
		$is_default = ($data['id_iconset'] >= 1 && $data['id_iconset'] <= 2) ? true : false;
		
		if ($this->isDemo() == true && $is_default == true)
			return 'ERROR_DEMO_MODE';

		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_iconsets li SET ';
		$i   = 1;
		foreach ($data as $key => $value)
		{
			if ($key != 'offline_img' && $key != 'online_img' && $key != 'iso_codes')
			{
				$sql .= pSQL($key).' = "'.pSQL($value).'"'.',';
				$i++;
			}
		}
		$sql = LcpTools::substr($sql, 0, -1);
		$sql .= ' WHERE li.id_iconset = "'.(int)$data['id_iconset'].'"';

		LcpDb::getInstance()->Execute($sql);

		foreach ($data['iso_codes'] as $key => $value)
		{
			if ($this->checkIfIconsetAndIsoCodeExists(array('id_iconset' => $data['id_iconset'], 'iso_code' => $value)) == true)
			{
				$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_iconsets_lang SET 
				offline_img = "'.pSQL($data['offline_img'][$value]).'",
				online_img = "'.pSQL($data['online_img'][$value]).'" 
				WHERE id_iconset = "'.(int)$data['id_iconset'].'" AND iso_code = "'.pSQL($value).'"';
			}
			else
			{
				$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_iconsets_lang
				(
					id_iconset,
					id_lang,
					iso_code,
					offline_img,
					online_img
				)
				VALUES 
				(
					"'.pSQL($data['id_iconset']).'",
					"'.pSQL($this->getIdByIso($value)).'",
					"'.pSQL($value).'",
					"'.pSQL($data['offline_img'][$value]).'",
					"'.pSQL($data['online_img'][$value]).'"
				)';
			}
			LcpDb::getInstance()->Execute($sql);
		}


		return true;
	}


	public function saveAsIconset($data)
	{
		$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_iconsets
		(
			name,
			is_default
		)
		VALUES 
		(
			"'.pSQL($data['name']).'",
			"0"
		)';

		LcpDb::getInstance()->Execute($sql);

		//get id_setting
		$id_iconset = $this->getLastIconsetId();

		//si lang
		foreach ($data['iso_codes'] as $value)
		{
			if (!empty($data['offline_img'][$value]))
			{
				//verific daca exista deja fisierele cu numele astea si daca exista fac o copie la ele cu alt nume (uniqid)
				$filename1 = dirname(__FILE__).'/../views/img/iconsets/'.$data['offline_img'][$value];

				if (file_exists($filename1))
				{
					$uniqid1               = uniqid();
					$pathinfo_offline_img  = pathinfo($data['offline_img'][$value]);
					$offline_img_extension = $pathinfo_offline_img['extension'];
					$data['offline_img'][$value] = $uniqid1.'.'.$offline_img_extension;
					$filename1_new = dirname(__FILE__).'/../views/img/iconsets/'.$data['offline_img'][$value];
					call_user_func('copy', $filename1, $filename1_new);
				}
			}

			if (!empty($data['online_img'][$value]))
			{
				$filename2 = dirname(__FILE__).'/../views/img/iconsets/'.$data['online_img'][$value];

				if (file_exists($filename2))
				{
					$uniqid2              = uniqid();
					$pathinfo_online_img  = pathinfo($data['online_img'][$value]);
					$online_img_extension = $pathinfo_online_img['extension'];
					$data['online_img'][$value] = $uniqid2.'.'.$online_img_extension;
					$filename2_new = dirname(__FILE__).'/../views/img/iconsets/'.$data['online_img'][$value];
					call_user_func('copy', $filename2, $filename2_new);
				}
			}

			$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_iconsets_lang
			(
				id_iconset,
				id_lang,
				iso_code,
				offline_img,
				online_img
			)
			VALUES 
			(
				"'.pSQL($id_iconset).'",
				"'.pSQL($this->getIdByIso($value)).'",
				"'.pSQL($value).'",
				"'.pSQL($data['offline_img'][$value]).'",
				"'.pSQL($data['online_img'][$value]).'"
			)';
			
			LcpDb::getInstance()->Execute($sql);
		}

		//@ob_end_clean();

		return true;
	}


	public function deleteStaffProfile($data)
	{
		$sql = 'DELETE FROM '._DB_PREFIX_.$this->name.'_staffprofiles WHERE id_staffprofile = "'.(int)$data['id_staffprofile'].'"';
		LcpDb::getInstance()->Execute($sql);

		return true;
	}

	public function deleteArchive($data)
	{
		$sql = 'DELETE FROM '._DB_PREFIX_.$this->name.'_archive WHERE id_archive = "'.(int)$data['id_archive'].'"';
		LcpDb::getInstance()->Execute($sql);

		return true;
	}

	public function deleteMessage($data)
	{
		$sql = 'DELETE FROM '._DB_PREFIX_.$this->name.'_messages WHERE id_message = "'.(int)$data['id_message'].'"';
		LcpDb::getInstance()->Execute($sql);

		return true;
	}

	public function deleteRating($data)
	{
		$sql = 'DELETE FROM '._DB_PREFIX_.$this->name.'_ratings WHERE id_rating = "'.(int)$data['id_rating'].'"';
		LcpDb::getInstance()->Execute($sql);

		return true;
	}

	public function updateMessage($data)
	{
		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_messages lm SET ';
		$i   = 1;
		foreach ($data as $key => $value)
		{
			$sql .= pSQL($key).' = "'.pSQL($value).'"'.',';
			$i++;
		}
		$sql = LcpTools::substr($sql, 0, -1);
		$sql .= ' WHERE id_message = "'.(int)$data['id_message'].'"';

		LcpDb::getInstance()->Execute($sql);

		return true;
	}

	public function updateTicket($data)
	{
		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_tickets lt SET ';
		$i   = 1;
		foreach ($data as $key => $value)
		{
			$sql .= pSQL($key).' = "'.pSQL($value).'"'.',';
			$i++;
		}
		$sql = LcpTools::substr($sql, 0, -1);
		$sql .= ' WHERE id_ticket = "'.(int)$data['id_ticket'].'"';

		LcpDb::getInstance()->Execute($sql);

		return true;
	}	

	public function updateRating($data)
	{
		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_ratings lr SET ';
		$i   = 1;
		foreach ($data as $key => $value)
		{
			$sql .= pSQL($key).' = "'.pSQL($value).'"'.',';
			$i++;
		}
		$sql = LcpTools::substr($sql, 0, -1);
		$sql .= ' WHERE id_rating = "'.(int)$data['id_rating'].'"';

		LcpDb::getInstance()->Execute($sql);

		return true;

	}

	public function getPSConfiguration()
	{
		$sql = 'SELECT * FROM '._DB_PREFIX_.'configuration';
		$configuration = LcpDb::getInstance()->executeS($sql);	

		$arr_info = array();
		foreach ($configuration as $value)
			$arr_info[$value['name']] = $value['value'];

		return $arr_info;
	}

	public function replaceParams($file_path, $params = '')
	{
		$file_content = LcpTools::file_get_contents($file_path);
	
		foreach ($params as $key => $value)
			$file_content = str_replace($key, $value, $file_content);

		return $file_content;
	}




	public function addTicketReply($data = '')
	{
		$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_ticketsreplyes 
				(
					id_ticket,
					id_staffprofile,
					id_customer,
					reply_from,
					message,
					date_add
				) 
				VALUES 
				(
					"'.pSQL($data['id_ticket']).'",
					"'.pSQL($data['id_staffprofile']).'",
					"'.pSQL($data['id_customer']).'",
					"'.pSQL($data['reply_from']).'",
					"'.pSQL($data['message']).'",
					DATE_FORMAT(NOW(),\'%Y-%m-%d %H:%i:%s\')
                )';

		LcpDb::getInstance()->execute($sql);

		// setam statusl in Answered cand e vorba de client si Customer-Reply cand vine de la client
		if ($data['reply_from'] == 'Staff')
			$this->updateTicket(array('id_ticket' => $data['id_ticket'], 'status' => 'Answered'));
		else
			$this->updateTicket(array('id_ticket' => $data['id_ticket'], 'status' => 'Customer-Reply'));

		$ticket_details = $this->getTicket(array('id_ticket' => $data['id_ticket']));

		#$link = new Link();
		#$ticket_details_link = $link->getPageLink('my-account', true).'?ticket_details='.$ticket_details['id_ticket'];
		$ticket_details_link = '';

		//send email
		$params = array(
			'{staff}' => $ticket_details['staff_name'],
			'{customer}' => $ticket_details['customer_name'],
			'{department}' => $ticket_details['department_name'],
			'{subject}' => $ticket_details['subject'],
			'{priority}' => $ticket_details['priority'],
			'{status}' => $ticket_details['status'],
			'{message}' => $data['message'],
			'{ticket_details_link}' => @$ticket_details_link,
		);

		if ($data['reply_from'] == 'Staff')
		{
			$from_address = $ticket_details['staff_email'];
			$to_address = $ticket_details['customer_email'];

			// ----------------------------------------------------------------------------------------------------------
			$ps_configuration = $this->getPSConfiguration();

			require_once dirname(__FILE__).'/../libraries/phpmailer/PHPMailerAutoload.php';
			$mail = new PHPMailer;

			//$mail->SMTPDebug = 3; 
			//is_SMTP
			if ($ps_configuration['PS_MAIL_METHOD'] == 2)
			{
				$mail->isSMTP();
				$mail->Host = $ps_configuration['PS_MAIL_SERVER'];
				$mail->SMTPAuth = true;
				$mail->Username = $ps_configuration['PS_MAIL_USER'];
				$mail->Password = $ps_configuration['PS_MAIL_PASSWD'];
				if (!empty($ps_configuration['PS_MAIL_SMTP_ENCRYPTION']) && $ps_configuration['PS_MAIL_SMTP_ENCRYPTION'] != 'off')
					$mail->SMTPSecure = LcpTools::strtolower($ps_configuration['PS_MAIL_SMTP_ENCRYPTION']);
				$mail->Port = $ps_configuration['PS_MAIL_SMTP_PORT'];
			}			
			$mail->setFrom($from_address, '');
			$mail->addAddress($to_address, '');
			$mail->isHTML(true);
			$mail->Subject = $this->l('Livechat new ticket reply from:').' '.$data['reply_from'];
			#$mail->msgHTML(file_get_contents('/../mails/'.$this->lcp_session['employee_id_lang'].'/lcp_ticket_reply.html'), dirname(__FILE__));
			$mail->Body    = $this->replaceParams(dirname(__FILE__).'/../mails/'.$this->lcp_session['employee_iso_code'].'/lcp_ticket_reply.html', $params);
			#$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			if (!$mail->send()) 
			{
				return false;
				#echo 'Message could not be sent: '.$mail->ErrorInfo;
			} 
			else 
			{
				return true;
				#echo 'Message has been sent';
			}
			// -------------		
		}
		else
		{
			$from_address = $ticket_details['customer_email'];
			$to_address = $ticket_details['staff_email'];
		}

		return true;
	}

	public function addRating($data = '')
	{
		$archive_details = $this->getArchive(array('id_archive' => $data['id_archive']));

		$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_ratings 
				(
					id_archive,
					id_staffprofile,
					id_visitor,
					status,
					internal,
					politness,
					qualification,
					date_add,
					name,
					email,
					company,
					comment
				) 
				VALUES 
				(
					"'.pSQL($archive_details['id_archive']).'",
					"'.pSQL($archive_details['id_staffprofile']).'",
					"'.pSQL($archive_details['id_visitor']).'",
					"Unread",
					"'.pSQL($archive_details['internal']).'",
					"'.pSQL($data['stars']).'",
					"'.pSQL($data['stars']).'",
					DATE_FORMAT(NOW(),\'%Y-%m-%d %H:%i:%s\'),
					"'.pSQL($archive_details['name']).'",
					"'.pSQL($archive_details['email']).'",
					"'.pSQL($archive_details['company']).'",
					"'.pSQL($data['comment']).'"
                )';

		LcpDb::getInstance()->execute($sql);

		return true;
	}

	/*public function updateStaffProfile($data)
	{
		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_staffprofiles lsp
			SET avatar = "'.pSQL($data['avatar']).'",
				departments = "'.pSQL($data['departments']).'",
				welcome_message = "'.pSQL($data['welcome_message'], true).'",
				signature = "'.pSQL($data['signature'], true).'",
				is_active = "'.pSQL($data['is_active']).'"
			WHERE id_staffprofile = "'.(int)$data['id_staffprofile'].'"';

		LcpDb::getInstance()->Execute($sql);

		return true;

	}*/

	public function addStaffProfile($data)
	{
		$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_staffprofiles 
				(
					id_employee,
					avatar,
					departments,
					welcome_message,
					signature,
					is_active
				) 
				VALUES 
				(
					"'.(int)$data['id_employee'].'",
					"'.pSQL($data['avatar']).'",
					"'.pSQL($data['departments']).'",
					"'.pSQL($data['welcome_message'], true).'",
					"'.pSQL($data['signature'], true).'",
					"'.pSQL($data['is_active']).'"
                )';

		LcpDb::getInstance()->execute($sql);

		return true;
	}


	public function replyToMessage($data)
	{
		$primary_settings = $this->getPrimarySettings();

		$employee = $this->getEmployeeById($this->lcp_session['id_employee']);

		//make mark as read message
		$this->updateMessage(array(
			'id_message' => $data['id_message'],
			'status' => 'Read',
		));

		$message = $this->getMessage(array('id_message' => $data['id_message']));

		$params = array(
			'{name}' => $employee['firstname'].' '.$employee['lastname'],
			'{from_email}' => $primary_settings['offline_messages_go_to'], // shop email address
			'{to_email}' => $message['email'],
			'{question}' => $message['question'],
			'{message}' => $data['messages_reply'],
		);
		
			// ----------------------------------------------------------------------------------------------------------
			$ps_configuration = $this->getPSConfiguration();

			require_once dirname(__FILE__).'/../libraries/phpmailer/PHPMailerAutoload.php';
			$mail = new PHPMailer;

			//$mail->SMTPDebug = 3; 
			//is_SMTP
			if ($ps_configuration['PS_MAIL_METHOD'] == 2)
			{
				$mail->isSMTP();
				$mail->Host = $ps_configuration['PS_MAIL_SERVER'];
				$mail->SMTPAuth = true;
				$mail->Username = $ps_configuration['PS_MAIL_USER'];
				$mail->Password = $ps_configuration['PS_MAIL_PASSWD'];
				if (!empty($ps_configuration['PS_MAIL_SMTP_ENCRYPTION']) && $ps_configuration['PS_MAIL_SMTP_ENCRYPTION'] != 'off')
					$mail->SMTPSecure = LcpTools::strtolower($ps_configuration['PS_MAIL_SMTP_ENCRYPTION']);
				$mail->Port = $ps_configuration['PS_MAIL_SMTP_PORT'];
			}			
			$mail->setFrom($primary_settings['offline_messages_go_to'], '');
			$mail->addAddress($message['email'], '');
			$mail->isHTML(true);
			$mail->Subject = $this->l('Livechat reply to your message');
			#$mail->msgHTML(file_get_contents('/../mails/'.$this->lcp_session['employee_id_lang'].'/lcp_ticket_reply.html'), dirname(__FILE__));
			$mail->Body    = $this->replaceParams(dirname(__FILE__).'/../mails/'.$this->lcp_session['employee_iso_code'].'/lcp_reply.html', $params);
			#$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			if (!$mail->send()) 
			{
				return false;
				#echo 'Message could not be sent: '.$mail->ErrorInfo;
			} 
			else 
			{
				return true;
				#echo 'Message has been sent';
			}
			// -------------

		//@ob_end_clean();

		return true;
	}


	public function clearDatabase()
	{
		$sql_array = '';
		$sql_execution = '';

		$sql_array[] = 'DELETE FROM `'._DB_PREFIX_.$this->name.'_archive`;';
		$sql_array[] = 'DELETE FROM `'._DB_PREFIX_.$this->name.'_logs`;';
		$sql_array[] = 'DELETE FROM `'._DB_PREFIX_.$this->name.'_messages`;';
		$sql_array[] = 'DELETE FROM `'._DB_PREFIX_.$this->name.'_ratings`;';

		foreach ($sql_array as $sql)
			$sql_execution[] = LcpDb::getInstance()->Execute($sql);	

		return true;
	}

	public function addMessage($data)
	{
		$sql = 'INSERT
		   INTO '._DB_PREFIX_.$this->name.'_messages (
														id_visitor,
														id_department,
														date_add,
														status,
														name,
														email,
														phone,
														department,
														question,
														ip
		   											 ) 
													VALUES 
													(
														"'.pSQL($data['id_visitor']).'",
														"'.pSQL(@$data['id_department']).'",
   											 			DATE_FORMAT(NOW(),\'%Y-%m-%d %H:%i:%s\'),
   											 			"Unread",
   											 			"'.pSQL($data['name']).'",
   											 			"'.pSQL($data['email']).'",
   											 			"'.pSQL($data['phone']).'",
   											 			"'.pSQL(@$data['department']).'",
   											 			"'.pSQL($data['question']).'",
   											 			"'.pSQL(LcpTools::getRemoteAddr()).'"
								   					)';

		LcpDb::getInstance()->execute($sql);

		//sending the email
		$primary_settings = $this->getPrimarySettings();

			$params = array(
				'{name}' => $data['name'],
				'{email}' => $data['email'],
				'{phone}' => $data['phone'],
				'{department}' => $data['department'],
				'{question}' => iconv('UTF-8', 'ISO-8859-1', $data['question']),
				'{current_url}' => $data['current_url'],
				'{ip_address}' => LcpTools::getRemoteAddr(),
			);

			// ----------------------------------------------------------------------------------------------------------
			$ps_configuration = $this->getPSConfiguration();

			require_once dirname(__FILE__).'/../libraries/phpmailer/PHPMailerAutoload.php';
			$mail = new PHPMailer;

			//$mail->SMTPDebug = 3; 
			//is_SMTP
			if ($ps_configuration['PS_MAIL_METHOD'] == 2)
			{
				$mail->isSMTP();
				$mail->Host = $ps_configuration['PS_MAIL_SERVER'];
				$mail->SMTPAuth = true;
				$mail->Username = $ps_configuration['PS_MAIL_USER'];
				$mail->Password = $ps_configuration['PS_MAIL_PASSWD'];
				if (!empty($ps_configuration['PS_MAIL_SMTP_ENCRYPTION']) && $ps_configuration['PS_MAIL_SMTP_ENCRYPTION'] != 'off')
					$mail->SMTPSecure = LcpTools::strtolower($ps_configuration['PS_MAIL_SMTP_ENCRYPTION']);
				$mail->Port = $ps_configuration['PS_MAIL_SMTP_PORT'];
			}			
			$mail->setFrom($data['email'], '');
			$mail->addAddress($primary_settings['offline_messages_go_to'], '');
			$mail->isHTML(true);
			$mail->Subject = $this->l('Livechat offline message from:').' '.$data['email'];
			#$mail->msgHTML(file_get_contents('/../mails/'.$this->lcp_session['employee_id_lang'].'/lcp_ticket_reply.html'), dirname(__FILE__));
			$mail->Body    = $this->replaceParams(dirname(__FILE__).'/../mails/'.$this->lcp_session['employee_iso_code'].'/lcp_offline_message.html', $params);
			#$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			if (!$mail->send()) 
			{
				return false;
				#echo 'Message could not be sent: '.$mail->ErrorInfo;
			} 
			else 
			{
				return true;
				#echo 'Message has been sent';
			}
			// ---------------------------

		//@ob_end_clean();

		return true;

	}

	public function addVisitedPage($data)
	{
		$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_visitedpages 
				(
					id_visitor,
					date_add,
					duration,
					url,
					referrer
				) 
				VALUES 
				(
					"'.pSQL($data['id_visitor']).'",
					DATE_FORMAT(NOW(),\'%Y-%m-%d %H:%i:%s\'),
					"00:00:10",
					"'.pSQL($data['url']).'",
					"'.pSQL($data['referrer']).'"
				)';

		LcpDb::getInstance()->execute($sql);

		return true;
	}


	public function addUpdateArchive($data = '')
	{
		if (empty($data['id_employee']))
			$data['id_employee'] = $this->getEmployeeIdByStaffProfile($data['id_staffprofile']);

		$employee_details = $this->getEmployeeById($data['id_employee']);

		if ($this->checkIfVisitorIdInArchive($data['id_visitor']) == false)
		{
			$data['id_department']                = ifsetor($data['id_department'], 1);
			$data['internal']                     = $employee_details['firstname'].' '.$employee_details['lastname'];
			$data['name']                         = ifsetor($data['name'], '');
			$data['email']                        = ifsetor($data['email'], '');
			$data['phone']                        = ifsetor($data['phone'], '');
			$data['company']                      = ifsetor($data['company'], '');
			$data['duration']                     = '0';
			$data['messages']                     = $data['messages'];
			$data['last_message']                 = $data['messages'];
			$data['log_entries']                  = '0';
			$this->addArchive($data);
		}
		else
		{
			$data['internal'] = $employee_details['firstname'].' '.$employee_details['lastname'];
			$this->updateArchive($data);
		}		

		return true;
	}


	public function addArchive($data = '')
	{
		$visitor_details = $this->getVisitor(array('id_visitor' => $data['id_visitor']));

		$id_chat = $this->getLastChatId() + 1;
		// 1) --------------------------------------------------------------------------------
		$sql1 = 'INSERT INTO '._DB_PREFIX_.$this->name.'_archive
			(
				id_chat,
				id_visitor,
				id_staffprofile,
				id_department,
				action,
				internal,
				date_add,
				in_chat,
				chat_request_from,
				awaiting_response_from_staff,
				name,
				email,
				phone,
				company,
				language,
				country,
				ip,
				host,
				duration,
				messages,
				last_message,
				log_entries,
				is_archive
			) 
			VALUES 
			(
				"'.(int)$id_chat.'",
				"'.pSQL($data['id_visitor']).'",
				"'.(int)$data['id_staffprofile'].'",
				"'.pSQL($data['id_department']).'",
				"'.pSQL($data['action']).'",
				"'.pSQL($data['internal']).'",
				DATE_FORMAT(NOW(),\'%Y-%m-%d %H:%i:%s\'),
				"'.pSQL($data['in_chat']).'",
				"'.pSQL($data['chat_request_from']).'",
				"'.pSQL($data['awaiting_response_from_staff']).'",
				"'.pSQL($data['name']).'",
				"'.pSQL($data['email']).'",
				"'.pSQL($data['phone']).'",
				"'.pSQL($data['company']).'",
				"'.pSQL($visitor_details['language']).'",
				"'.pSQL($visitor_details['country']).'",
				"'.pSQL($visitor_details['ip']).'",
				"'.pSQL($visitor_details['host']).'",
				"'.pSQL($data['duration']).'",
				"'.pSQL($data['messages'], true).'",
				"'.pSQL($data['last_message']).'",
				"'.pSQL($data['log_entries']).'",
				"'.pSQL($data['is_archive']).'"
			)';

		#d($sql1);

		@LcpDb::getInstance()->execute($sql1);
	}



	public function addVisitor($data)
	{
		$ip = (LcpTools::getRemoteAddr() == '::1') ? '127.0.0.1' : LcpTools::getRemoteAddr();

		$sql = 'INSERT INTO '._DB_PREFIX_.$this->name.'_onlinevisitors 
			(
				session_id,
				country,
				city,
				province,
				language,
				visits,
				current_page,
				host,
				ip,
				browser,
				timezone,
				resolution,
				online_time,
				referrer,
				page_count,
				os,
				last_visit
            ) 
			VALUES 
			(
				"'.pSQL(@$data['session_id']).'",
				"",
				"",
				"",
				"'.pSQL(@$data['language']).'",
				"0",
				"'.pSQL(@$data['current_page']).'",
				"'.pSQL(@$_SERVER['HTTP_HOST']).'",
				"'.pSQL(@$ip).'",
				"'.pSQL(@$data['browser']).'",
				"GMT '.pSQL(@$data['timezone']).':00",
				"'.pSQL(@$data['resolution']).'",
				"00:00:10",
				"'.pSQL(@$data['referrer']).'",
				"0",
				"'.pSQL(@$data['os']).'",
				DATE_FORMAT(NOW(),\'%Y-%m-%d %H:%i:%s\') 
            )';
		
		LcpDb::getInstance()->execute($sql);

		$id_visitor = $this->getLastVisitorId();

		$tracking_info = $this->getGeoTracking();

		//update geotracking
		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_onlinevisitors
			SET country = "'.pSQL($tracking_info['country']).'",
			city = "'.pSQL($tracking_info['city']).'"
		WHERE id_visitor = "'.pSQL($id_visitor).'"';

		LcpDb::getInstance()->execute($sql);
		
		return $id_visitor;
	}

	public function updateArchive($data)
	{
		$sql = 'UPDATE `'._DB_PREFIX_.$this->name.'_archive` la SET ';
		$i   = 1;
		foreach ($data as $key => $value)
		{
			if ($key != 'id_employee')
			{
				$sql .= pSQL($key).' = "'.pSQL($value).'"'.',';
				$i++;
			}
		}
		$sql = LcpTools::substr($sql, 0, -1);
		$sql .= ' WHERE id_visitor = "'.pSQL($data['id_visitor']).'" AND is_archive != "Y"';

		LcpDb::getInstance()->Execute($sql);

		return true;
	}


	public function updateVisitor($data)
	{
		$sql = 'UPDATE `'._DB_PREFIX_.$this->name.'_onlinevisitors` SET ';
		$i   = 1;
		foreach ($data as $key => $value)
		{
			$sql .= pSQL($key).' = "'.pSQL($value).'"'.',';
			$i++;
		}
		$sql = LcpTools::substr($sql, 0, -1);
		$sql .= ' WHERE session_id = "'.pSQL($data['session_id']).'"';

		LcpDb::getInstance()->Execute($sql);

		return true;
	}

	public function getLastVisitorId()
	{
		$sql = 'SELECT id_visitor
			FROM '._DB_PREFIX_.$this->name.'_onlinevisitors
			ORDER BY id_visitor DESC LIMIT 1';

		$result = LcpDb::getInstance()->executeS($sql);

		if ($result)
			return $result[0]['id_visitor'];
		else
			return 0;
	}

	public function getLastChatId()
	{
		$sql = 'SELECT id_chat FROM '._DB_PREFIX_.$this->name.'_archive ORDER BY id_archive DESC LIMIT 1';

		$result = LcpDb::getInstance()->executeS($sql);

		if ($result)
			return $result[0]['id_chat'];
		else
			return 0;
	}

	public function chatAcceptedFromClient($data = '')
	{
		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_archive
          SET
          action = "chatAcceptedFromClient",
          awaiting_response_from_staff = "Y",
          in_chat = "Y",
          is_archive = "N" 
          WHERE id_visitor = "'.pSQL($data['id_visitor']).'" AND is_archive != "Y"';

		LcpDb::getInstance()->execute($sql);

		$sql = 'SELECT * FROM '._DB_PREFIX_.$this->name.'_archive WHERE id_visitor = "'.pSQL($data['id_visitor']).'"';

		$archive_details = LcpDb::getInstance()->executeS($sql);
		$archive_details = $archive_details[0];

		$data['id_staffprofile'] = $archive_details['id_staffprofile'];
		$data['id_visitor'] = $data['id_visitor'];
		$data['type'] = 'External';

		$this->addOnlineUser($data);

		@$this->addLog(array(
			'id_staffprofile' => $archive_details['id_staffprofile'],
			'id_visitor' => $data['id_visitor'],
			'message' => 'Chat-Request accepted by (Client).'
		));
		
		#$this->id_staffprofile = $archive_details['id_staffprofile'];

		return $archive_details;
	}


	public function chatAcceptedFromStaff($data = '')
	{
		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_archive
          SET
          id_staffprofile = "'.(int)$this->id_staffprofile.'",
          action = "chatAcceptedFromStaff",
          awaiting_response_from_staff = "Y",
          internal = "'.pSQL($data['internal']).'",
          in_chat = "Y",
          is_archive = "N" 
          WHERE id_visitor = "'.pSQL($data['id_visitor']).'" AND is_archive != "Y"';

		LcpDb::getInstance()->execute($sql);

		$data['id_staffprofile'] = $this->id_staffprofile;
		$data['id_visitor'] = $data['id_visitor'];
		$data['type'] = 'External';

		$this->addOnlineUser($data);

		@$this->addLog(array(
			'id_staffprofile' => $data['id_staffprofile'],
			'id_visitor' => $data['id_visitor'],
			'message' => 'Chat-Request accepted by (Staff).'
		));
		return true;
	}

	public function chatDeniedFromClient($data = '')
	{
		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_archive
			SET
				action = "chatDeniedFromClient",
				awaiting_response_from_staff = "N",
				in_chat = "N",
				is_archive = "Y" 
			WHERE id_visitor = "'.pSQL($data['id_visitor']).'" AND is_archive != "Y"';

		LcpDb::getInstance()->execute($sql);

		// get data from archive
		$sql = 'SELECT * FROM '._DB_PREFIX_.$this->name.'_archive WHERE id_visitor = "'.pSQL($data['id_visitor']).'"';

		$archive_details = LcpDb::getInstance()->executeS($sql); 

		$archive_details = $archive_details[0];

		$sql = 'DELETE FROM '._DB_PREFIX_.$this->name.'_onlineusers WHERE id_visitor = "'.pSQL($data['id_visitor']).'"';
		LcpDb::getInstance()->Execute($sql);

		@$this->addLog(array(
			'id_staffprofile' => $archive_details['id_staffprofile'],
			'id_visitor' => $data['id_visitor'],
			'message' => 'Chat-Request denied by (Client).'
		));

		#$this->id_staffprofile = $archive_details['id_staffprofile'];

		return $archive_details;

	}



	public function chatClosedFromStaff($data = '')
	{
		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_archive
			SET
				id_staffprofile = "'.(int)$this->id_staffprofile.'",
				action = "chatClosedFromStaff",
				awaiting_response_from_staff = "N",
				internal = "'.pSQL($data['internal']).'",
				in_chat = "N",
				is_archive = "Y" 
			WHERE id_visitor = "'.pSQL($data['id_visitor']).'" AND is_archive != "Y"';

		LcpDb::getInstance()->execute($sql);

		// get data from archive
		$sql = 'SELECT * FROM '._DB_PREFIX_.$this->name.'_archive WHERE  id_visitor = "'.pSQL($data['id_visitor']).'"';

		$archive_details = LcpDb::getInstance()->executeS($sql); 
		$archive_details = $archive_details[0];

		if (LcpTools::substr($data['id_visitor'], 0, 1) !== 'i')
		{
			$sql = 'DELETE FROM '._DB_PREFIX_.$this->name.'_onlineusers WHERE id_visitor = "'.pSQL($data['id_visitor']).'"';
			LcpDb::getInstance()->Execute($sql);
		}

		@$this->addLog(array(
			'id_staffprofile' => $archive_details['id_staffprofile'],
			'id_visitor' => $data['id_visitor'],
			'message' => 'Chat-Request closed by (Staff).'
		));

	}


	public function chatClosedFromClient($data = '')
	{
		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_archive
			SET
				in_chat = "N",
				action = "chatClosedFromClient",
				awaiting_response_from_staff = "N",
				is_archive = "Y" 
			WHERE id_visitor = "'.pSQL($data['id_visitor']).'" AND is_archive != "Y"';

		LcpDb::getInstance()->execute($sql);

		// get data from archive
		$sql = 'SELECT * FROM '._DB_PREFIX_.$this->name.'_archive WHERE id_visitor = "'.pSQL($data['id_visitor']).'"';
		$archive_details = LcpDb::getInstance()->executeS($sql); 
		$archive_details = $archive_details[0];

		if (LcpTools::substr($data['id_visitor'], 0, 1) !== 'i')
		{
			$sql = 'DELETE FROM '._DB_PREFIX_.$this->name.'_onlineusers WHERE id_visitor = "'.pSQL($data['id_visitor']).'"';
			LcpDb::getInstance()->Execute($sql);
		}

		@$this->addLog(array(
			'id_staffprofile' => $archive_details['id_staffprofile'],
			'id_visitor' => $data['id_visitor'],
			'message' => 'Chat-Request closed by '.pSQL($archive_details['name']).' (Client).'
		));

		#$this->id_staffprofile = $archive_details['id_staffprofile'];

		return $archive_details;
	}
	
	public function chatRequestFromStaff($data = '')
	{
		$data['action'] = 'chatRequestFromStaff';
		$data['in_chat'] = 'P';
		$data['chat_request_from'] = 'Staff';
		$data['awaiting_response_from_staff'] = 'N';
		$data['messages'] = $this->l('Chat invitation from staff');
		$data['is_archive'] = 'N';
		$data['id_staffprofile'] = $this->id_staffprofile;

		$this->addUpdateArchive($data);

		@$this->addLog(array(
			'id_staffprofile' => $this->id_staffprofile,
			'id_visitor' => $data['id_visitor'],
			'message' => 'Chat-Request from (Staff).'
		));
	}


	public function chatRequestFromStaffToStaff($data = '')
	{
		$data['action'] = 'chatRequestFromStaffToStaff';
		$data['in_chat'] = 'Y';
		$data['chat_request_from'] = 'Staff';
		$data['awaiting_response_from_staff'] = 'N';
		$data['messages'] = $this->l('Chat invitation from staff to staff');
		$data['is_archive'] = 'N';
		$data['name'] = 'Staff';
		$data['id_staffprofile'] = $this->id_staffprofile;

		$this->addUpdateArchive($data);

		@$this->addLog(array(
			'id_staffprofile' => $this->id_staffprofile,
			'id_visitor' => $data['id_visitor'],
			'message' => 'Chat-Request from (Staff).'
		));
		return true;
	}


	public function chatRequestFromClient($data = '')
	{
		#if ($this->getChatStatus() == 'offline') 
			#return;

		$data['action'] = 'chatRequestFromClient';
		$data['in_chat'] = 'P';
		$data['chat_request_from'] = 'Client';
		$data['awaiting_response_from_staff'] = 'Y';
		$data['id_staffprofile'] = $this->getMostAvailableStaffMemberId($data['id_department']);
		$data['messages'] = $data['question'];
		$data['is_archive'] = 'N';

		$this->addUpdateArchive($data);	

		@$this->addLog(array(
			'id_staffprofile' => $data['id_staffprofile'],
			'id_visitor' => $data['id_visitor'],
			'message' => 'Chat-Request from (Client).'
		));

		#$this->id_staffprofile = $data['id_staffprofile'];

		return $data;
	}


	public function chatMessageFromStaff($data = '')
	{
		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_archive
			SET messages = CONCAT(messages, "'.pSQL($data['messages'], true).'"),
			action = "chatMessageFromStaff",
			awaiting_response_from_staff = "N",
			last_message = "'.pSQL($data['msg'], true).'"
			WHERE id_visitor = "'.pSQL($data['id_visitor']).'" AND is_archive != "Y"';

		LcpDb::getInstance()->execute($sql);

	}


	public function chatMessageFromClient($data = '')
	{
		$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_archive
			SET messages = CONCAT(messages, "'.pSQL($data['messages'], true).'"),
				awaiting_response_from_staff = "Y",
				action = "chatMessageFromClient",
				last_message = "'.pSQL($data['msg'], true).'"
			WHERE id_visitor = "'.pSQL($data['id_visitor']).'" AND is_archive != "Y"';

		LcpDb::getInstance()->execute($sql);

		// get data from archive
		$sql = 'SELECT * FROM '._DB_PREFIX_.$this->name.'_archive WHERE id_visitor = "'.pSQL($data['id_visitor']).'" AND is_archive != "Y"';
		$archive_details = LcpDb::getInstance()->executeS($sql); 
		$archive_details = @$archive_details[0];

		#$this->id_staffprofile = $archive_details['id_staffprofile'];

		return $archive_details;
	}


	public function getMostAvailableStaffMemberId($id_department = '')
	{
		$primary_settings = $this->getPrimarySettings();	

		if ($primary_settings['new_chat_rings_to'] == 'all')
			return '0';

		$online_staff_members = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_onlineusers` lou
			LEFT JOIN `'._DB_PREFIX_.$this->name.'_staffprofiles` lsp ON (lou.`id_staffprofile` = lsp.`id_staffprofile`)
			WHERE lou.type = "Internal" AND lsp.is_active = "Y" AND lsp.departments LIKE "%'.(int)$id_department.'%"');

		#d($online_staff_members);

		if (empty($online_staff_members))
		{
			// cautam la alt departament
			$online_staff_members = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_onlineusers` lou
			LEFT JOIN `'._DB_PREFIX_.$this->name.'_staffprofiles` lsp ON (lou.`id_staffprofile` = lsp.`id_staffprofile`)
			WHERE lou.type = "Internal" AND lsp.is_active = "Y"');
		}

		$arr_info = array();
		foreach ($online_staff_members as $value)
		{
			$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_archive la WHERE la.in_chat = "Y" AND la.id_visitor REGEXP("(^[0-9]+$)") AND la.id_staffprofile = "'.(int)$value['id_staffprofile'].'"');
			$arr_info[$value['id_staffprofile']] = $result[0]['COUNT(*)'];
		}

		$min = @min($arr_info);

		$id_staffprofile = array_search($min, $arr_info);

		return $id_staffprofile;
	}


	public function transferVisitor($data = '')
	{
		if (!empty($data['id_visitor']) && !empty($data['id_staffprofile_destination']))
		{
			$sql = 'UPDATE '._DB_PREFIX_.$this->name.'_archive
				SET
					in_chat = "P",
					id_staffprofile = "'.(int)$data['id_staffprofile_destination'].'",
					action = "chatRequestFromClient",
					awaiting_response_from_staff = "Y" 
				WHERE id_visitor = "'.pSQL($data['id_visitor']).'" AND is_archive != "Y"';

			LcpDb::getInstance()->execute($sql);

			if (LcpTools::substr($data['id_visitor'], 0, 1) !== 'i')
				LcpDb::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.$this->name.'_onlineusers WHERE id_visitor = "'.pSQL($data['id_visitor']).'"');			

			@$this->addLog(array(
						'id_staffprofile' => $data['id_staffprofile_destination'],
						'id_visitor' => $data['id_visitor'],
						'message' => 'Chat-Transfer to another staff (Staff).'
					));

			return true;
		}
		else
			return false;
	}


	public function getOnlineUsers($type = 'External')
	{
		if ($type == 'External')
		{
			return LcpDb::getInstance()->executeS('SELECT *
				FROM `'._DB_PREFIX_.$this->name.'_onlineusers` lou
				LEFT JOIN `'._DB_PREFIX_.$this->name.'_onlinevisitors` lov ON (lou.`id_visitor` = lov.`id_visitor`)
				LEFT JOIN `'._DB_PREFIX_.$this->name.'_archive` la ON (lou.`id_visitor` = la.`id_visitor`)
				WHERE lou.type = "'.pSQL(LcpTools::ucfirst($type)).'" AND la.is_archive != "Y"');
		}
		else
		{
			$online_internal_users = LcpDb::getInstance()->executeS('SELECT *
				FROM `'._DB_PREFIX_.$this->name.'_onlineusers` lou
				LEFT JOIN `'._DB_PREFIX_.$this->name.'_staffprofiles` lsp ON (lou.`id_staffprofile` = lsp.`id_staffprofile`)
				LEFT JOIN `'._DB_PREFIX_.'employee` e ON (lsp.`id_employee` = e.`id_employee`)
				WHERE lou.type = "'.pSQL(LcpTools::ucfirst($type)).'"');

			$arr_info = array();

			if ($online_internal_users)
			{
				foreach ($online_internal_users as $value)
				{
					$value['count_online_archives'] = $this->countStaffOnlineArchives($value['id_staffprofile']);
					$value['count_pending_archives'] = $this->countStaffPendingArchives($value['id_staffprofile']);

					$arr_info[] = $value;
				}
			}

			return $arr_info;
		}
	}


	public function countStaffOnlineArchives($id_staffprofile)
	{
		$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_onlineusers lou WHERE lou.id_staffprofile = "'.pSQL($id_staffprofile).'" AND lou.id_visitor != "0"');

		$res = $result[0]['COUNT(*)'];

		if (!empty($res)) 
			return $result[0]['COUNT(*)'];
		else 
			return 0;
	}

	public function countStaffPendingArchives($id_staffprofile)
	{
		$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_archive la WHERE la.id_staffprofile = "'.pSQL($id_staffprofile).'" AND la.in_chat = "P"');

		$res = $result[0]['COUNT(*)'];

		if (!empty($res)) 
			return $result[0]['COUNT(*)'];
		else 
			return 0;
	}

	/* type : Active, Pending, Inactive */
	public function countArchives($in_chat = 'Y')
	{
		$in_chat_cond = empty($in_chat) ? '1' : 'la.in_chat = "'.pSQL($in_chat).'"';

		$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_archive la WHERE '.$in_chat_cond.'');

		$res = $result[0]['COUNT(*)'];

		if (!empty($res)) 
			return $result[0]['COUNT(*)'];
		else 
			return 0;
	}

	public function countOnlineUsers($type = 'External')
	{
		$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_onlineusers lou WHERE lou.type = "'.pSQL(LcpTools::ucfirst($type)).'"');

		$res = $result[0]['COUNT(*)'];

		if (!empty($res)) 
			return $result[0]['COUNT(*)'];
		else 
			return 0;
	}

	public function countVisitedPages($id_visitor = '')
	{
		$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_visitedpages WHERE id_visitor = "'.pSQL($id_visitor).'"');

		$res = $result[0]['COUNT(*)'];

		if (!empty($res))  
			return $result[0]['COUNT(*)'];
		else 
			return 0;
	}	

	public function countOnlineVisitors()
	{
		$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_onlinevisitors lov');

		$res = $result[0]['COUNT(*)'];

		if (!empty($res))  
			return $result[0]['COUNT(*)'];
		else 
			return 0;
	}

	public function getStaffProfilesIdsToDelete($employees_ids)
	{
		if (!empty($employees_ids))
		{
			$employees_ids_list = '';

			foreach ($employees_ids as $value)
				$employees_ids_list .= $value.',';

			$employees_ids_list = LcpTools::substr($employees_ids_list, 0, -1);
		}

		$result = LcpDb::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.$this->name.'_staffprofiles lsp WHERE id_employee NOT IN ('.pSQL($employees_ids_list).')');
		$staffprofiles_ids = array();

		foreach ($result as $value)
			$staffprofiles_ids[] = $value['id_staffprofile'];

		return $staffprofiles_ids;
	}

	public function checkIfEmployeeInStaffProfile($id_employee)
	{
		$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_staffprofiles lsp WHERE id_employee = "'.(int)$id_employee.'"');

		$res = $result[0]['COUNT(*)'];

		if (empty($res))
			return false;
		else
			return true;
	}


	public function getVisitorChatStatus($id_visitor)
	{
		$result = LcpDb::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.$this->name.'_archive la WHERE id_visitor = "'.pSQL($id_visitor).'" ORDER BY id_archive DESC LIMIT 1');
		#d('SELECT * FROM '._DB_PREFIX_.$this->name.'_archive la WHERE id_visitor = "'.pSQL($id_visitor).'" ORDER BY id_archive DESC LIMIT 1');
		if ($result)
			return $result[0]['in_chat'];
		else
			return 'N';
	}


	public function checkIfVisitorIdInArchive($id_visitor)
	{
		$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_archive la WHERE id_visitor = "'.pSQL($id_visitor).'" AND is_archive != "Y"');

		$res = $result[0]['COUNT(*)'];

		if (empty($res))
			return false;
		else
			return true;
	}

	public function checkIfVisitorIdNotOffline($id_visitor)
	{
		$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_archive la WHERE id_visitor = "'.pSQL($id_visitor).'" AND is_archive != "Y" AND in_chat != "N"');

		$res = $result[0]['COUNT(*)'];

		if (empty($res))
			return false;
		else
			return true;
	}

	public function getCookie($data = '')
	{
		$result = @unserialize(${'_COOKIE'}['livechatpro']);
		
		if ($result)
			return $result[$data];

	}

	public function setCookie($data = '')
	{
		return setcookie('livechatpro', serialize($data), time() + 259200); 
	}



	public function assignVisitorDetails($data = '')
	{
		$visitor_details = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_onlinevisitors` lov 
			LEFT JOIN `'._DB_PREFIX_.$this->name.'_archive` la ON (lov.`id_visitor` = la.`id_visitor`)
			WHERE lov.id_visitor = "'.pSQL($data['id_visitor']).'" LIMIT 1');

		if (!empty($visitor_details))
		{
			$visitor_details = $visitor_details[0];
			$visitor_details['key'] = $data['key'];
		}

		$this->smarty->assign(array(
			'visitor_details' => $visitor_details,
			'key' => $data['key'],
		));
		return true;
	}


	public function assignVisitorVisitedPagesHistory($data = '')
	{
		$visitor_details = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_onlinevisitors` lov 
			WHERE lov.id_visitor = "'.pSQL($data['id_visitor']).'" LIMIT 1');

		if (!empty($visitor_details))
		{
			$visitor_details = $visitor_details[0];
			$visitor_details['key'] = $data['key'];
			$visitor_details['visited_pages'] = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_visitedpages` WHERE id_visitor = "'.pSQL($data['id_visitor']).'"');
		}

		$this->smarty->assign(array(
			'visitor_details' => $visitor_details,
			'key' => $data['key'],
		));
		return true;
	}

	public function assignVisitorGeoTracking($data = '')
	{
		$visitor_details = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_onlinevisitors` lov 
			WHERE lov.id_visitor = "'.pSQL($data['id_visitor']).'" LIMIT 1');

		if (!empty($visitor_details))
		{
			$visitor_details = $visitor_details[0];
			$visitor_details['key'] = $data['key'];
			$visitor_details['tracking_info'] = $this->getGeoTracking(array('id_visitor' => $data['id_visitor']));
		}

		$this->smarty->assign(array(
			'http_or_https' => $this->http_or_https(),
			'visitor_details' => $visitor_details,
			'key' => $data['key'],
		));
		return true;
	}	

	public function getGeoTracking($data = '')
	{
		//get visitor details
		if (!empty($data['id_visitor']))
		{
			$visitor_details = $this->getVisitor( array('id_visitor' => $data['id_visitor']) );
			$ip = $visitor_details['ip'];
		}
		else
			$ip = (LcpTools::getRemoteAddr() == '::1') ? '127.0.0.1' : LcpTools::getRemoteAddr();
			#$ip = '197.231.64.118';

		require_once dirname(__FILE__).'/../libraries/geoplugin/geoplugin.class.php';

		$geoplugin = new geoPlugin();
		$geoplugin->locate($ip);

		$city = empty($geoplugin->city) ? 'unknown' : $geoplugin->city;
		$country = empty($geoplugin->countryName) ? 'unknown' : $geoplugin->countryName;

		if ($country != 'unknown')
		{
			if ($city != 'unknown')
				$coordinates_precision = 7;
			else
				$coordinates_precision = 2;
		}
		else
			$coordinates_precision = 1;

		$tracking_info = array(
			'city' => $city,
			'country' => $country,
			'latitude' => number_format($geoplugin->latitude, $coordinates_precision, '.', ''),
			'longitude' => number_format($geoplugin->longitude, $coordinates_precision, '.', '')
		);

		//@ob_end_clean();

		return $tracking_info;
	}


	public function showOnlineInternalUsers()
	{	
		$online_internal_users = $this->getOnlineUsers('Internal');
		$count_online_internal_users = $this->countOnlineUsers('Internal');

		$this->smarty->assign(array(
			'online_internal_users' => $online_internal_users,
			'count_online_internal_users' => $count_online_internal_users,
			'id_staffprofile' => $this->id_staffprofile,
		));

		return $this->display(dirname(__FILE__).'/../views/templates/admin/ajax.online_internal_users.tpl');
	}


	public static function replaceVars($template, $variables = array())
	{
		self::$replace_vars = $variables;
		$template           = preg_replace_callback('/{(?P<tag>[^}]+)}/', 'self::replaceCallback', $template);

		return $template;
	}

	public static function replaceCallback($matches)
	{
		$tag = $matches['tag'];
		return (isset(self::$replace_vars['{'.$tag.'}'])) ? self::$replace_vars['{'.$tag.'}'] : '{'.$tag.'}';
	}


	public function getCustomerDetails($data = '')
	{
		$customer = LcpDb::getInstance()->executeS(
			'SELECT * FROM `'._DB_PREFIX_.'customer` c 
			LEFT JOIN `'._DB_PREFIX_.'address` a ON (c.`id_customer` = a.`id_customer`)
			WHERE c.`id_customer` = "'.pSQL($data['id_customer']).'" LIMIT 1'
			);
		$customer = $customer[0];
		
		return $customer;
	}

	public function getSessionId()
	{
		#$session_id = (empty($this->context->cookie->id_customer)) ? $this->context->cookie->id_guest : $this->context->cookie->id_customer;
		// for php 5.2
		if (call_user_func('session_id') === '')
			call_user_func('session_start');
		
		$session_id = call_user_func('session_id');

		session_write_close();

		return $session_id;
	}


	public function addUpdateVisitor($data)
	{
		$data['session_id'] = $this->getSessionId();

		$result = LcpDb::getInstance()->executeS('SELECT COUNT(*) FROM '._DB_PREFIX_.$this->name.'_onlinevisitors WHERE session_id = "'.pSQL($data['session_id']).'"');
		$count  = $result[0]['COUNT(*)'];

		if ($count == 0)
			$id_visitor = $this->addVisitor($data);
		else
		{
			$visitor = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_onlinevisitors` lov WHERE lov.`session_id` = "'.pSQL($data['session_id']).'"');
			$visitor = $visitor[0];
			$id_visitor = $visitor['id_visitor'];
			//update user in baza de date
			$data['timezone']    = 'GMT '.$data['timezone'].':00';
			$data['online_time'] = gmdate('H:i:s', time() - strtotime($visitor['last_update']));
			$data['last_visit']  = date('Y-m-d H:i:s', time());
			$data['page_count'] = $this->countVisitedPages($id_visitor) + 1;

			$this->updateVisitor($data);
		}
		//adaugam pagina visitata
		$data['id_visitor'] = $id_visitor;

		$data['url']        = $data['current_page'];

		//add visited page
		$this->addVisitedPage($data);

		//returnez visitor id
		return $id_visitor;
	}


	public function deleteOfflineVisitors()
	{
		$sql = 'DELETE FROM '._DB_PREFIX_.$this->name.'_onlinevisitors WHERE UNIX_TIMESTAMP(last_update) < '.pSQL(time() - $this->timeout);
		LcpDb::getInstance()->Execute($sql);

		return true;
	}

	public function deleteOfflineUsers()
	{
		$sql = 'DELETE FROM '._DB_PREFIX_.$this->name.'_onlineusers WHERE UNIX_TIMESTAMP(last_update) < '.pSQL(time() - $this->timeout);
		LcpDb::getInstance()->Execute($sql);

		return true;
	}

	public function deleteOldVisitedPages()
	{
		$sql = 'DELETE FROM '._DB_PREFIX_.$this->name.'_visitedpages WHERE UNIX_TIMESTAMP(last_update) < '.pSQL(time() - $this->timeout);
		LcpDb::getInstance()->Execute($sql);

		return true;
	}

	public function fillGridDataTables($type, $data = '')
	{
		$db = '';		

		include('libraries/datatables/PHP/DataTables.php');

			if ($type == 'departments')
			{
				//$editor = DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_departments',  'id_department');
				return DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_departments', 'id_department')->fields(
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_departments.id_department' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_departments.status' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_departments.name' )->validator( 'Validate::notEmpty' )
					)->process( $data )->json();
			}
			elseif ($type == 'staffprofiles')
			{
				return DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_staffprofiles', 'id_staffprofile')->fields(
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_staffprofiles.id_staffprofile' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_staffprofiles.is_active' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.'employee.firstname' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.'employee.lastname' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_staffprofiles.avatar' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_staffprofiles.departments' )->validator( 'Validate::notEmpty' )->getFormatter( 

							function ( $val, $data, $opts ) { // from server for client
									
									$departments_names = '';
									$departments_ids_array = @explode(',', $val);

									foreach ($departments_ids_array as $value)
									{
										$department_name = LcpDb::getInstance()->executeS('SELECT name FROM `'._DB_PREFIX_.'livechatpro_departments` ld WHERE ld.id_department = "'.pSQL($value).'"');
										if (!empty($department_name))
											$departments_names .= $department_name[0]['name'].',';
									}
									return LcpTools::substr(trim($departments_names), 0, -1);
								
								} )->setFormatter( 
							
							function ( $val, $data, $opts ) { // from client for server

									$departments_ids = '';
									$departments_names_array = @explode(',', $val);

									foreach ($departments_names_array as $value)
									{
										$department_id = LcpDb::getInstance()->executeS('SELECT id_department FROM `'._DB_PREFIX_.'livechatpro_departments` ld WHERE ld.name = "'.pSQL($value).'"');
										if (!empty($department_id))
											$departments_ids .= $department_id[0]['id_department'].',';
									}
									return LcpTools::substr(trim($departments_ids), 0, -1);
								} ),
						//Field::inst( _DB_PREFIX_.$this->name.'_departments.name' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_staffprofiles.welcome_message' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_staffprofiles.signature' )->validator( 'Validate::notEmpty' )
					)->leftJoin( _DB_PREFIX_.'employee', _DB_PREFIX_.'employee.id_employee', '=', _DB_PREFIX_.$this->name.'_staffprofiles.id_employee' )->process( $data )->json();
			}
			elseif ($type == 'predefinedmessages')
			{
				return DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_predefinedmessages', 'id_predefinedmessage')->fields(
					DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_predefinedmessages.id_predefinedmessage' )->validator( 'Validate::notEmpty' ),
					DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_predefinedmessages.iso_code' )->options( _DB_PREFIX_.'lang', 'iso_code', 'iso_code' )->validator( 'Validate::notEmpty' ),
					DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_predefinedmessages.title' )->validator( 'Validate::notEmpty' ),
					DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_predefinedmessages.message' )->validator( 'Validate::notEmpty' ),
					DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_predefinedmessages.last_update' )->validator( 'Validate::notEmpty' )
					)->leftJoin( _DB_PREFIX_.'lang', _DB_PREFIX_.'lang.iso_code', '=', _DB_PREFIX_.$this->name.'_predefinedmessages.iso_code' )->process( $data )->json();
			}
			elseif ($type == 'onlinevisitors')
			{
				return DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_onlinevisitors', 'id_visitor')->fields(
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.id_visitor' )->validator( 'Validate::notEmpty' ),
						//Field::inst( _DB_PREFIX_.$this->name.'_archive.name' )->validator( 'Validate::notEmpty' ),
						//Field::inst( _DB_PREFIX_.$this->name.'_archive.email' )->validator( 'Validate::notEmpty' ),
						//Field::inst( _DB_PREFIX_.$this->name.'_archive.phone' )->validator( 'Validate::notEmpty' ),
						//Field::inst( _DB_PREFIX_.$this->name.'_archive.company' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.country' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.city' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.province' )->validator( 'Validate::notEmpty' ),
						//Field::inst( _DB_PREFIX_.$this->name.'_archive.in_chat' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.language' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.visits' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.current_page' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.host' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.ip' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.browser' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.timezone' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.resolution' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.online_time' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.referrer' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.page_count' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.os' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_onlinevisitors.last_visit' )->validator( 'Validate::dateFormat', array('format'  => DataTables\Editor\Format::DATE_ISO_8601, 'message' => 'Please enter a date in the format yyyy-mm-dd') )->getFormatter( 'Format::date_sql_to_format', DataTables\Editor\Format::DATE_ISO_8601 )->setFormatter( 'Format::date_format_to_sql', DataTables\Editor\Format::DATE_ISO_8601 )
					)->process( $data )->json();
			}
			elseif ($type == 'archive')
			{
				return DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_archive', 'id_archive')->fields(
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.id_archive' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.date_add' )->validator( 'Validate::dateFormat', array('format'  => DataTables\Editor\Format::DATE_ISO_8601,'message' => 'Please enter a date in the format yyyy-mm-dd') )->getFormatter( 'Format::date_sql_to_format', DataTables\Editor\Format::DATE_ISO_8601 )->setFormatter( 'Format::date_format_to_sql', DataTables\Editor\Format::DATE_ISO_8601 ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.id_chat' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.name' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.internal' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_departments.name' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.email' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.phone' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.company' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.language' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.country' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.ip' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.host' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.duration' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.log_entries' )->validator( 'Validate::notEmpty' )
					)->leftJoin( _DB_PREFIX_.$this->name.'_departments', _DB_PREFIX_.$this->name.'_departments.id_department', '=', _DB_PREFIX_.$this->name.'_archive.id_department' )->process( $data )->json();
			}
			elseif ($type == 'visitorarchive')
			{
				$archive_visitor_details = $this->getVisitorArchiveDetails(array('id_visitor' => @$data['id_visitor']));
				//d($visitor_details['ip']);

				return DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_archive', 'id_archive')->fields(
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.id_archive' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.id_visitor' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.date_add' )->validator( 'Validate::dateFormat', array('format'  => DataTables\Editor\Format::DATE_ISO_8601,'message' => 'Please enter a date in the format yyyy-mm-dd') )->getFormatter( 'Format::date_sql_to_format', DataTables\Editor\Format::DATE_ISO_8601 )->setFormatter( 'Format::date_format_to_sql', DataTables\Editor\Format::DATE_ISO_8601 ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.id_chat' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.name' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.internal' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_departments.name' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.email' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.phone' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.company' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.language' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.country' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.ip' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.host' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.duration' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_archive.log_entries' )->validator( 'Validate::notEmpty' )
					)->leftJoin( _DB_PREFIX_.$this->name.'_departments', _DB_PREFIX_.$this->name.'_departments.id_department', '=', _DB_PREFIX_.$this->name.'_archive.id_department' )->where( _DB_PREFIX_.$this->name.'_archive.ip', @$archive_visitor_details['ip'] )->process( $data )->json();
			}
			elseif ($type == 'messages')
			{
				return DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_messages', 'id_message')->fields(
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.id_message' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.date_add' )->validator( 'Validate::dateFormat', array('format'  => DataTables\Editor\Format::DATE_ISO_8601,'message' => 'Please enter a date in the format yyyy-mm-dd') )->getFormatter( 'Format::date_sql_to_format', DataTables\Editor\Format::DATE_ISO_8601 )->setFormatter( 'Format::date_format_to_sql', DataTables\Editor\Format::DATE_ISO_8601 ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.name' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.email' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.phone' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.department' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.question' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.ip' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.status' )->validator( 'Validate::notEmpty' )
					)->process( $data )->json();
			}
			elseif ($type == 'tickets' || $type == 'customertickets')
			{
				if ($type == 'customertickets')
				{
				$id_customer = @$data['id_customer'];
				$datatable = DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_tickets', 'id_ticket')->fields(
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.id_ticket' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.id_employee' )->options( _DB_PREFIX_.'employee', 'id_employee', 'firstname' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.id_staffprofile' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.id_customer' )->options( _DB_PREFIX_.'customer', 'id_customer', 'firstname' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.id_department' )->options( _DB_PREFIX_.$this->name.'_departments', 'id_department', 'name' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.subject' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.last_update' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.priority' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.status' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.date_add' )->validator( 'Validate::dateFormat', array('format'  => DataTables\Editor\Format::DATE_ISO_8601,'message' => 'Please enter a date in the format yyyy-mm-dd') )->getFormatter( 'Format::date_sql_to_format', DataTables\Editor\Format::DATE_ISO_8601 )->setFormatter( 'Format::date_format_to_sql', DataTables\Editor\Format::DATE_ISO_8601 ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_departments.name' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.'employee.firstname' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.'employee.lastname' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.'customer.firstname' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.'customer.lastname' )
					)->leftJoin( _DB_PREFIX_.$this->name.'_departments', _DB_PREFIX_.$this->name.'_departments.id_department', '=', _DB_PREFIX_.$this->name.'_tickets.id_department' )->leftJoin( _DB_PREFIX_.'employee', _DB_PREFIX_.'employee.id_employee', '=', _DB_PREFIX_.$this->name.'_tickets.id_employee' )->leftJoin( _DB_PREFIX_.'customer', _DB_PREFIX_.'customer.id_customer', '=', _DB_PREFIX_.$this->name.'_tickets.id_customer' )->where( _DB_PREFIX_.$this->name.'_tickets.id_customer', @$id_customer )->process( $data )->json();
				}
				else
				{
				$datatable = DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_tickets', 'id_ticket')->fields(
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.id_ticket' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.id_employee' )->options( _DB_PREFIX_.'employee', 'id_employee', 'firstname' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.id_staffprofile' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.id_customer' )->options( _DB_PREFIX_.'customer', 'id_customer', 'firstname' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.id_department' )->options( _DB_PREFIX_.$this->name.'_departments', 'id_department', 'name' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.subject' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.last_update' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.priority' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.status' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_tickets.date_add' )->validator( 'Validate::dateFormat', array('format'  => DataTables\Editor\Format::DATE_ISO_8601,'message' => 'Please enter a date in the format yyyy-mm-dd') )->getFormatter( 'Format::date_sql_to_format', DataTables\Editor\Format::DATE_ISO_8601 )->setFormatter( 'Format::date_format_to_sql', DataTables\Editor\Format::DATE_ISO_8601 ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_departments.name' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.'employee.firstname' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.'employee.lastname' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.'customer.firstname' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.'customer.lastname' )
					)->leftJoin( _DB_PREFIX_.$this->name.'_departments', _DB_PREFIX_.$this->name.'_departments.id_department', '=', _DB_PREFIX_.$this->name.'_tickets.id_department' )->leftJoin( _DB_PREFIX_.'employee', _DB_PREFIX_.'employee.id_employee', '=', _DB_PREFIX_.$this->name.'_tickets.id_employee' )->leftJoin( _DB_PREFIX_.'customer', _DB_PREFIX_.'customer.id_customer', '=', _DB_PREFIX_.$this->name.'_tickets.id_customer' )->process( $data )->json();
				}


				if (!empty($data['action']))
				{
					if ($data['action'] == 'remove')
					{
						$id_row = key($data['data']);
						$sql = 'DELETE FROM '._DB_PREFIX_.$this->name.'_ticketsreplyes WHERE id_ticket = "'.pSQL($data['data'][$id_row][_DB_PREFIX_.$this->name.'_tickets']['id_ticket']).'"';
						#d($sql);
						LcpDb::getInstance()->Execute($sql);
					}
					elseif ($data['action'] == 'create')
					{
						#d($data);
						$id_ticket = $this->getLastTicketId();
						if (!empty($data['data'][0][_DB_PREFIX_.$this->name.'_tickets']['id_employee']))
						{
							$id_employee = $data['data'][0][_DB_PREFIX_.$this->name.'_tickets']['id_employee'];
							$staffprofile_details = $this->getStaffProfile(array('id_employee' => $id_employee));
							$id_staffprofile = $staffprofile_details['id_staffprofile'];
						}
						else
							$id_staffprofile = 0;

						$id_customer = $data['data'][0][_DB_PREFIX_.$this->name.'_tickets']['id_customer'];
						$message = $data['data'][0]['message'];

						$this->updateTicket(array('id_ticket' => $id_ticket, 'id_staffprofile' => $id_staffprofile, 'date_add' => date('Y-m-d H:i:s', time())));

						$this->addTicketReply(array(
								'id_ticket' => (int)$id_ticket,
								'id_staffprofile' => (int)$id_staffprofile,
								'id_customer' => $id_customer,
								'reply_from' => $data['data'][0]['reply_from'],
								'message' => $message,
							));
						
					}
				}

				return $datatable;

			}
			elseif ($type == 'visitormessages')
			{
				$archive_visitor_details = $this->getVisitorArchiveDetails(array('id_visitor' => @$data['id_visitor']));

				return DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_messages', 'id_message')->fields(
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.id_message' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.id_visitor' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.date_add' )->validator( 'Validate::dateFormat', array('format'  => DataTables\Editor\Format::DATE_ISO_8601,'message' => 'Please enter a date in the format yyyy-mm-dd') )->getFormatter( 'Format::date_sql_to_format', DataTables\Editor\Format::DATE_ISO_8601 )->setFormatter( 'Format::date_format_to_sql', DataTables\Editor\Format::DATE_ISO_8601 ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.name' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.email' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.phone' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.department' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.question' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.ip' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_messages.status' )->validator( 'Validate::notEmpty' )
					)->where( _DB_PREFIX_.$this->name.'_messages.ip', @$archive_visitor_details['ip'] )->process( $data )->json();
			}
			elseif ($type == 'ratings')
			{
				return DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_ratings', 'id_rating')->fields(
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.id_rating' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.politness' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.qualification' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.date_add' )->validator( 'Validate::dateFormat', array('format'  => DataTables\Editor\Format::DATE_ISO_8601,'message' => 'Please enter a date in the format yyyy-mm-dd') )->getFormatter( 'Format::date_sql_to_format', DataTables\Editor\Format::DATE_ISO_8601 )->setFormatter( 'Format::date_format_to_sql', DataTables\Editor\Format::DATE_ISO_8601 ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.internal' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.name' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.email' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.company' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.comment' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.status' )->validator( 'Validate::notEmpty' )
					)->process( $data )->json();
			}
			elseif ($type == 'visitorratings')
			{
				return DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_ratings', 'id_rating')->fields(
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.id_rating' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.id_visitor' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.politness' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.qualification' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.date_add' )->validator( 'Validate::dateFormat', array('format'  => DataTables\Editor\Format::DATE_ISO_8601,'message' => 'Please enter a date in the format yyyy-mm-dd') )->getFormatter( 'Format::date_sql_to_format', DataTables\Editor\Format::DATE_ISO_8601 )->setFormatter( 'Format::date_format_to_sql', DataTables\Editor\Format::DATE_ISO_8601 ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.internal' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.name' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.email' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.company' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.comment' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_ratings.status' )->validator( 'Validate::notEmpty' )
					)->where( _DB_PREFIX_.$this->name.'_ratings.id_visitor', @$data['id_visitor'] )->process( $data )->json();
			}
			elseif ($type == 'logs')
			{
				return DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_logs', 'id_log')->fields(
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_logs.id_log' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_logs.date_add' )->validator( 'Validate::dateFormat', array('format'  => DataTables\Editor\Format::DATE_ISO_8601,'message' => 'Please enter a date in the format yyyy-mm-dd') )->getFormatter( 'Format::date_sql_to_format', DataTables\Editor\Format::DATE_ISO_8601 )->setFormatter( 'Format::date_format_to_sql', DataTables\Editor\Format::DATE_ISO_8601 ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_logs.message' )->validator( 'Validate::notEmpty' )
					)->process( $data )->json();
			}
			elseif ($type == 'visitorlogs')
			{
				return DataTables\Editor::inst( $db, _DB_PREFIX_.$this->name.'_logs', 'id_log')->fields(
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_logs.id_log' )->validator( 'Validate::notEmpty' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_logs.id_visitor' ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_logs.date_add' )->validator( 'Validate::dateFormat', array('format'  => DataTables\Editor\Format::DATE_ISO_8601,'message' => 'Please enter a date in the format yyyy-mm-dd') )->getFormatter( 'Format::date_sql_to_format', DataTables\Editor\Format::DATE_ISO_8601 )->setFormatter( 'Format::date_format_to_sql', DataTables\Editor\Format::DATE_ISO_8601 ),
						DataTables\Editor\Field::inst( _DB_PREFIX_.$this->name.'_logs.message' )->validator( 'Validate::notEmpty' )
					)->where( _DB_PREFIX_.$this->name.'_logs.id_visitor', @$data['id_visitor'] )->process( $data )->json();
			}
		
	}


	public function getLastTicketId()
	{
		$sql = 'SELECT id_ticket FROM '._DB_PREFIX_.$this->name.'_tickets ORDER BY id_ticket DESC LIMIT 1';

		$result = LcpDb::getInstance()->executeS($sql);

		if ($result)
			return $result[0]['id_ticket'];
		else
			return 0;
	}	

	public function fillGrid($type)
	{
		if ($type == 'departments')
			$sql = 'SELECT * FROM '._DB_PREFIX_.$this->name.'_departments ld WHERE 1 ORDER BY ld.id_department ASC';
		elseif ($type == 'staffprofiles')
			$sql = 'SELECT * FROM '._DB_PREFIX_.'employee e LEFT JOIN `'._DB_PREFIX_.$this->name.'_staffprofiles` lsp ON (e.`id_employee` = lsp.`id_employee`) WHERE 1 ORDER BY e.id_employee ASC';
		elseif ($type == 'visitors')
			$sql = 'SELECT * FROM '._DB_PREFIX_.$this->name.'_onlinevisitors lov WHERE 1 ORDER BY lov.id_visitor ASC';
		elseif ($type == 'archive')
			$sql = 'SELECT * FROM '._DB_PREFIX_.$this->name.'_archive la WHERE 1 GROUP BY la.ip ORDER BY la.id_archive ASC';
		elseif ($type == 'messages')
			$sql = 'SELECT * FROM '._DB_PREFIX_.$this->name.'_messages lm WHERE 1 ORDER BY lm.id_message ASC';
		elseif ($type == 'ratings')
			$sql = 'SELECT * FROM '._DB_PREFIX_.$this->name.'_ratings lr WHERE 1 ORDER BY lr.id_rating ASC';
		elseif ($type == 'logs')
			$sql = 'SELECT * FROM '._DB_PREFIX_.$this->name.'_logs ll WHERE 1 ORDER BY ll.id_log ASC';

		$result = LcpDb::getInstance()->executeS($sql);

		if ($type == 'staffprofiles')
		{
			$arr_info = array();
			foreach ($result as $value)
			{
				if (empty($value['departments']))
					$value['departments'] = 1;

				$departments = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$this->name.'_departments` ld WHERE ld.id_department IN ('.pSQL($value['departments']).')');

				$value['departments_names'] = '';

				foreach ($departments as $value2)
					$value['departments_names'] .= $value2['name'].', ';

				$value['departments_names'] = LcpTools::substr(trim($value['departments_names']), 0, -1);

				$arr_info[] = $value;

			}
			$result = $arr_info;

		}

		return LcpTools::jsonEncode($result);
	}

	public function getEmployeeById($id_employee, $active_only = false)
	{
		$result = LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'employee`
		WHERE `id_employee` = '.(int)$id_employee.'
		'.($active_only ? ' AND `active` = 1' : ''));
		
		if ($result)
			return $result[0];
		else
			return false;
	}

	public function getStoreById($id_store)
	{
		return LcpDb::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'store` WHERE id_store = '.(int)$id_store.'');
	}


	public static function makeClickableLinks($s) 
	{
		return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
	}


	/**
	 * Convert object to array.
	 */
	public function objectToArray($obj)
	{
		if (is_array($obj) || is_object($obj))
		{
			$result = array();

			foreach ($obj as $key => $value)
				$result[$key] = $this->objectToArray($value);

			return $result;
		}
		return $obj;
	}

public function arrayToObject($array)
{
	$obj = new stdClass;

	foreach ($array as $k => $v) 
	{
		if (LcpTools::strlen($k))
		{
			if (is_array($v))
				$obj->{$k} = $this->arrayToObject($v);
			else
				$obj->{$k} = $v;
		}
	}
	return $obj;
} 



}





?>