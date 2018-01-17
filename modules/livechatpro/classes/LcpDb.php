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

/*
require_once 'cl/Database.php';
$con= new Database ('localhost', 'username', 'password', 'Dbname');

$db = Database('localhost', 'username', 'password', 'database_name');
$q = "SELECT * FROM articles ORDER BY id DESC";
$r = $db->query($q);

if ($db->num_rows($r) > 0) {
while ($a = $db->fetch_array_assoc($r)) {
echo "{$a['author']} wrote {$a['title']}\n";
}
}

$q = "SELECT * FROM articles ORDER BY id DESC";
$a = $db->fetch_all_array($q);
if (!empty($a)) {
foreach ($a as $k => $v) {
echo "{$v['author']} wrote {$v['title']}\n";
}
}
*/
#$_SESSION = @$_SESSION;
class LcpDb
{
	protected static $instance;
	private $host;
	private $user;
	private $pass;
	private $name;
	public static $link;
	private $error;
	private $errno;
	private $query;

	public static function i($host = '', $user = '', $pass = '', $name = '', $conn = 1)
	{
		if (!isset(self::$instance)) self::$instance = new LcpDb($host, $user, $pass, $name, $conn);
		return self::$instance;
	}

	public static function getInstance()
	{
		return self::i();
	}

	public function __construct($host = '', $user = '', $pass = '', $name = '', $conn = 1)
	{
		if (!empty($host)) $this->host = $host;
		if (!empty($user)) $this->user = $user;
		if (!empty($pass)) $this->pass = $pass;
		if (!empty($name)) $this->name = $name;
		
		if ($conn == 1) 
			$this->connect();
	}
	public function __destruct()
	{
		@mysqli_close(self::$link);
	}

	public function connect()
	{
		$ps_version = (file_exists(dirname(__FILE__).'/../../../app/config/parameters.php')) ? '1.7' : '1.6';

		if ($ps_version == '1.7') 
		{
			$defines = include dirname(__FILE__).'/../../../app/config/parameters.php';
			$db_server = $defines['parameters']['database_host'];
			$db_user = $defines['parameters']['database_user'];
			$db_passwd = $defines['parameters']['database_password'];
			$db_name = $defines['parameters']['database_name'];
			$db_port = $defines['parameters']['database_port'];
		}
		else 
		{
			require_once dirname(__FILE__).'/../../../config/settings.inc.php';
			$db_server = @_DB_SERVER_;
			$db_user = @_DB_USER_;
			$db_passwd = @_DB_PASSWD_;
			$db_name = @_DB_NAME_;
			$db_port = @_DB_PORT_;
		}

		if (strstr($db_server, ':/'))
		{
			$db_server_exp = explode(':/', $db_server);
			$host = '/'.$db_server_exp[1];
			$port = false;
		}
		elseif (strstr($db_server, ':'))
		{
			$db_server_exp = explode(':', $db_server);
			$host = $db_server_exp[0];
			$port = $db_server_exp[1];
		}
		else
		{
			$host = $db_server;
			$port = false;
		}

		#$con = mysqli_connect($db_server, $db_user, $db_passwd, $db_name, $db_port, '/tmp/mysql/mysql5.sock');
		$con = mysqli_connect($host, $db_user, $db_passwd, $db_name, $port);

		// Check connection
		if (mysqli_connect_errno())
			$this->exception('Could not create database connection!'.mysqli_connect_error());
		else
			self::$link = $con;

	}

	public function close()
	{
		@mysqli_close(self::$link);
	}

	public function query($sql)
	{
		if ($this->query = @mysqli_query(self::$link, $sql))
			return $this->query;
		else
			$this->exception('Could not query database!');
			return false;
	}
	/*public static function query2($sql) {
	return self::fetch_assoc(@mysql_query($sql));
	}*/
	public static function executeS($sql)
	{
		$arr_info = array();
		$query = @mysqli_query(self::$link, $sql);
		// d($query);
		while ($row = @mysqli_fetch_assoc($query))
			$arr_info[] = $row;

		return $arr_info;
	}

	public static function Execute($sql)
	{
		return @mysqli_query(self::$link, $sql);
	}

	public function num_rows($qid)
	{
		if (empty($qid))
		{
			$this->exception('Could not get number of rows because no query id was supplied!');
			return false;
		}
		else
			return mysqli_num_rows($qid);
	}

	public function fetch_array($qid)
	{
		if (empty($qid))
		{
			$this->exception('Could not fetch array because no query id was supplied!');
			return false;
		}
		else
			$data = @mysqli_fetch_array($qid);
		return $data;
	}
	/* public static function fetch_assoc($qid) {
	if (empty($qid)) {
	$this->exception("Could not fetch array assoc because no query id was supplied!");
	return false;
	} else {
	$data = mysql_fetch_array($qid, MYSQL_ASSOC);
	}
	return $data;
	}*/

	public function fetch_array_assoc($qid)
	{
		if (empty($qid))
		{
			$this->exception('Could not fetch array assoc because no query id was supplied!');
			return false;
		}
		else
			$data = @mysqli_fetch_array($qid, MYSQL_ASSOC);
		return $data;
	}

	public function fetch_all_array($sql, $assoc = true)
	{
		$data = array();
		if ($qid = $this->query($sql))
		{
			if ($assoc)
			{
				while ($row = $this->fetch_array_assoc($qid))
					$data[] = $row;
			}
			else
			{
				while ($row = $this->fetch_array($qid))
					$data[] = $row;
			}
		}
		else
			return false;

		return $data;
	}

	public function last_id()
	{
		if ($id = mysqli_insert_id())
			return $id;
		else
			return false;
	}

	private function exception($message)
	{
		die($message);
	}
}
?>