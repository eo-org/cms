<?php
class Admin_MessageController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $labels = array(
            'id' => 'ID',
            'attributesetId'=>'类型',
        	'owner' => '发布人',
            'display' => '显示',
        	'created' => '发布时间',
        	'~contextMenu' => ''
        );
        $db = Zend_Registry::get('db');
        $easArr = $db->fetchPairs('select id, label from eav_attribute_set where entityType="message"');
        
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header-front.phtml', array(
            'labels' => $labels,
            'selectFields' => array(
                'id' => NULL,
        		'created' => NULL,
        		'attributesetId' => $easArr
            ),
            'url' => '/admin/message/get-message-grid-json/',
            'actionId' => 'id',
            'click' => array(
            	'action' => 'contextMenu',
            	'menuItems' => array(
            		array('查看', '/admin/message/view/id/')
            	)
            ),
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));

        $this->view->assign('partialHTML', $partialHTML);
        $this->_helper->template->head('用户反馈')->actionMenu(array('create'));
	}
	
	public function createAction()
	{
		$tb = new Zend_Db_Table('eav_attribute_set');
		$rowset = $tb->fetchAll($tb->select()->where('entityType = ?', 'message'));
		$options = Class_Func::buildArr($rowset, 'id', 'label');
		
		$form = new Zend_Form();
		$form->setAction('/admin/message/edit/');
		$form->setMethod('get');
		$form->addElement('select', 'attributeSetId', array(
			'label' => '类别',
			'multiOptions' => $options
		));
		$form->addElement('submit', 'submit', array(
			'label' => '提交'
		));
		$this->view->form = $form;
	}
	
	public function viewAction()
	{
		$id = $this->getRequest()->getParam('id');
		$tb = Class_Base::_('Message');
		$row = $tb->find($id)->current();
		$valueRowset = $row->getValueRowset();
		
		$this->view->row = $row;
		$this->view->valueRowset = $valueRowset;
		
		$this->_helper->template->actionMenu(array(
			'edit' => array('label' => '修改', 'callback' => '/admin/message/edit/id/'.$id))
		);
	}
	
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		if(is_null($id)) {
			$attributeSetId = $this->getRequest()->getParam('attributeSetId');
			$entity = Class_Base::_('Message')->createRow();
	    	$attributesetId = $entity->setAttributesetId($attributeSetId);
	    	$entity->setOwnerLabel('发布人');
		} else {
			$tb = Class_Base::_('Message');
			$entity = $tb->find($id)->current();
			$valueRowset = $entity->getValueRowset();
		}
		$form = $entity->getForm(true);
    	
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$entity->setFromArray($form->getValues())
				->setAttributeValue($form->getValues());
			$entity->save();
			$entityId = $entity->id;
			$this->_helper->switchContent->gotoSimple('view', null, null, array('id' => $entityId));
		}
		
		$this->view->form = $form;
		$this->_helper->template->head('新建数据条目')->actionMenu(array('save', 'delete'));
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		
		$tb = new Zend_Db_Table('comment');
		$row = $tb->find($id)->current();
		
		if($row == null) {
			throw new Class_Exception_Pagemissing();
		}
		$row->delete();
		
		$this->_helper->redirector->gotoSimple('index');
	}
	
	public function getMessageGridJsonAction()
    {
        $pageSize = 30;
        
        $tb = Class_Base::_('Message');
	    $selector = $tb->select()->order('id DESC')
	        ->limitPage(1, $pageSize);
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                	case 'attributesetId':
                		$selector->where('attributesetId = ?', $value);
                		break;
                	case 'owner':
                		$selector->where('owner = ?', $value);
                		break;
                    case 'page':
            			if(intval($value) == 0) {
            				$value = 1;
            			}
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
        
        if(empty($result['currentPage'])) {
        	$result['currentPage'] = 1;
        }
        return $this->_helper->json($result);
    }
}