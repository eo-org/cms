<?php
class Admin_AdController extends Zend_Controller_Action
{
//	public function init()
//	{
//		$this->_helper->template->portal(array(
//			array('label' => '广告', 'controllerName' => 'ad', 'href' => '/admin/ad/index'),
//			array('label' => '广告分类', 'controllerName' => 'group', 'href' => '/admin/group/list/type/ad'),
//		));
//	}
	
	public function indexAction()
	{
		$sectionId = $this->getRequest()->getParam('sectionId');
    	
        $labels = array(
            'label' => '广告名',
        	'url' => '连接',
            'clicks' => '点击次数',
        	'~contextMenu' => ''
        );
        
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header-front.phtml', array(
            'labels' => $labels,
        	'selectFields' => array(
                'id' => null,
            ),
            'url' => '/admin/ad/get-ad-json/',
            'actionId' => 'id',
        	'click' => array(
            	'action' => 'contextMenu',
            	'menuItems' => array(
            		array('编辑', '/admin/ad/edit/id/')
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
//		$adTable = Class_Base::_('Ad');
//		$adRowset = $adTable->fetchAll();
//		
//		$groupTable = Class_Base::_('GroupV2');
//		$groupRowset = $groupTable->fetchAll($groupTable->select()->where('type = ?', 'ad'));
//		
//		foreach($groupRowset as $groupRow) {
//			foreach($adRowset as $adRow) {
//				if($adRow->groupId == $groupRow->id) {
//					$groupRow->appendAdRow($adRow);
//				}
//			}
//		}
//		
//		$this->view->groupRowset = $groupRowset;
	}
	
	public function createAction()
	{
		$this->_forward('edit');
		$this->_helper->template->actionMenu(array('save'));
	}
	
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		
		require APP_PATH.'/admin/forms/Ad/Edit.php';
		$form = new Form_Ad_Edit();
		$co = App_Factory::_m('Ad');
		if(is_null($id)) {
			$doc = $co->create();
			$doc->created = new MongoDate();
			$doc->clicks = 0;
		} else {
			$doc = $co->find($id);
		}
		
		$form->populate($doc->toArray());
		//preset groupId from url
//    	$groupId = $this->getRequest()->getParam('groupId');
//        if(!empty($groupId)) {
//        	$form->groupId->setAttrib('disabled', 'disabled');
//        	$form->groupId->setValue($groupId);
//        }
        //end preset
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$doc->setFromArray($form->getValues());
			$doc->save();
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
		$co = App_Factory::_m('Ad');
		$doc = $co->find($id);
		if(!is_null($doc)) {
			$doc->delete();
		}
		$this->_helper->switchContent->gotoSimple('index');
	}
	
	public function getAdJsonAction()
	{
		$pageSize = 20;
		$currentPage = 1;
		
		$co = App_Factory::_m('Ad');
		$co->setFields(array('label', 'groupId', 'url', 'clicks'));
		$queryArray = array();
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                	case 'label':
                		$co->addFilter('label', new MongoRegex("/^".$value."/"));
                		break;
                	case 'groupId':
                		$co->addFilter('groupId', $value);
                		break;
                	case 'url':
                		$co->addFilter('url', $value);
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