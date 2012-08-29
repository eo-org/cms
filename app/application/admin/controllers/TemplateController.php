<?php
class Admin_TemplateController extends Zend_Controller_Action
{
	public function indexAction()
	{
		
		
//		$adapter = Zend_Registry::get('mongoAdapter');
//		
//		$db = $adapter->getDb();
//		
//		$collection = $db->selectCollection("system.users");
//		
//		$users = $collection->find();
//		
//		Zend_Debug::dump($users);
//		
//		echo count($users);
//		
//		foreach($users as $user) {
//			
//			Zend_Debug::dump($user);
//			
////			$user->delete();
//			echo "ok<br />";
//		}
//		
//		die();
		
//		$mongo = Zend_Registry::get('mongo');
//		$connection = $mongo->selectDb('admin');
//		
//		$username = 'templateAdmin';
//		$password = 'timeToBuildtempLate';
//		
//		
//		$n = $connection->command(array(
//			'copydbgetnonce' => 1,
//			'fromhost' => '108.166.114.11'
//		));
//		
//		echo $n['nonce'].'<br />';
//		
//		$saltedHash = md5($n['nonce'].$username.md5($username.":mongo:".$password));
//		
//		
//		echo $saltedHash.'<br />';
//		
//		
//		$result = $connection->command(array(
//			'copydb' => 1,
//			'fromhost' => '108.166.114.11',
//			'fromdb' => 'cms_2',
//			'todb' => 'cms_copy_byphp',
//			"username" => $username,
//		    "nonce" => $n["nonce"],
//		    "key" => $saltedHash
//		));
//		
//		
//		
//		Zend_Debug::dump($result);
//		die();
	}
	
	public function activateAction()
	{
		$adapter = Zend_Registry::get('mongoAdapter');
		
		$db = $adapter->getDb();
		
		$collection = $db->selectCollection("system.users");
		
		$users = $collection->find();
		
		$hasUser = false;
		
		foreach($users as $user) {
			$hasUser = true;
			Zend_Debug::dump($user);
			
//			$user->delete();
			echo "ok<br />";
		}
		
		if(!$hasUser) {
		
			$username = 'templateAdmin';
			$password = 'timeToBuildtempLate';
			$collection->insert(array(
				'user' => $username,
				'pwd' => md5($username . ":mongo:" . $password),
				'readOnly' => true
			));
		}
		die();
	}
	
	public function deactivateAction()
	{
		$adapter = Zend_Registry::get('mongoAdapter');
		
		$db = $adapter->getDb();
		
		$collection = $db->selectCollection("system.users");
		
		$collection->remove();
		die();
	}
	
	public function importAction()
	{
		$resetDb = $this->getRequest()->getParam('reset-db');
		$resourceSiteId = $this->getRequest()->getParam('site-id');
		
		$ch = curl_init("http://account.enorange.cn/rest/remote-site/".$resourceSiteId);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$returnedStr = curl_exec($ch);
		
		$returnedObj = Zend_Json::decode($returnedStr, Zend_Json::TYPE_OBJECT);
		if($returnedObj->result == 'fail') {
			die($returnedObj->errMsg);
		} else {
			$returnedObj = $returnedObj->data;
		}
		
		$orgCode = $returnedObj->orgCode;
		$siteFolder = $returnedObj->siteFolder;
		
		$destSiteFolder = Class_Server::getSiteFolder();
		$fromhost = $returnedObj->subdomainName;
		$fromdb = 'cms_'.$returnedObj->remoteId;
		
		if($resetDb == 1) {
			$adapter = Zend_Registry::get('mongoAdapter');
			
			$targetDbName = $adapter->getDbName();
			$db = $adapter->getDb();
			$db->drop();			
			$mongo = Zend_Registry::get('mongo');
			
			$connection = $mongo->selectDb('admin');
			
			$username = 'templateAdmin';
			$password = 'timeToBuildtempLate';
			
			$n = $connection->command(array(
				'copydbgetnonce' => 1,
				'fromhost' => $fromhost
			));
			
			$saltedHash = md5($n['nonce'].$username.md5($username.":mongo:".$password));
			$result = $connection->command(array(
				'copydb' => 1,
				'fromhost' => $fromhost,
				'fromdb' => $fromdb,
				'todb' => $targetDbName,
				"username" => $username,
			    "nonce" => $n["nonce"],
			    "key" => $saltedHash
			));
		}
		if($destSiteFolder != $siteFolder) {
			$fileServerMongo = new Mongo('mongodb://craftgavin:whothirstformagic?@58.51.194.8', array('persist' => 'x'));
			$db = $fileServerMongo->selectDb('service-file');
			$collection = $db->file;
			$cursor = $collection->find(array('orgCode' => $siteFolder));
			
			$service = Class_Api_Oss_Instance::getInstance();
			foreach($cursor as $key => $value) {
				$prefix = $value['orgCode'];
				$urlname = $value['urlname'];
				
				$fromObj = $prefix.'/'.$urlname;
				$resp = $service->copyObject($fromObj, $destSiteFolder.'/'.$urlname);
				if($value['isImage']) {
					$thumbObj = $prefix.'/_thumb/'.$urlname;
					$resp = $service->copyObject($thumbObj, $destSiteFolder.'/_thumb/'.$urlname);
				}
			}
		}
		die('ok');
	}
	
	public function useAction()
	{
//		$templateId = $this->getRequest()->getParam('id');
//		
//		$service = Class_Api_Oss_Instance::getInstance();
		
//		$resp = $service->listObject('public-misc', array(
//			'delimiter' => '/',
//			'prefix' => '5028912c6d5461a512000000/',
//			'max-keys' => 50
//		));
//		for($i = 1; $i < 5; $i++) {
//			$resp = $service->copyObject('t4/template.css', 'apple/t-'.$i.'.css');
//			Zend_Debug::dump($resp);
//		}
		
		
//		
		
//		$result = $db->execute('db.copyDatabase("cms_1", "cms_1_cped_php", "108.166.114.11");');
		
//		
//		$mongo = Zend_Registry::get('mongo');
//		$connection = $mongo->selectDb('admin');
//		
//		
//		$n = $connection->command(array(
//			'copydbgetnonce' => 1,
//			'fromhost' => '108.166.114.11'
//		));
//		
//		Zend_Debug::dump($n);
//		
//		$result = $connection->command(array(
//			'copydb' => 1,
//			'fromhost' => '108.166.114.11',
//			'fromdb' => 'cms_1',
//			'todb' => 'cms_copy_byphp'
//		));
//		
//		
//		
//		
//		Zend_Debug::dump($result);
		
		
//		$m = new Mongo('mongodb://craftgavin:whothirstformagic?@58.51.194.8', array('persist' => 'x'));
//		
//		$conn = $m->selectDb('service-file');
//		
//		$collection = $conn->file;
//		
//		$cursor = $collection->find(array('orgCode' => '502ca9bb6d54614534000000'));
//		
//		foreach($cursor as $key => $value) {
//			$prefix = $value['orgCode'];
//			$urlname = $value['urlname'];
//			
//			$fromObj = $prefix.'/'.$urlname;
//			
//			$resp = $service->copyObject($fromObj, 'apple/'.$urlname);
//			Zend_Debug::dump($resp);
//			if($value['isImage']) {
//				$thumbObj = $prefix.'/_thumb/'.$urlname;
//				
//				$resp = $service->copyObject($thumbObj, 'apple/_thumb/'.$urlname);
//				Zend_Debug::dump($resp);
//			}
//			
//			
//		}
		
		die("db copied");
	}
}