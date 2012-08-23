<?php
class Admin_TemplateController extends Zend_Controller_Action
{
	public function indexAction()
	{
		
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
		
		
		
		$db = Zend_Registry::get('db');
		$result = $db->execute('db.copyDatabase("cms_1", "cms_1_cped_php", "108.166.114.11");');
		
		Zend_Debug::dump($result);
		
		die("db copied");
	}
}