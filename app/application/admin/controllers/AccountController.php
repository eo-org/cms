<?php
class Admin_AccountController extends Zend_Controller_Action
{
	public function init()
    {
        $this->view->pageTitle = "我的帐户";
		//$this->view->headLink()->appendStylesheet('/style/admin/account.css');
    }
    
	public function indexAction()
	{
		$id = Class_Session_Admin::getData('id');
		
		$table = Class_Base::_('Admin');
		$row = $table->find($id)->current();
		require_once(APP_PATH.'/admin/forms/Account.php');
		$form = new Form_Account();
		$form->loginName->setValue($row->loginName);
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
		    $posts = $form->getValues();
			if(md5($posts['password_old'].MD5_SALT) == $row->password) {
				$row->setFromArray($posts);
				$row->password = md5($posts['password'].MD5_SALT);
                $row->save();
                $this->_helper->flashMessenger->addMessage('您的密码已修改');
                $this->_helper->switchContent->gotoSimple('index', 'index');
			}
		}
		
		$this->view->form = $form;
		$this->_helper->template->head('我的账户')->actionMenu(array('save'));
	}
}