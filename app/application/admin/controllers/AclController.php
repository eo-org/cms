<?php
class Admin_AclController extends Zend_Controller_Action
{
	public function indexAction()
	{
	    $labels = array(
			'name' => '管理员角色',
			'~contextMenu' => ''
        );
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header-front.phtml', array(
            'labels' => $labels,
        	'selectFields' => array(),
            'url' => '/admin/acl/get-group-json/',
            'actionId' => 'id',
        	'click' => array(
            	'action' => 'contextMenu',
            	'menuItems' => array(
            		array('编辑', '/admin/acl/edit/id/')
            	)
            ),
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));

        $this->view->assign('partialHTML', $partialHTML);
        $this->_helper->template->actionMenu(array('create'));
	}
	
	public function createAction()
	{
	    $form = new Zend_Form();
        $nameEle = new Zend_Form_Element_Text('name', array(
        	'value' => '',
            'label' => '权限名称',
            'required'=>true
        ));
        $form->addElement($nameEle);
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
	        $db = Class_Base::_('Admin_Group');
	        $row = $db->createRow();
	        $row->setFromArray($form->getValues());
	        $row->save();
	        $this->_helper->redirector->gotoSimple('edit', null, null, array('id' => $row->id));
	    }
        
	    $this->view->form = $form;
	    $this->_helper->template->head('创建新角色')
	    	->actionMenu(array('save'));
	}
	
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		
		$db = Zend_Registry::get('siteDb');
    	$resTb = new Zend_Db_Table(array('db' => $db, 'name' => 'resource'));
    	$resRowset = $resTb->fetchAll();
    	
		$ruleTb = new Zend_Db_Table('admin_rule');
		if($this->getRequest()->isPost()) {
			$selectedResArr = $this->getRequest()->getParam('res');
			
			$resourceArr = array();
			foreach($selectedResArr as $item => $tmp) {
				$resourceArr[$item] = 'index';
//				if(array_key_exists($resRow->id, $selectedResArr)) {
//					if(!array_key_exists($resRow->controllerName, $resourceArr)) {
//						$resourceArr[$resRow->controllerName] = array();
//					}
//					$resourceArr[$resRow->controllerName][] = $resRow->actionName;
//				}
			}
			
			$ruleTb->delete($ruleTb->getAdapter()->quoteInto('roleId = ?', $id));
			foreach($resourceArr as $resource => $privileges) {
				$privilegeString = implode(',', $privileges);
				$newRow = $ruleTb->createRow();
				$newRow->roleId = $id;
				$newRow->resource = $resource;
				$newRow->privileges = $privilegeString;
				$newRow->save();
			}
			$this->_helper->flashMessenger->addMessage('新的权限已保存！');
			$this->_helper->switchContent->gotoSimple('index');
		}
		
//		$linkController = new Class_Link_Controller($resRowset);
//    	$renderer = new Class_Link_Renderer_Checkbox();
//		Class_Link_Controller::setRenderer($renderer);
		
		
		$ruleRowset = $ruleTb->fetchAll($ruleTb->select()->where('roleId = ?', $id));
		
		$resSelectedIdArr = array();
		foreach($ruleRowset as $ruleRow) {
			$resSelectedIdArr[] = $ruleRow->resource;
//			$privilegeArr = explode(',', $ruleRow->privileges);
//			foreach($privilegeArr as $p) {
//				foreach($resRowset as $resRow) {
//					if($resRow->controllerName == $ruleRow->resource) {
//						$resSelectedIdArr[] = $resRow->id;
//					}
//				}
//			}
		}
		
//		$renderer->setChecked($resSelectedIdArr);
		
//		$this->view->linkHead = $linkController->getLinkHead();

		$this->view->resourceRowset = $resRowset;
		$this->view->resSelectedIdArr = $resSelectedIdArr;
		
		$tb = Class_Base::_('Admin_Group');
	    $row = $tb->find($id)->current();
		$this->_helper->template->actionMenu(array('save'))->head('修改权限:<em>'.$row->name.'</em>');
	}
	
	public function getGroupJsonAction()
	{
		$pageSize = 20;
        
	    $tb = new Zend_Db_Table('admin_group');
	    $selector = $tb->select()
	    	->limitPage(1, $pageSize);
        $result = array();
        
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
            		case 'page':
            		    $selector->limitPage(intval($value), $pageSize);
                        $result['currentPage'] = intval($value);
            		    break;
            		case 'selectedIds':
					    if($value != 'all') {
					        $selector->where('brickId in (?)', explode(',', $value));
					    }
					    break;
                }
            }
        }
        $rowset = $tb->fetchAll($selector)->toArray();
        $result['data'] = $rowset;
        $result['dataSize'] = Class_Func::count($selector);
        $result['pageSize'] = $pageSize;
        
        if(empty($result['currentPage'])) {
        	$result['currentPage'] = 1;
        }
        return $this->_helper->json($result);
	}
}