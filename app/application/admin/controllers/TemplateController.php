<?php
class Admin_TemplateController extends Zend_Controller_Action
{
	public function indexAction()
	{
		
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
		
		/****************************/
		/*COPY MONGO DATABASE*/
		/****************************/
		//---->get origin site info through curl
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
		$siteFolder = $returnedObj->siteId;
		$destSiteFolder = Class_Server::getSiteFolder();
		curl_close($ch);
		
		//---->reset database by copydb command
		if($resetDb == 1) {
			$fromhost = $returnedObj->subdomainName;
			$fromdb = 'cms_'.$returnedObj->remoteId;
			
			$adapter = Zend_Registry::get('mongoAdapter');
			$todb = $adapter->getDbName();
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
				'todb' => $todb,
				"username" => $username,
			    "nonce" => $n["nonce"],
			    "key" => $saltedHash
			));
		}
		
		/****************************/
		/*DUP ALI FILES & FILE SERVER DATA*/
		/****************************/
		/*
		$fileServerKey = 'gioqnfieowhczt7vt87qhitonqfn8eaw9y8s90a6fnvuzioguifeb';
		$time = time();
		$ch = curl_init("http://file.eo.test/".$resourceSiteId."/default/copy/to-site/id/".$siteFolder);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Sig: asdfded'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Time: '.$time));
		
		$returnedStr = curl_exec($ch);
		$returnedObj = Zend_Json::decode($returnedStr, Zend_Json::TYPE_OBJECT);
		*/
		
		
//			$fileServerMongo = new Mongo('mongodb://craftgavin:whothirstformagic?@58.51.194.8', array('persist' => 'x'));
//			$db = $fileServerMongo->selectDb('service-file');
//			$collection = $db->file;
//			$cursor = $collection->find(array('orgCode' => $siteFolder));
//			
//			$service = Class_Api_Oss_Instance::getInstance();
//			foreach($cursor as $key => $value) {
//				$prefix = $value['orgCode'];
//				$urlname = $value['urlname'];
//				
//				$fromObj = $prefix.'/'.$urlname;
//				$resp = $service->copyObject($fromObj, $destSiteFolder.'/'.$urlname);
//				if($value['isImage']) {
//					$thumbObj = $prefix.'/_thumb/'.$urlname;
//					$resp = $service->copyObject($thumbObj, $destSiteFolder.'/_thumb/'.$urlname);
//				}
//			}
			
			
			
			
			
			
			
		die('ok');
	}
	
	public function testAction()
	{
		$resourceSiteId = '502a00446d5461593a000001';
		$toSiteId = '111-test';
		
		$time = time();
		$fileServerKey = 'gioqnfieowhczt7vt87qhitonqfn8eaw9y8s90a6fnvuzioguifeb';
		$sig = md5($resourceSiteId.$time.$toSiteId.$fileServerKey);
		
		echo $sig;
		
		$ch = curl_init("http://file.eo.test/".$resourceSiteId."/default/copy/to-site/id/".$toSiteId);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'X-Sig: '.$sig,
			'X-Time: '.$time
		));
		
		$returnedStr = curl_exec($ch);
//		$returnedObj = Zend_Json::decode($returnedStr, Zend_Json::TYPE_OBJECT);
		
		Zend_Debug::dump($returnedStr);
		die();
	}
}