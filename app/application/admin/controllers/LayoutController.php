<?php
class Admin_LayoutController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $labels = array(
            'label' => '页面名',
        	'controllerName' => 'Folder Url',
        	'~contextMenu' => ''
        );
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header-front.phtml', array(
            'labels' => $labels,
            'url' => '/admin/layout/get-layout-json/',
            'actionId' => 'id',
        	'click' => array(
            	'action' => 'contextMenu',
            	'menuItems' => array(
            		array('编辑', '/admin/layout/edit/id/')
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
        require APP_PATH.'/admin/forms/Layout/Create.php';
        $form = new Form_Layout_Create();
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
        	$co = App_Factory::_m('Layout');
        	$doc = $co->create();
        	$doc->setFromArray($form->getValues());
        	$doc->default = 0;
        	$doc->save();
        	
			$this->_helper->switchContent->gotoSimple('index', null, null, array(), true);
        }
        
        $this->view->form = $form;
        $this->_helper->template->actionMenu(array('save'));
    }
    
    public function editAction()
    {
        $layoutId = $this->getRequest()->getParam('id');
        $layoutDoc = App_Factory::_m('Layout')->find($layoutId);
        
        require_once APP_PATH.'/admin/forms/Layout/Edit.php';
        $form = new Form_Layout_Edit();
        $form->populate($layoutDoc->toArray());
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
        	$layoutDoc->setFromArray($this->getRequest()->getParams());
			$layoutDoc->save();
            $this->_helper->switchContent->gotoSimple('index', null, null, array(), true);
        }
        
        $this->view->form = $form;
        if($layoutDoc->default == 1) {
        	$this->_helper->template->actionMenu(array('save'));
        } else {
			$this->_helper->template->actionMenu(array('save', 'delete'));
        }
    }
    
    public function editStageAction()
    {
    	$stageId = $this->getRequest()->getParam('stageId');
    	$co = App_Factory::_m('Layout');
    	$doc = $co->addFilter('stage.stageId', $stageId)->fetchOne();
    	
    	$stages = $doc->stage;
    	$tempKey = null;
    	foreach($stages as $key => $val) {
    		if($val['stageId'] == $stageId) {
    			$tempKey = $key;
    			break;
    		}
    	}
    	
    	require_once APP_PATH.'/admin/forms/Layout/EditStage.php';
        $form = new Form_Layout_EditStage();
        $form->populate($stages[$tempKey]);
    	if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
        	$stages[$tempKey]['uniqueId'] = $form->getValue('uniqueId');
			$doc->stage = $stages;
        	$doc->save();
        	
            $this->_helper->switchContent->gotoSimple('index', null, null, array(), true);
        }
        
        $this->view->form = $form;
        $this->_helper->template->actionMenu(array('save'));
    }
    
	public function saveStageJsonAction()
	{
		$jsonString = $this->getRequest()->getParam('jsonString');
		$jsonObj = Zend_Json::decode($jsonString);
		$layoutId = $jsonObj['layoutId'];
		$stagesObj = $jsonObj['stages'];
		
		$co = App_Factory::_m('Layout');
		$doc = $co->find($layoutId);
		if(is_null($doc)) {
			$doc = $co->create();
			
		}
		$oldStage = $doc->stage;
		if(is_null($oldStage)) {
			$oldStage = array();
		}
		$brickCo = App_Factory::_m('Brick');
		foreach($oldStage as $v) {
			$deleted = true;
			foreach($stagesObj as $newValue) {
				if($v['stageId'] == $newValue['stageId']) {
					$deleted = false;
					break;
				}
			}
			if($deleted) {
				$brickCo->delete(array('stageId' => $v['stageId']));
			}
		}
		
		$doc->stage = $stagesObj;
		$doc->save();
		
		return $this->_helper->json(array('result' => 'success'));
	}
    
    public function setPageAction()
    {
//    	$type = $this->getRequest()->getParam('type');
//    	$id = $this->getRequest()->getParam('id');
//        
//        require APP_PATH.'/admin/forms/Layout/SetPage.php';
//        $form = new Form_Layout_SetPage($type);
//        
//        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
//        	
//	        if($type == 'static-artical') {
//	        	$linkRow = Class_Base::_('Artical')->find($id)->current();
//	        } else if($type == 'static-list') {
//	        	$linkRow = Class_Base::_('Group')->find($id)->current();
//	        }
//        	
//        	$linkRow->layoutId = $form->getValue('selectedLayoutId');
//        	$linkRow->save();
//			echo 'success';
//			$this->_helper->viewRenderer->setNoRender(true);
//			exit(0);
//        }
//        
//        $this->view->form = $form;
//        $this->view->id = $id;
//        
//        $this->view->controls = array(
//			'ajax-save' => array('callback' => '/admin/layout/set-page/type/'.$type.'/id/'.$id)
//        );
    }
    
    public function deleteAction()
    {
//    	$layoutId = $this->getRequest()->getParam('layoutId');
//    	
//    	$co = App_Factory::_m('Layout');
//		$doc = $co->find($layoutId);
//    	if(is_null($doc)) {
//    		throw new Exception('layout not found');
//    	}
////    	$db = Zend_Registry::get('db');
////		$linkTable = Class_Base::_('Layout_Brick');
////		$linkTable->delete($db->quoteInto('layoutId = ?', $layoutId));
////		$layout->delete();
//
//    	$co = App_Factory::_m('Brick');
//    	
//    	
//		$this->_helper->switchContent->gotoSimple('index', null, null, array(), true);
    }
    
    public function getLayoutJsonAction()
    {
        $pageSize = 30;
        
	    $tb = Class_Base::_('Layout');
	    $selector = $tb->select()->limitPage(1, $pageSize);
		
        $result = array();
        
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
            		case 'page':
            		    $selector->limitPage(intval($value), $pageSize);
                        $result['currentPage'] = intval($value);
            		    break;
                }
            }
        }
        $rowset = $tb->fetchAll($selector)->toArray();
        $result['data'] = $rowset;
        $result['dataSize'] = Class_Func::count($selector);
        $result['pageSize'] = $pageSize;
        
        if(!isset($result['currentPage'])) {
        	$result['currentPage'] = 1;
        }
        
        return $this->_helper->json($result);
    }
}