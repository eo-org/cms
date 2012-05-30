<?php
class Admin_UserController extends Zend_Controller_Action
{
    public function indexAction()
    {    	
        $labels = array(
        	'loginName' => '登陆名',
        	'created' => '注册日期',
        	'status' => '用户状态',
        	'~contextMenu' => ''
        );
        
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header-front.phtml', array(
            'labels' => $labels,
            'selectFields' => array(
                'id' => NULL,
        		'created' => NULL,
        		'status' => array('active' => '激活', 'inactive' => '冻结')
            ),
            'url' => '/admin/user/get-user-json/',
            'actionId' => 'id',
            'click' => array(
            	'action' => 'contextMenu',
            	'menuItems' => array(
            		array('编辑', '/admin/user/edit/id/')
            	)
            ),
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));

        $this->view->assign('partialHTML', $partialHTML);
//        $this->_helper->template->head('用户列表')->actionMenu(array('create'));
	}
	
//	public function createAction()
//	{
//		$attributesetCo = new Class_Mongo_Attributeset_Co();
//		
//		$attrDocSet = $attributesetCo->setFields(array('label'))
//			->addFilter('type', 'user')
//			->fetchAll();
//			
//		$this->view->attrRowset = $attrDocSet;
//		$this->_helper->template->head('创建新产品');
//	}
	
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		
		$userCo = App_Factory::_m('User');
//		$attributesetCo = App_Factory::_m('Attributeset');
		
		if(empty($id)) {
			$userDoc = $userCo->create();
			$userDoc->attributesetId = $attributesetId;
		} else {
			$userDoc = $userCo->find($id);
		}
		
//		$attributesetId = $userDoc->attributesetId;
//		$attributesetDoc = $attributesetCo->find($attributesetId);
		
		require APP_PATH."/admin/forms/User/Edit.php";
		$form = new Form_User_Edit();
		$form->populate($userDoc->toArray());
		
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$result = $userDoc->setFromArray($form->getValues())
				->save(false);
			
			if($result) {
				$this->_helper->flashMessenger->addMessage('用户已经成功保存');
				$this->_helper->switchContent->gotoSimple('index');
			}
		}
		
		$this->view->form = $form;
		$this->view->userDoc = $userDoc;
		
		if(empty($id)) {
			$this->_helper->template->actionMenu(array('save'));
			$this->_helper->template->head('创建新产品');
		} else {
			$this->_helper->template->actionMenu(array('save', 'delete'));
			$this->_helper->template->head('编辑用户');
		}
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		
		$userCo = App_Factory::_m('User');
		$userDoc = $productCo->find($id);
		
		if($userDoc == null){
			throw new Class_Exception_Pagemissing();
		}
		$loginName = $userDoc->loginName;
		$userDoc->delete();
		$this->_helper->flashMessenger->addMessage('用户 '.$loginName.' 已经删除');
		$this->_helper->switchContent->gotoSimple('index');
	}
	
	public function getUserJsonAction()
    {
    	$pageSize = 20;
		$currentPage = 1;
		
		$userCo = App_Factory::_m('User');
		$userCo->setFields(array('loginName', 'created', 'status'));
		$queryArray = array();
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                	case 'email':
                		$userCo->addFilter('email', new MongoRegex("/^".$value."/"));
                		break;
                    case 'page':
            			if(intval($value) != 0) {
            				$currentPage = $value;
            			}
                        $result['currentPage'] = intval($value);
            		    break;
                }
            }
        }
        $userCo->sort('$natural', -1);
        
		$userCo->setPage($currentPage)->setPageSize($pageSize);
		$data = $userCo->fetchAll(true);
		$dataSize = $userCo->count();
		
		$result['data'] = $data;
        $result['dataSize'] = $dataSize;
        $result['pageSize'] = $pageSize;
        $result['currentPage'] = $currentPage;
        
        return $this->_helper->json($result);
    }
}