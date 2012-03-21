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
        	$tb = Class_Base::_('Layout');
        	$row = $tb->createRow();
        	$row->setFromArray($form->getValues());
        	$row->default = 0;
        	$row->save();
        	
//        	$linkRow->save();
			$this->_helper->switchContent->gotoSimple('index');
        }
        
        $this->view->form = $form;
        
//        $this->view->controls = array(
//			'ajax-save' => array('callback' => '/admin/layout/create/')
//        );
        $this->_helper->template->actionMenu(array('save'));
    }
    
    public function editAction()
    {
        $layoutId = $this->getRequest()->getParam('id');
        $layoutRow = Class_Base::_('Layout')->find($layoutId)->current();
        
        require_once APP_PATH.'/admin/forms/Layout/Edit.php';
        $form = new Form_Layout_Edit();
        $form->populate($layoutRow->toArray());
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
        	$layoutRow->setFromArray($this->getRequest()->getParams());
			$layoutRow->save();
            $this->_helper->switchContent->gotoSimple('index');
        }
        
        $this->view->form = $form;
        if($layoutRow->default == 1) {
        	$this->_helper->template->actionMenu(array('save'));
        } else {
			$this->_helper->template->actionMenu(array('save', 'delete'));
        }
    }
    
    public function editStageAction()
    {
    	$stageId = $this->getRequest()->getParam('stageId');
    	$tb = new Zend_Db_Table('layout_stage');
    	$row = $tb->find($stageId)->current();
    	
    	require_once APP_PATH.'/admin/forms/Layout/EditStage.php';
        $form = new Form_Layout_EditStage();
        $form->populate($row->toArray());
    	if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
        	$row->setFromArray($this->getRequest()->getParams());
			$row->save();
            $this->_helper->switchContent->gotoSimple('index', null, null, array(), true);
        }
        
        $this->view->form = $form;
        $this->_helper->template->actionMenu(array('save'));
    }
    
    public function setPageAction()
    {
    	$type = $this->getRequest()->getParam('type');
    	$id = $this->getRequest()->getParam('id');
        
        require APP_PATH.'/admin/forms/Layout/SetPage.php';
        $form = new Form_Layout_SetPage($type);
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
        	
	        if($type == 'static-artical') {
	        	$linkRow = Class_Base::_('Artical')->find($id)->current();
	        } else if($type == 'static-list') {
	        	$linkRow = Class_Base::_('Group')->find($id)->current();
	        }
        	
        	$linkRow->layoutId = $form->getValue('selectedLayoutId');
        	$linkRow->save();
			echo 'success';
			$this->_helper->viewRenderer->setNoRender(true);
			exit(0);
        }
        
        $this->view->form = $form;
        $this->view->id = $id;
        
        $this->view->controls = array(
			'ajax-save' => array('callback' => '/admin/layout/set-page/type/'.$type.'/id/'.$id)
        );
    }
    
    public function deleteAction()
    {
    	$layoutId = $this->getRequest()->getParam('layoutId');
    	$layout = Class_Base::_('Layout')->find($layoutId)->current();
    	if(is_null($layout)) {
    		throw new Exception('layout not found');
    	}
    	$db = Zend_Registry::get('db');
		$linkTable = Class_Base::_('Layout_Brick');
		$linkTable->delete($db->quoteInto('layoutId = ?', $layoutId));
		$layout->delete();
//    	if($this->getRequest()->getParam('format') == 'html') {
//			echo 'success';
//			$this->_helper->viewRenderer->setNoRender(true);
//		} else {
//			$this->_helper->redirector->gotoSimple('index');
//		}
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