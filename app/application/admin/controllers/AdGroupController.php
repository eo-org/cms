<?php
class Admin_AdGroupController extends Zend_Controller_Action
{	
	public function indexAction()
	{
		$sectionId = $this->getRequest()->getParam('sectionId');
    	
        $labels = array(
            'label' => '广告名',
        	'~contextMenu' => ''
        );
        
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header-front.phtml', array(
            'labels' => $labels,
        	'selectFields' => array(
                'id' => null,
            ),
            'presetFields' => array('sectionId' => $sectionId),
            'url' => '/admin/ad-group/get-ad-group-json/',
            'actionId' => 'id',
        	'click' => array(
            	'action' => 'contextMenu',
            	'menuItems' => array(
            		array('编辑', '/admin/ad-group/edit/id/')
            	)
            ),
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));

        $this->view->partialHTML = $partialHTML;
        $this->_helper->template->actionMenu(array('create'));
	}
	
	public function listGroupAction()
	{
		$adTable = Class_Base::_('Ad');
		$adRowset = $adTable->fetchAll();
		
		$groupTable = Class_Base::_('GroupV2');
		$groupRowset = $groupTable->fetchAll($groupTable->select()->where('type = ?', 'ad'));
		
		foreach($groupRowset as $groupRow) {
			foreach($adRowset as $adRow) {
				if($adRow->groupId == $groupRow->id) {
					$groupRow->appendAdRow($adRow);
				}
			}
		}
		
		$this->view->groupRowset = $groupRowset;
	}
	
	public function createAction()
	{
		$this->_forward('edit');
		$this->_helper->template->actionMenu(array('save'));
	}
	
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		
		require APP_PATH.'/admin/forms/Ad/Group/Edit.php';
		$form = new Form_Ad_Group_Edit();
		$tb = App_Factory::_('GroupV2');
		if(is_null($id)) {
			$row = $tb->createRow();
		} else {
			$row = $tb->find($id)->current();
		}
		
		$form->populate($row->toArray());
		//preset groupId from url
    	$groupId = $this->getRequest()->getParam('groupId');
        if(!empty($groupId)) {
        	$form->groupId->setAttrib('disabled', 'disabled');
        	$form->groupId->setValue($groupId);
        }
        //end preset
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$row->setFromArray($form->getValues());
			$row->save();
			$this->_helper->switchContent->gotoSimple('index', null, null, array(), true);
		}
		
		$labels = array(
            'id' => 'ID',
            'label' => '链接名'
        );
		$this->view->form = $form;
		$this->_helper->template->actionMenu(array('save', 'delete'));
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$tb = new Class_Model_Ad_Tb();
		$row = $tb->find($id)->current();
		if(!is_null($row)) {
			$row->delete();
		}
		$this->_helper->switchContent->gotoSimple('index');
	}
	
	public function getAdGroupSelectorAction()
	{
		
	}
	
	public function getAdGroupJsonAction()
	{
		$pageSize = 30;
        
	    $tb = Class_Base::_('GroupV2');
	    $selector = $tb->select()
	    	->where('type = ?', 'ad')
	    	->order('id DESC')
	        ->limitPage(1, $pageSize);
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
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