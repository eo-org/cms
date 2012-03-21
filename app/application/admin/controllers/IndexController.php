<?php
class Admin_IndexController extends Zend_Controller_Action 
{
	public function indexAction()
    {
        $this->_helper->template->head('后台管理器');
    }
    
    public function loginAction()
    {
        $this->view->pageTitle = "管理员登录";
		require_once(APP_PATH.'/admin/forms/Index/Login.php');
		$loginForm = new Form_Index_Login();
		if($this->getRequest()->isPost()  && $loginForm->isValid($this->getRequest()->getPost())) {
			$tb = Class_Base::_('Admin');
            $select = $tb->select()->where("loginName = ?", $loginForm->getValue('loginName'))
            	->where('password = ?', md5($loginForm->getValue('password').MD5_SALT));
		    $admin = $tb->fetchRow($select);
		    $csa = Class_Session_Admin::getInstance();
			if($csa->webAdminLogin($admin)) {
				$this->_helper->redirector->gotoSimple('index');
			}
		}
		
		$this->view->form = $loginForm;
		
    }
    
    public function designerLoginAction()
    {
        $this->view->pageTitle = "设计师登录";
		require_once(APP_PATH.'/admin/forms/Index/SuperLogin.php');
		$loginForm = new Form_Index_SuperLogin();
		if($this->getRequest()->isPost()  && $loginForm->isValid($this->getRequest()->getPost())) {
			$result = Class_Service_Curl::get($this->getRequest()->getPost(), 'http://www.enorange.com/login.php');
			$loginArr = Zend_Json::decode($result);
			$csa = Class_Session_Admin::getInstance();
			if($csa->designerLogin($loginArr)) {
				$this->_helper->redirector->gotoSimple('index');
			}
		}
		
		$this->view->form = $loginForm;
    }
    
	public function logoutAction()
	{
		$csa = Class_Session_Admin::getInstance();
		if($csa->isLogin()) {
			$csa->logout();
		}
		$this->_helper->redirector->gotoSimple('index', 'index', 'default');
	}
	
	public function noPrivilegeAction()
	{
	    
	}
}
