<?php
class Admin_AdController extends Zend_Controller_Action
{	
	public function indexAction()
	{
		$sectionId = $this->getRequest()->getParam('section-id');
		
		$this->view->sectionId = $sectionId;
		
		$this->view->headLink()->appendStylesheet(Class_Server::libUrl().'/admin/style/ad.css');
		$this->_helper->template->head('广告图')
			->actionMenu(array(
				array('label' => '添加广告图', 'callback' => '/rest/ad', 'method' => 'createAd')
			));
	}
}