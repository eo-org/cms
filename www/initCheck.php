<?php
define("LIB_PATH", "/Users/gavin/Sites/include");
define("MD5_SALT", getenv('MD5_SALT'));
define("APP_ENV", getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production');
define("LOCAL_CHARSET", getenv('LOCAL_CHARSET') ? getenv('LOCAL_CHARSET') : 'UTF-8');
define("DOMAIN_NAME", getenv('DOMAIN_NAME'));

set_include_path(LIB_PATH);
require_once LIB_PATH.'/Zend/Db/Adapter/Pdo/Mysql.php';
require_once LIB_PATH.'/Zend/Registry.php';
require_once LIB_PATH.'/Zend/Db/Table.php';
require_once LIB_PATH.'/Zend/Debug.php';
require_once LIB_PATH.'/Zend/Config/Ini.php';

$config = new Zend_Config_Ini('../config.ini');

$requestServerName = $_SERVER['SERVER_NAME'];
$siteDb = new Zend_Db_Adapter_Pdo_Mysql(array(
	'host' => $config->db->host,
	'username' => $config->db->username,
	'password' => $config->db->password,
	'dbname' => $config->db->dbname,
	'charset' => 'UTF8'
));
Zend_Registry::set('siteDb', $siteDb);
$requestHost = $_SERVER['HTTP_HOST'];

//$domainName = getDomain($requestHost, $config->ourDomain);
//$subdomainName = getSubdomain($requestHost, $domainName);

$siteArr = $siteDb->fetchRow($siteDb->select()->from('site')->where('subdomainName = ?', $requestHost)
	->orWhere('domainName = ?', $requestHost));
//ini_set('session.cookie_domain', '.'.$domainName);

/*
 * redirect user to payment page or no site page
 */
if($siteArr === false) {
	//check for site id with subdomain
	echo $domainName.'<br />';
	echo $subdomainName;
    header('Location: http://www.enorange.com/no-site/');
    exit;
}
if($siteArr['active'] == 1) {
	$valid = strtotime($siteArr['validToDate']);
	$now = time();
	if($valid < $now) {
		$siteDb->update('site', array('active' => 0), 'id = '.$siteArr['id']);
	}
} else {
	header('Location: http://www.enorange.com/site-expired/');
	exit;
}
if($siteArr['version'] == 0.3) {
	define("LIB_CMS_PATH", "/Users/gavin/Sites/library-cms");
} else if($siteArr['version'] >= 0.4) { 
	define("LIB_CMS_PATH", "/Users/gavin/Sites/".$siteArr['library']);
} else {
	define("LIB_CMS_PATH", $siteArr['containerPath'].'/library');
}

/*
 * set db connection for client site
 */
$db = new Zend_Db_Adapter_Pdo_Mysql(array(
	'host' => $siteArr['dbHost'],
	'username' => $config->db->username,
	'password' => $config->db->password,
	'dbname' => $siteArr['dbDatabase'],
	'adapter' => 'mysqli',
	'charset' => 'UTF8'
));
Zend_Registry::set('db', $db);
Zend_Db_Table::setDefaultAdapter($db);
/*
 * define values for server
 */
$subdomianArr = array(
	'id' => 0,
	'label' => '总站'
);
Zend_Registry::set('siteInfo', array(
	'id' => $siteArr['id'],
	'domainName' => $domainName,
	'subdomainName' => $siteArr['subdomainName'],
	'libServer' => $siteArr['libServer'],
	'fileServer' => $siteArr['fileServer'],
	'miscServer' => $siteArr['miscServer'],
	'imageServer' => $siteArr['imageServer'],
	'folder' => $siteArr['folder'],
	'requestServerName' => $requestServerName,
	'type' => $siteArr['type'],
	'subdomain' => $subdomianArr
));

define("CONTAINER_PATH", $siteArr['containerPath']);
define("APP_PATH", CONTAINER_PATH.'/app/application');

if(!is_dir('/Users/gavin/Sites/cms-misc/cache/'.$siteArr['id'])) {
	mkdir('/Users/gavin/Sites/cms-misc/cache/'.$siteArr['id']);
}
define("GENERAL_CACHE_PATH", '/Users/gavin/Sites/cms-misc/cache');
define("CACHE_PATH", '/Users/gavin/Sites/cms-misc/cache/'.$siteArr['id']);
define("TEMPLATE_PATH", '/Users/gavin/Sites/cms-misc/template/'.$siteArr['id']);