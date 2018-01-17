<?php if (!defined('DATATABLES')) exit(); // Ensure being used in DataTables env.

error_reporting(E_ALL);

$ps_version = (file_exists(dirname(__FILE__).'/../../../../../app/config/parameters.php')) ? '1.7' : '1.6';

if ($ps_version == '1.7') 
{
	$defines = include dirname(__FILE__).'/../../../../../app/config/parameters.php';
	$db_server = $defines['parameters']['database_host'];
	$db_user = $defines['parameters']['database_user'];
	$db_passwd = $defines['parameters']['database_password'];
	$db_name = $defines['parameters']['database_name'];
	#$db_port = $defines['parameters']['database_port'];
}
else 
{
	require_once dirname(__FILE__).'/../../../../../config/settings.inc.php';
	$db_server = _DB_SERVER_;
	$db_user = _DB_USER_;
	$db_passwd = _DB_PASSWD_;
	$db_name = _DB_NAME_;
	$db_port = '';
}

#print_r($_SESSION['lcp']); exit;


if (strstr($db_server, ':/'))
{
	$db_server_exp = explode(':/', $db_server);
	$host = '/'.$db_server_exp[1];
	$port = '';
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
	$port = '';
}

$sql_details = array(
	"type" => "Mysql",  // Database type: "Mysql", "Postgres", "Sqlite" or "Sqlserver"
	"user" => $db_user,       // Database user name
	"pass" => $db_passwd,       // Database password
	"host" => $host,       // Database host
	"port" => $port,       // Database connection port (can be left empty for default)
	"db"   => $db_name,       // Database name
	"dsn"  => "charset=utf8"        // PHP DSN extra information. Set as `charset=utf8` if you are using MySQL
);


