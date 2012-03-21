<?php
class User_AddressController extends Zend_Controller_Action
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('list', 'html')
        	->addActionContext('edit-json', 'html')
            ->initContext();
	}
	
	public function indexAction()
    {
        $this->view->headTitle("地址管理 ");

        $csu = Class_Session_User::getInstance();
    	$addressList = App_Factory::_am('Address')->setFields(array('fullAddress'))
    		->addFilter('userId', $csu->getUserId())
    		->fetchAll(true);
    	$this->view->addressList = $addressList;
    }
    
    public function editJsonAction()
    {
    	require APP_PATH.'/user/forms/Address.php';
    	$form = new Form_Address();
    	if($this->getRequest()->isPost() ) {
    		if($form->isValid($this->getRequest()->getParams())) {
    			$addressDoc = App_Factory::_am('Address')->create();
    			$addressDoc->setFromArray($form->getValues());
    			$addressDoc->save();
    			
    			$this->getResponse()->setHeader('result', 'success')
    				->setHeader('dataType', 'json');
    			$this->_helper->json(array('id' => $addressDoc->getId(), 'fullAddress' => $addressDoc->fullAddress));
    		} else {
    			$this->getResponse()->setHeader('result', 'fail');
    			$this->view->errorMsg = $form->getMessages();
    			$this->render('error-msg');
    		}
    	}
    	$this->view->form = $form;
    }
    
    public function listAction()
    {
    	$csu = Class_Session_User::getInstance();
    	$addressList = App_Factory::_am('Address')->setFields(array('fullAddress'))
    		->addFilter('userId', $csu->getUserId())
    		->fetchAll(true);
    	$this->view->addressList = $addressList;
    }
    
    /**
     * 
     * return address list as a json form
     */
    public function getListJsonAction()
    {
    	$csu = Class_Session_User::getInstance();
    	$addressList = App_Factory::_am('Address')->setFields(array('fullAddress'))
    		->addFilter('userId', $csu->getUserId())
    		->fetchAll(true);
    	 
    	$this->_helper->json($addressList);
    }
    
	public function deleteAction()
    {
        $addressId = intval($this->getRequest()->getParam('id'));
        $addressObj = new Class_Model_Address();
        $addressObj->setData('addressId', $addressId)->setData('active', 1)->setData('customerId',Class_Customer::getData('entityId'))->load();
       // echo $addressObj->getData('addressId') ;
        if ($addressObj->getData('addressId') == $addressId) {
            $addressObj->setData('active', 0)->save();
          //  echo 'LLLL';
        }
        $this->_redirector->gotoSimple('address-list', 'user');
    }
}