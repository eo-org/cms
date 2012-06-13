<?php
class Admin_SiteController extends Zend_Controller_Action 
{
	public function indexAction()
    {
    	include_once APP_PATH.'/admin/forms/Info/Edit.php';
    	$form = new Form_Info_Edit();
    	
        $co = App_Factory::_m('Info');
        $doc = $co->fetchOne();
        if(is_null($doc)) {
        	$doc = $co->create();
        }
        $form->populate($doc->toArray());
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
        	$doc->setFromArray($form->getValues());
        	$doc->save();
        	$this->_helper->flashMessenger->addMessage('网站基本信息已保存');
        	$this->_helper->switchContent->gotoSimple('index');
        }
        
        $this->view->form = $form;
        $this->_helper->template->actionMenu(array('save'));
    }
}