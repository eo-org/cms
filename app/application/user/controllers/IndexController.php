<?php
class User_IndexController extends Zend_Controller_Action
{
	public function preDispatch()
    {
        $actionName = $this->getRequest()->getActionName();
        $csu = Class_Session_User::getInstance();
        if (! $csu->isLogin()) {
            $pageAllowed = false;
            switch ($actionName) {
                case 'login':
                case 'register':
                case 'forgot-password':
                    $pageAllowed = true;
                    break;
            }
            if (! $pageAllowed) {
                $this->_helper->redirector->gotoSimple('login', 'index');
            }
        } else {
            switch ($actionName) {
                case 'login':
                case 'register':
                    $this->_helper->redirector->gotoSimple('index', 'index');
                    break;
            }
        }
    }
	
	public function indexAction()
	{
		$this->view->headTitle("用户中心首页");


		//        $rawData = Class_Customer::getData();
		//
		//        $customer = Class_Core::_('Customer')->setData($rawData);
		//        $medalAttr = Class_Core::_('Eav_Attribute')->setData('code', 'customer_lottery_point')
		//            ->load();
		//
		//        $medalAttr->loadValuesForEntity($customer);
		//        $medalCount = 0;
		//        if(count($medalAttr->getSelectedValue()) > 0) {
		//            $medalCount = $medalAttr->getSelectedValue();
		//            $medalCount = $medalCount[0];
		//        }
		//        $this->view->customer = $rawData;
	}

	public function loginAction()
	{
		require_once APP_PATH."/user/forms/Login.php";
		$loginForm = new Form_Login();
		$this->view->loginForm = $loginForm;
		
		if($this->getRequest()->isPost() && $loginForm->isValid($this->getRequest()->getPost())) {
			$csu = Class_Session_User::getInstance();
			$result = $csu->login($loginForm->getValues());
			if (! $result) {
				$loginForm->setDescription('用户不存在或者密码错误！');
			}
			if ($result) {
				$this->_backToRef();
			}
		}
	}

	public function logoutAction()
	{
		$csu = Class_Session_User::getInstance();
		$csu->logout();
		$this->_helper->redirector->gotoSimple('index', 'index', 'default');
	}

	public function registerAction()
	{
		require_once APP_PATH."/user/forms/Register.php";
		$registerForm = new Form_Register();
		$this->view->registerForm = $registerForm;
		
		if($this->getRequest()->isPost() && $registerForm->isValid($this->getRequest()->getParams())) {
			$csu = Class_Session_User::getInstance();
			$result = $csu->register($registerForm->getValue('loginName'), $registerForm->getValue('password'));
			
			if ($result) {
				$this->_backToRef();
			}
		}
	}

	/**
	 * show and update personal information
	 *
	 */
	public function editAction()
	{
		$this->view->headTitle("编辑用户信息");
		$csu = Class_Session_User::getInstance();
		require_once APP_PATH."/user/forms/User/Edit.php";
		$form = new Form_User_Edit();
		
		$this->view->form = $form;
		$this->view->loginName = $csu->getLoginName();
	}

	public function addFavoriteAction()
	{
		$pid = intval($this->getRequest()->getParam('pid'));

		if(!Class_Customer::isLogin() || !Class_Customer::isRegistered()){
			$this->_redirector->gotoSimple('login', 'user');
		}
		if(intval($pid) == 0){
			throw new Exception('invalid product id');
		}
		$product = Class_Core::_('product')
		->setData('entityId',$pid)
		->load();
		if(is_null($product->getData('entityId'))){
			throw new Exception(" this product is not exist");
		}
		$customerId = Class_Customer::getData('entityId');
		$neddAddFavorite = true;
		$favoriteList = Class_Core::_list('favorite')
		->addUserFilter($customerId)
		->load()
		->getListData();

		if(count($favoriteList)){
			foreach($favoriteList as $favorite){
				if($favorite->getData('productId') == $pid){
					$neddAddFavorite = false;
					break;
				}
			}
		}
		if($neddAddFavorite){
			$favorite = Class_Core::_('favorite')
			->create()
			->setData('entityId',$customerId)
			->setData('productId',$pid)
			->save();
		}
		$this->_redirector->gotoSimple('index', 'product', null, array('id' => $pid));
	}

