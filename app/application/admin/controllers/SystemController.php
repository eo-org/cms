<?php
class Admin_SystemController extends Zend_Controller_Action 
{
	public function indexAction()
	{
		$this->_helper->template->head('系统管理');
	}
	
	public function clearAllCacheAction()
	{
		$backendOptions = array(
	        'cache_dir' => CACHE_PATH
	    );
		$cache = new Zend_Cache_Backend_File($backendOptions);
		$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
		$this->_helper->flashMessenger->addMessage('缓存已清除！');
		$this->_helper->switchContent->gotoSimple('index');
	}
	
	public function generateBrickCssAction()
	{
		$filename = CONTAINER_PATH."/temp/".md5(time()).'.css';
		$fileHandle = fopen($filename, 'w') or die('fail to open file');
		$tb = new Zend_Db_Table('css');
		$cssRowset = $tb->fetchAll($tb->select()->where('type = ?', 'brick'));
		foreach($cssRowset as $css) {
			$str = $css->content;
			$str.= "\n/******************/\n";
			fwrite($fileHandle, $str);
		}
		fclose($fileHandle);
		
		$siteInfo = Zend_Registry::get('siteInfo');
		$siteId = $siteInfo['id'];
		
		$cu = new Class_Service_Curl();
		$response = $cu->putFile($filename, $siteId.'/system/brick.css', Class_HTML::server('file'));
		$result = $response;
		if($result == 'success') {
			$db = Zend_Registry::get('db');
			$db->query("update css set `inFile` = 1 where `type` = 'brick'");
			unlink($filename);
		}
		$this->_helper->flashMessenger->addMessage('Brick.css文件已更新!');
		$this->_helper->switchContent->gotoSimple('index');
	}
	
	public function headFileAction()
	{
		$docs = App_Factory::_m('HeadFile')->fetchDoc();
		
		$this->view->headLink()->appendStylesheet(Class_Server::libUrl().'/admin/style/headfile.css');

		
		$this->view->docs = $docs;
	}
}