<?php
class Admin_MessagePatternController extends Zend_Controller_Action 
{
	public function indexAction()
    {
    	$this->_helper->template->head('问卷列表')
    		->actionMenu(array('create'));
        
    	$hashParam = $this->getRequest()->getParam('hashParam');
    	$labels = array(
    		'id' => 'ID',
    		'label' => '表单名',
            'name' => '表单URL',
    		'~contextMenu' => ''
    	);
        $partialHTML = $this->view->partial('select-search-header-front.phtml', array(
            'labels' => $labels,
        	'selectFields' => array('id' => null),
            'url' => '/admin/message-pattern/get-pattern-json/',
            'actionId' => 'id',
        	'click' => array(
            	'action' => 'contextMenu',
            	'menuItems' => array(
            		array('编辑', '/admin/message-pattern/edit/attributesetId/'),
            		array('删除', '/admin/message-pattern/delete/attributesetId/')
            	)
            ),
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));

        $this->view->partialHTML = $partialHTML;
//        $this->_helper->template->actionMenu(array('create'));
    }
    
    public function createAction()
    {
    	require APP_PATH.'/admin/forms/MessagePattern/Edit.php';
    	$form = new Form_MessagePattern_Edit();
    	if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
    		$tb = Class_Base::_('Eav_Attributeset');
    		$row = $tb->createRow();
    		$row->setFromArray($form->getValues());
    		$row->entityType = 'message';
    		$row->save();
    		
    		$this->_helper->flashMessenger->addMessage('新问卷<em>'.$row->label.'</em>已保存,请为问卷添加新问题！');
    		$this->_helper->switchContent->gotoSimple('edit', null, null, array('attributesetId' => $row->id));
    	}
    	$this->view->form = $form;
    	$this->_helper->template->head('创建新问卷')->actionMenu(array('save'));
    }
    
    public function editAction()
    {
    	$attributesetId = $this->getRequest()->getParam('attributesetId');
    	
    	require APP_PATH.'/admin/forms/MessagePattern/Edit.php';
    	$form = new Form_MessagePattern_Edit();
    	if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
    		
    	}
    	$tb = Class_Base::_('Eav_Attributeset');
    	$setRow = $tb->find($attributesetId)->current();
    	$form->populate($setRow->toArray());
    	$attributeRowset = $setRow->getAttributeRowset();
    	$this->_helper->template->head('编辑问卷')
    		->actionMenu(array(
				'create-text-input' => array(
					'label' => '新填空题',
    				'callback' => '/admin/message-pattern/create-question/attributesetId/'.$attributesetId.'/inputType/text'
    			),
				'create-select-input' => array(
					'label' => '新选择题',
					'callback' => '/admin/message-pattern/create-question/attributesetId/'.$attributesetId.'/inputType/select'
    			),
    			'save',
    			'delete'
			));
    	if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
    		$setRow->setFromArray($form->getValues());
    		$setRow->save();
    		
    		$this->_helper->flashMessenger->addMessage('问卷成功保存！');
    		$this->_helper->switchContent->gotoSimple('index', null, null, array('attributesetId' => $row->id));
    	}
		$this->view->form = $form;
    	$this->view->attributeRowset = $attributeRowset;
    	$this->_helper->template->head('编辑问卷：<em>'.$setRow->label.'</em>');
    }
    
    public function deleteAction()
    {
    	$attributesetId = $this->getRequest()->getParam('attributesetId');
    	
    	$tb = Class_Base::_('Eav_Attributeset');
    	$setRow = $tb->find($attributesetId)->current();
    	$setRow->delete();
    	
    	$this->_helper->flashMessenger->addMessage('问卷已删除！');
    	$this->_helper->switchContent->gotoSimple('index', null, null);
    }
    
    public function createQuestionAction()
    {
    	$this->_forward('edit-question');
    }
    
    public function editQuestionAction()
    {
    	$id = $this->getRequest()->getParam('id');
    	if(empty($id)) {
    		$attr = Class_Base::_('Eav_Attribute')->createRow($this->getRequest()->getParams());
    		$inputType = $this->getRequest()->getParam('inputType');
    		if($inputType == 'text' || $inputType == 'select') {
    			if($inputType == 'text') {
    				$this->_helper->template->head('创建填空题');
    			}
    			if($inputType == 'select') {
    				$this->_helper->template->head('创建选择题');
    			}
    		} else {
    			throw new Exception('input type is not correct while creating new question!');
    		}
    	} else {
    		$attr = Class_Base::_('Eav_Attribute')->find($id)->current();
    		$attr->loadOptions();
    		$this->_helper->template->head('编辑问题：<em>'.$attr->label.'</em>');
    		$this->getRequest()->setParam('attributesetId', $attr->attributesetId);
    	}
    	
    	if($this->getRequest()->isPost()) {
    		$attr->setFromArray($this->getRequest()->getParams());
    		$options = $this->getRequest()->getParam('options');
    		$newOptions = $this->getRequest()->getParam('newOptions');
    		if(!is_null($options)) {
    			$attr->updateOptions($options);
    		}
    		if(!is_null($newOptions)) {
    			$attr->setNewOptions($newOptions);
    		}
    		$attr->save();
    		$this->_helper->switchContent->gotoSimple('edit', null, null, array('attributesetId' => $attr->attributesetId));
    	}
    	
    	$this->view->attr = $attr;
    	$this->_helper->template->actionMenu(array('save', 'delete' => array('label' => '删除', 'callback' => '/admin/message-pattern/delete-question/attributeId/'.$id)));
    }
    
    public function deleteQuestionAction()
    {
    	$id = $this->getRequest()->getParam('attributeId');
    	$attr = Class_Base::_('Eav_Attribute')->find($id)->current();
    	$attr->delete();
    	
    	$this->_helper->flashMessenger->addMessage('问题已删除！');
    	$this->_helper->switchContent->gotoSimple('edit', null, null, array('attributesetId' => $id));
    }
    
    public function getPatternJsonAction()
    {
        $pageSize = 20;
        
	    $tb = Class_Base::_('Eav_Attributeset');
	    $selector = $tb->select(false)
	    	->order('id DESC')
	        ->limitPage(1, $pageSize);
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                    case 'label':
                        $selector->where('label like ?', '%'.$value.'%');
                        break;
            		case 'page':
            			if(intval($value) == 0) {
            				$value = 1;
            			}
            		    $selector->limitPage(intval($value), $pageSize);
                        $result['currentPage'] = intval($value);
            		    break;
            		case 'selectedIds':
					    if($value != 'all') {
					        $selector->where('id in (?)', explode(',', $value));
					    }
					    break;
                }
            }
        }
        
        $selector->where('entityType = ?', 'message');
        
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