	/**
	 * show user's favorite in account function
	 *
	 */
	public function favoriteAction()
	{
		$this->view->headTitle("收藏夹 ");
		$customerId = Class_Customer::getData('entityId');
		$favoriteList = Class_Core::_list('favorite')
		->addUserFilter($customerId)
		->addProductTable(array('entityAttributeSetId' , 'name' , 'sku','price','origPrice'))
		->addProductGraphicTable(array('value' , 'alt'))
		->load()
		->getListData();
	  
		$this->view->favorites = $favoriteList;
	}

	/**
	 * user account's cancel favorite
	 *
	 */
	public function cancelFavoriteAction()
	{
		$id = $this->getRequest()->getParam('id');

		if(intval($id) == 0){
			throw new Exception('invalid  id');
		}
		$favorite = Class_Core::_('favorite')
		->setData('id',$id)
		->load();
		 
		if (! is_null($favorite->getData('id'))) {
			$favorite->setData('active',0)->save();
		}
		$this->_redirector->gotoSimple('favorite', 'user');
	}

	public function getCityListJsonAction()
	{
		$provinceId = $this->getRequest()->getParam('provinceId');
		$jsonObj = array();
		if($provinceId == 0) {
			$jsonObj['cities'] = array('' => '--------');
			$this->_helper->json($jsonObj);
		}
		$address = Class_Core::_('Address');
		$cityCollectionData = $address->getCityCollectionData($provinceId);
		$jsonObj['cities'] = $cityCollectionData;
		$this->_helper->json($jsonObj);
	}

	public function emailVerifyAction()
	{
		$email = base64_decode($this->getRequest()->getParam('email'));
		$code = $this->getRequest()->getParam('code');

		$customer = Class_Core::_('Customer')
		->setData('email', $email)
		->load();

		$generatedCode = md5($customer->getData('password').$customer->getData('entityId'));
		if($code == $generatedCode) {
			if($customer->getData('emailVerified') == 0) {
				$lotteryPromoteCustomer = Class_Core::_('Customer_Decorator_Lottery', $customer, array(12, 6, 3));
				$lotteryPromoteCustomer->promote();
				$customer->setData('emailVerified', 1)
				->save();
				Class_Customer::setErrorMsg(array('您的邮箱已通过验证，感谢您的配合'));
			} else {
				Class_Customer::setErrorMsg(array('您的邮箱之前已经验证通过，无需验证两次'));
			}
			Class_Customer::login($customer);
			$this->_helper->redirector->gotoSimple('index', 'user');
		}
	}

	public function sendEmailJsonAction()
	{
		$mailType = $this->getRequest()->getParam('mailType');
		$customerData = Class_Customer::getData();
		$result = false;
		switch($mailType) {
			case 'emailVerify':
				$result = Class_Push::email($customerData['email'], null, $mailType);
				break;
			default:
				break;
		}
		if($result) {
			return $this->_helper->json(array('result' => 'success'));
		} else {
			return $this->_helper->json(array('result' => 'fail'));
		}
	}

	protected function _backToRef()
	{
		$ref = base64_decode($this->getRequest()->getParam('ref'));
		$client = new Zend_Session_Namespace('client');
		if(empty($ref)) {
			$ref = $client->lastVisitedPage;
		}
		switch ($ref) {
			case 'order':
				$this->_redirector->gotoSimple('index', 'order');
				break;
			case 'login':
				$this->_redirector->gotoSimple('index', 'user');
				break;
			case 'register':
				$this->_redirector->gotoSimple('index', 'user');
				break;
			default:
				header("Location:http://".$_SERVER ['HTTP_HOST'].'/user/');
		}
	}
}