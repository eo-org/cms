<?php
class Admin_AdSectionController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$this->view->headLink()->appendStylesheet(Class_Server::libUrl().'/admin/style/ad.css');
		$this->_helper->template->head('广告分组')
			->actionMenu(array(
				array('label' => '添加新分组', 'callback' => '/rest/ad-section', 'method' => 'createAdSection')
			));
	}
}