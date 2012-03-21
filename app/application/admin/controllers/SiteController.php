<?php
class Admin_SiteController extends Zend_Controller_Action 
{
	public function indexAction()
    {
    	include_once APP_PATH.'/admin/forms/Site/Edit.php';
    	$form = new Form_Site_Edit();
    	
        $tb = new Zend_Db_Table('site_general');
        $row = $tb->fetchAll()->current();
        $form->populate($row->toArray());
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
        	$row->setFromArray($form->getValues());
        	$row->save();
        	$this->_helper->flashMessenger->addMessage('网站基本信息已保存');
        	$this->_helper->switchContent->gotoSimple('index');
        }
        
        $this->view->form = $form;
        $this->_helper->template->actionMenu(array('save'));
    }
}