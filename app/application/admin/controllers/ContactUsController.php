<?php
class Admin_ContactUsController extends Zend_Controller_Action
{
	public function indexAction()
	{
		require_once APP_PATH.'/admin/forms/ContactUs/Edit.php';
		$form = new Form_ContactUs_Edit();
		$tb = new Zend_Db_Table('site_contact');
		$siteInfo = Zend_Registry::get('siteInfo');
		$row = $tb->find($siteInfo['subdomain']['id'])->current();
		if(is_null($row)) {
			$row = $tb->createRow();
		}
		
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$row->setFromArray($form->getValues());
			$row->id = $siteInfo['subdomain']['id'];
			$row->save();
		}
		$form->populate($row->toArray());
		$this->view->form = $form;
	}
}