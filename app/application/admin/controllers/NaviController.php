<?php
class Admin_NaviController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->pageTitle = "目录管理";
    }
    
    public function indexAction()
    {
    	$this->_helper->template->head('目录组列表');
    	
        $hashParam = $this->getRequest()->getParam('hashParam');
        
        $labels = array(
            'name' => '目录组名',
            'title' => '介绍',
        	'~contextMenu' => ''
        );
        $partialHTML = $this->view->partial('select-search-header-front.phtml', array(
            'labels' => $labels,
            'selectFields' => array(
                
            ),
            'url' => '/admin/navi/get-navi-json/',
            'actionId' => 'id',
            'click' => array(
            	'action' => 'contextMenu',
            	'menuItems' => array(
            		array('编辑', '/admin/navi/edit/id/')
            	)
            ),
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));
		$this->_helper->template->actionMenu(array('create'));
        $this->view->assign('partialHTML', $partialHTML);
    }
    
    public function createAction()
    {
//        require APP_PATH.'/admin/forms/Section/Edit.php';
//        $form = new Form_Edit();
//        
//        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
//            $row = Class_Base::_('Category_Section')->createRow($form->getValues());
//            $row->save();
//            $this->_helper->switchContent->gotoSimple('index');
//        }
//        
//        $this->view->form = $form;
//        $this->_helper->template->actionMenu(array('save'));
		$this->_forward('edit');
    }
    
    public function editAction()
    {
    	$co = App_Factory::_('Navi');
    	$doc = $co->fetchOne();
    	if(is_null($doc)) {
    		$doc->label = '主导航';
    		$doc->navi = array(
    			array('label' => '首页', 'url' => '/'),
    			array('label' => '关于公司', 'url' => '/about/us.shtml'),
    			array('label' => '公司产品', 'url' => '/company/products.shtml'),
    			array('label' => '分子机构', 'url' => '/branch/a.shtml', 'children' => array(
    				'label' => '分子机构A', 'url' => '/branch/a.shtml', 'children' => array(
    					array('label' => '首页', 'url' => '/'),
    					array('label' => '首页', 'url' => '/')
    				)
    			)
    		);
    	}
    	
    	
    	
    	
    	
    	
    	
//        $id = $this->getRequest()->getParam('id');
//        
//        require APP_PATH.'/admin/forms/Section/Edit.php';
//        $form = new Form_Edit();
//        $row = Class_Base::_('Category_Section')->find($id)->current();
//        
//    	if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
//            $row->setFromArray($form->getValues())
//            	->save();
//            
//            $this->_helper->flashMessenger->addMessage('您对目录组的修改已保存！');
//            $this->_helper->switchContent->gotoSimple('index');
//        }
//        
//        $form->populate($row->toArray());
//        $this->view->form = $form;
//        $this->_helper->template->actionMenu(array('save', 'delete'));
		
    }
    
    public function deleteAction()
    {
//    	$id = $this->getRequest()->getParam('id');
//        
//        $row = Class_Base::_('Category_Section')->find($id)->current();
//        if(!is_null($row)) {
//        	$row->delete();
//        }    	
//        $this->_helper->flashMessenger->addMessage('该目录组已被删除！');
//        $this->_helper->switchContent->gotoSimple('index');
    }
    
    public function getNaviJsonAction()
    {
        $pageSize = 20;
		$currentPage = 1;
		
		$co = App_Factory::_m('Navi');
		$co->setFields(array('id', 'label'));
		$queryArray = array();
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                	case 'label':
                		$co->addFilter('label', new MongoRegex("/^".$value."/"));
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
        $co->sort('_id', -1);
        
		$co->setPage($currentPage)->setPageSize($pageSize);
		$data = $co->fetchAll(true);
		$dataSize = $co->count();
		
		$result['data'] = $data;
        $result['dataSize'] = $dataSize;
        $result['pageSize'] = $pageSize;
        $result['currentPage'] = $currentPage;
        
        return $this->_helper->json($result);
    }
}