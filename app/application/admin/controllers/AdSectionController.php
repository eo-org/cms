<?php
class Admin_AdSectionController extends Zend_Controller_Action
{
	public function indexAction()
	{
        $labels = array(
            'id' => 'ID',
            'label' => '广告分组名'
        );
        
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header.phtml', array(
            'labels' => $labels,
        	'selectFields' => array(
                'id' => null
            ),
            'url' => '/admin/ad-section/get-ad-section-json/',
            'actionId' => 'id',
            'initSelectRun' => 'true',
            'hashParam' => $hashParam,
            'click' => array(
            	'action' => 'contextMenu',
            	'menuItems' => array(
            		array('编辑', '/admin/ad-section/edit/id/', 'current')
            	)
            ),
        ));

        $this->view->partialHTML = $partialHTML;
	}
	
	public function createAction()
	{
		$this->_forward('edit');
	}
	
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		
		require APP_PATH.'/admin/forms/Ad/Section/Edit.php';
		$form = new Form_Ad_Section_Edit();
		$tb = new Class_Model_Ad_Section_Tb();
		if(is_null($id)) {
			$row = $tb->createRow();
		} else {
			$row = $tb->find($id)->current();
		}
		$form->populate($row->toArray());
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$row->setFromArray($form->getValues());
			$row->save();
			$this->_helper->redirector->gotoSimple('index');
		}
		
		$this->view->form = $form;
	}
	
	public function getAdSectionJsonAction()
	{
		$pageSize = 30;
        
	    $tb = Class_Base::_('Ad_Section');
	    $selector = $tb->select(false)
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