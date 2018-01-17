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

class LcpTools
{
	public function __construct()
	{

	}

	public static function isSubmit($submit)
	{
		return @(self::getIsset(${'_POST'}[$submit]) || self::getIsset(${'_POST'}[$submit.'_x']) || self::getIsset(${'_POST'}[$submit.'_y']) || self::getIsset(${'_GET'}[$submit]) || self::getIsset(${'_GET'}[$submit.'_x']) || self::getIsset(${'_GET'}[$submit.'_y']));
	}

	public static function getValue($key, $default_value = false)
	{
		if (!self::getIsset($key) || empty($key) || !is_string($key))
			return false;
		
		$ret = @(self::getIsset(${'_POST'}[$key]) ? ${'_POST'}[$key] : (self::getIsset(${'_GET'}[$key]) ? ${'_GET'}[$key] : $default_value));

		if (is_string($ret) === true)
			$ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
		return !is_string($ret)? $ret : self::stripslashes($ret);

	}

	public static function encrypt($passwd)
	{
		return md5($passwd);
	}

	public static function getIsset($str)
	{
		return isset($str);
		#return call_user_func('isset', $str);
	}

	public static function stripslashes($str)
	{
		#return stripslashes($str);
		return call_user_func('stripslashes', $str);
	}

	public static function file_get_contents($filename)
	{
		#return file_get_contents($filename);
		return call_user_func('file_get_contents', $filename);
	}

	public static function substr($str, $start, $length = false, $encoding = 'utf-8')
	{
		#return substr($str, $start, ($length === false ? LcpTools::strlen($str) : (int)$length));
		return call_user_func('substr', $str, $start, ($length === false ? LcpTools::strlen($str) : (int)$length));
	}

	public static function strtolower($str)
	{
		return call_user_func('strtolower', $str);
	}

	public static function floatVal($str)
	{
		return call_user_func('floatval', $str);
	}

	public static function strVal($str)
	{
		return call_user_func('strval', $str);
	}

	public static function jsonEncode($a)
	{
		if (is_null($a)) return 'null';
		if ($a === false) return 'false';
		if ($a === true) return 'true';

		if (is_scalar($a))
		{
			if (is_float($a))
			{
				// Always use "." for floats.
				return self::floatVal(str_replace(',', '.', self::strVal($a)));
			}
			if (is_string($a))
			{
				static $jsonReplaces = array(array('\\', '/', "\n", "\t", "\r", '\b', "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
				return '"'.str_replace($jsonReplaces[0], $jsonReplaces[1], $a).'"';
			}
			else
				return $a;
		}

		$isList = true;

		$count_a = count($a);

		for ($i = 0, reset($a); $i < $count_a; $i++, next($a))
		{
			if (key($a) !== $i)
			{
				$isList = false;
				break;
			}
		}

		$result = array();

		if ($isList)
		{
			foreach ($a as $v) 
				$result[] = self::jsonEncode($v);

			return '['.join(',', $result).']';
		}
		else
		{
			foreach ($a as $k => $v) 
				$result[] = self::jsonEncode($k).':'.self::jsonEncode($v);

			return '{'.join(',', $result).'}';
		}
		#return json_encode($data, JSON_UNESCAPED_UNICODE);
		#return call_user_func('json_encode', $data, JSON_UNESCAPED_UNICODE);
	}


	public static function getRemoteAddr()
	{
		// This condition is necessary when using CDN, don't remove it.
		if (self::getIsset(@$_SERVER['HTTP_X_FORWARDED_FOR']) && @$_SERVER['HTTP_X_FORWARDED_FOR'] && (!self::getIsset($_SERVER['REMOTE_ADDR']) || preg_match('/^127\..*/i', trim($_SERVER['REMOTE_ADDR'])) || preg_match('/^172\.16.*/i', trim($_SERVER['REMOTE_ADDR'])) || preg_match('/^192\.168\.*/i', trim($_SERVER['REMOTE_ADDR'])) || preg_match('/^10\..*/i', trim($_SERVER['REMOTE_ADDR']))))
		{
			if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ','))
			{
				$ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
				return $ips[0];
			}
			else
				return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		return $_SERVER['REMOTE_ADDR'];
	}

	public static function ucfirst($str)
	{
		#return strtoupper($str);
		return call_user_func('strtoupper', $str);
	}

	public static function strlen($str, $encoding = 'UTF-8')
	{
		#return strlen($str);
		return call_user_func('strlen', $str);
	}



} /* end class */

if (!function_exists('d')) 
{
	function d($object)
	{
		echo '<xmp style="text-align: left;">';
		print_r($object);
		echo '</xmp><br />';

		die('END');
		#return $object;
	}
}

if (!function_exists('p')) 
{
	function p($object)
	{
		echo '<xmp style="text-align: left;">';
		print_r($object);
		echo '</xmp><br />';
		
		return $object;
	}
}

if (!function_exists('pSQL')) 
{
	function pSQL($string, $html_ok = false)
	{
		if (!is_numeric($string))
		{
			$string = mysqli_real_escape_string(LcpDb::$link, $string);
			if (!$html_ok)
				$string = strip_tags(nl2br($string));
		}

		return $string;
	}
}



if (!function_exists('ifsetor')) 
{

	function ifsetor(&$variable, $default = null) 
	{
		if (LcpTools::getIsset($variable)) 
			$tmp = $variable;
		else
			$tmp = $default;

		return $tmp;
	}

}


if (!function_exists('smartyFunctionTranslate')) 
{
	function smartyFunctionTranslate($params, &$smarty)
	{
		//die((strstr($_SERVER['REQUEST_URI'], 'admin') == false) ? $_SESSION['lcp']['visitor_iso_code'] :  $_SESSION['lcp']['employee_iso_code']);
		//require(dirname(__FILE__).'/../translations/lang_'.(empty($_SESSION['lcp']['employee_iso_code']) ? $_SESSION['lcp']['visitor_iso_code'] : $_SESSION['lcp']['employee_iso_code']).'.php');
		$iso_code = (strstr($_SERVER['REQUEST_URI'], 'admin') == false || strstr($_SERVER['REQUEST_URI'], 'backoffice') == false) ? $_SESSION['lcp']['visitor_iso_code'] :  $_SESSION['lcp']['employee_iso_code'];
		if (file_exists(dirname(__FILE__).'/../translations/lang_'.$iso_code.'.php'))
			require(dirname(__FILE__).'/../translations/lang_'.$iso_code.'.php');
		else
			require(dirname(__FILE__).'/../translations/lang_en.php');

		//return $params;
		if (empty($params['filename']))
			$filename = $smarty->getTemplateVars('template');
		else
			$filename = $params['filename'];

		$filename = str_replace('\\', '/', $filename);
		$filename_exp = explode('/views/templates/', $filename);
		$filename = $filename_exp[1];
	
		//setam key-ul
		$key = '{'.$filename.'}{'.md5($params['s']).'}';

		$_LANG = @$_LANG;

		if (!empty($_LANG[$key]))
			return $_LANG[$key];
		else
		{
			return $params['s'];
			//return '&#60;'.$params['s'].'&#62;';
			//if (empty($_SESSION['lcp']['missing_translations']))
				//$_SESSION['lcp']['missing_translations'] = array();
			//$_SESSION['lcp']['missing_translations'] = array_merge($_SESSION['lcp']['missing_translations'], array('The template language var was not found: $_LANG["'.$key.' => '.$params['s'].'"]'));
		}
	}
}