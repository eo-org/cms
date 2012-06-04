<?php
class Admin_AttributesetController extends Zend_Controller_Action
{
	protected $_type = 'product';
	
	public function init()
	{
		$this->view->headLink()->appendStylesheet(Class_Server::libUrl().'/admin/style/attributeset-editor.css');
		$this->view->headScript()->appendFile(Class_Server::libUrl().'/admin/script/attributeset-editor.js');
		
		$type = $this->getRequest()->getParam('type');
		if(!is_null($type)) {
			$this->_type = $this->getRequest()->getParam('type');
		}
	}
	
    public function indexAction()
    {
    	$this->_helper->template->head('属性组管理');
    	
        $hashParam = $this->getRequest()->getParam('hashParam');
        $labels = array(
			'label' => '标题',
			'~contextMenu' => ''
		);
		$partialHTML = $this->view->partial('select-search-header-front.phtml', array(
			'labels' => $labels,
			'selectFields' => array(),
			'url' => '/admin/attributeset/get-attributeset-json/type/'.$this->_type.'/',
			'actionId' => 'id',
			'click' => array(
				'action' => 'contextMenu',
				'menuItems' => array(
					array('编辑', '/admin/attributeset/edit/type/'.$this->_type.'/id/'),
					array('删除', '/admin/attributeset/delete/type/.'.$this->_type.'/id/')
				)
			),
			'initSelectRun' => 'true',
			'hashParam' => $hashParam
		));

        $this->view->partialHTML = $partialHTML;
        $this->_helper->template->actionMenu(array('create'));
    }
    
    public function createAction()
    {
//        $this->_forward('edit');
		require APP_PATH."/admin/forms/Attributeset/Create.php";
    	$form = new Form_Attributeset_Create();
    	
    	if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
    		$attributesetCo = App_Factory::_am('Attributeset');
    		$attributesetDoc = $attributesetCo->create();
    		$attributesetDoc->label = $form->getValue('label');
    		$attributesetDoc->type = $this->_type;
    		$attributesetDoc->save();
    		$attributesetId = $attributesetDoc->getId();
    		
    		$this->_helper->redirector->gotoSimple('edit', 'attributeset', 'admin', array(
    			'type' => $this->_type,
    			'id' => $attributesetId
    		));
    	}
    	$this->view->form = $form;
        $this->_helper->template->head('输入新表名')
        	->actionMenu(array('save'));
    }
    
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $attributesetCo = App_Factory::_am('Attributeset');
    	$attributesetDoc = $attributesetCo->find($id);
        if(is_null($attributesetDoc)) {
        	throw new Exception('attributeset not found');
        }
        
        $this->view->elementList = array();
		$this->view->entityId = $id;
        $this->_helper->template->head('编辑')
        	->actionMenu(array('save', 'delete'));
    }
    
    public function deleteAction()
    {
    	$id = $this->getRequest()->getParam('id');
    	$table = Class_Base::_('Artical');
    	$row = $table->fetchRow(array($table->getAdapter()->quoteInto('id = ?', $id)));
    	if(is_null($row)) {
            throw new Class_Exception_AccessDeny('没有权限访问此内容，或者内容id不存在');
        }
        
        $row->delete();
		$this->_helper->switchContent->gotoSimple('index');
    }
    
	public function getAttributesetJsonAction()
    {
    	$pageSize = 20;
		$currentPage = 1;
		
		$attributesetCo = App_Factory::_am('Attributeset');
		$attributesetCo->addFilter('type', $this->_type);
		$attributesetCo->setFields(array('label'));
		$queryArray = array();
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                	case 'label':
                		$attributesetCo->addFilter('label', new MongoRegex("/^".$value."/"));
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
		$attributesetCo->setPage($currentPage)->setPageSize($pageSize);
		$data = $attributesetCo->fetchAll(true);
		$dataSize = $attributesetCo->count();
		
		$result['data'] = $data;
        $result['dataSize'] = $dataSize;
        $result['pageSize'] = $pageSize;
        $result['currentPage'] = $currentPage;
        
        return $this->_helper->json($result);
    }
    
    public function resortAttributesAction()
    {
    	$attrIdStr = $this->getRequest()->getParam('sortedIdsStr');
    	$attrIdArr = explode(',', $attrIdStr);
    	
    	$attributeCo = App_Factory::_am('Attribute');
    	foreach($attrIdArr as $key => $aId) {
    		$attributeDoc = $attributeCo->find($aId);
    		$attributeDoc->sort = $key;
    		$attributeDoc->save();
    	}
    	
//    	$mongoIdArr = array();
//    	foreach($attrIdArr as $aId) {
//    		$mongoIdArr[] = new MongoId($aId);
//    	}
//    	
//    	$attributeCo = App_Factory::_am('Attribute');
//    	$attributeDocs = $attributeCo->addFilter('_id', array('$in' => $mongoIdArr))->fetchDoc();
////    	Zend_Debug::dump($attributeDocs);
//    	foreach($attributeDocs as $doc) {
//    		echo $doc->getId().',';
//    	}
    	
    	$this->_helper->json('ok');
    }
    
    public function getElementTemplateAction()
    {
    	$type = $this->getRequest()->getParam('element-type');
		$id = $this->getRequest()->getParam('id');
		$attributeCo = App_Factory::_am('Attribute');
		$formDoc = $formCo->create();
		if($type == 'text' || $type == 'textarea') {
			$formDoc->setFromArray(array('formId' => $formid,'elementType'=>$type,'label'=>'标题','required'=>0,'desc'=>'标题描述'));
		} else if($type == 'button') {
			$formDoc->setFromArray(array('formId' => $formid,'elementType'=>$type,'label'=>'提交','type'=>'submit'));
		} else {
			$formDoc->setFromArray(array('formId' => $formid,'elementType'=>$type,'label'=>'标题','required'=>0,'desc'=>'标题描述','option'=>array('第一选项','第二选项','第三选项')));
		}
		$formDoc->save();
		$this->view->testid = $formDoc->getId();
		switch($type) {
			case 'text':
				$this->render('element/text');
				break;
			case 'textarea':
				$this->render('element/textarea');
				break;
			case 'select':
				$this->render('element/select');
				break;
			case 'multi-checkbox':
				$this->render('element/multi-checkbox');
				break;
			case 'menu':
				$this->render('element/menu');
				break;
			case 'button':
				$this->render('element/button');
				break;
		}
		$this->getResponse()->setHeader('result', 'success');
    }
}