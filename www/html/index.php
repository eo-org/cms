<?php
//set path evironment
define("BASE_PATH", getenv('BASE_PATH'));
define("CONTAINER_PATH", BASE_PATH.'/cms');
define("APP_PATH", CONTAINER_PATH.'/app/application');

//set const data
define("APP_ENV", getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production');
define("MD5_SALT", getenv('MD5_SALT'));
define("LOCAL_CHARSET", getenv('LOCAL_CHARSET') ? getenv('LOCAL_CHARSET') : 'UTF-8');

$libPath = BASE_PATH.'/include';
$commonLibPath = BASE_PATH.'/libraries/common';
$cmsLibPath = BASE_PATH.'/libraries/cms';
set_include_path($libPath.PATH_SEPARATOR.$commonLibPath.PATH_SEPARATOR.$cmsLibPath);

//set db adaptors and other configs
require_once $libPath.'/Zend/Db/Adapter/Pdo/Mysql.php';
require_once $libPath.'/Zend/Registry.php';
require_once $libPath.'/Zend/Db/Table.php';
require_once $libPath.'/Zend/Debug.php';
require_once $libPath.'/Zend/Config/Ini.php';
require_once $cmsLibPath.'/Class/Server.php';
$config = new Zend_Config_Ini(BASE_PATH.'/configs/cms/db.ini');

$siteDb = new Zend_Db_Adapter_Pdo_Mysql(array(
	'host' => $config->siteDb->host,
	'username' => $config->siteDb->username,
	'password' => $config->siteDb->password,
	'dbname' => $config->siteDb->dbname,
	'adapter' => 'mysqli',
	'charset' => 'UTF8'
));
Zend_Registry::set('siteDb', $siteDb);

$db = new Zend_Db_Adapter_Pdo_Mysql(array(
	'host' => $config->db->host,
	'username' => $config->db->username,
	'password' => $config->db->password,
	'dbname' => $config->db->dbname,
	'adapter' => 'mysqli',
	'charset' => 'UTF8'
));
Zend_Registry::set('db', $db);
Zend_Db_Table::setDefaultAdapter($db);

define('CACHE_PATH', $config->path->misc);
define('TEMPLATE_PATH', $config->path->template);

Class_Server::setConfigPath(BASE_PATH.'/configs/cms/server.ini');
Class_Server::config(APP_ENV, 'v1', 'cms.eo.test');

require_once $libPath."/Zend/Application.php";
$application = new Zend_Application(APP_ENV, BASE_PATH.'/configs/cms/application.ini');
$application->bootstrap()->run();
//echo $startTime - time();
