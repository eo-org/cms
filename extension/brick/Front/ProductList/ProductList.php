<?php
class Front_ProductList extends Class_Brick_Solid_Abstract
{
	public function _init()
	{
		$groupId = $this->_request->getParam('action');
		if($groupId == 'index') {
			$this->_disableRender = 'product-list'.$this->_brick->cssSuffix;
		}
	}
	
	public function getCacheId()
	{
		$page = $this->_request->getParam('page');
		$groupId = $this->_request->getParam('action');
		
		return 'product_listline_'.$groupId.'_'.$page;
	}
	
	public function prepare()
	{
		$pageSize = 20;
		
	    $page = $this->_request->getParam('page');
		$groupId = $this->_request->getParam('action');
		
		$linkController = Class_Link_Controller::factory('product');
		
		$link = $linkController->getLink($groupId);
		
		if(is_null($link)) {
			$groupId = 0;
		} else if($link->hasChildren() && $this->getParam('showSubgroupContent') == 'y') {
			$subGroupRow = $link->getChildren();
			$idArr = array();
			foreach($subGroupRow as $r) {
				$idArr[] = $r->getId();
			}
			$groupId = $groupId.','.implode($idArr, ',');
		}
		
		$productCo = App_Factory::_m('Product');
		$productCo->addFilter('groupId', $groupId)
			->setFields(array('id', 'name', 'sku', 'label', 'introicon', 'introtext', 'price'));
			
			
//		$selector = $table->select()->from($table, array('id', 'sku', 'title', 'introicon', 'introtext', 'price'))
//			->where('groupId in ('.$groupId.')')
//			->limitPage($page, $pageSize)
//			->order('id DESC');
			
			
			
			
			
//        Zend_Paginator::setDefaultScrollingStyle('Sliding');
//		Zend_View_Helper_PaginationControl::setDefaultViewPartial(
//		    'pagination-control.phtml'
//		);
//        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($selector));
//        $paginator->setCurrentPageNumber($page)
//        	->setItemCountPerPage($pageSize);
        
		$rowset = $productCo->fetchAll(true);
		
		if(is_null($link)) {
			$this->view->title = '产品列表';
		} else {
			$this->view->title = $link->label;
		}
		$this->view->rowset = $rowset;
//		$this->view->paginator = $paginator;
	}
	
	public function configParam(Class_Form_Edit $form)
    {
    	$form->addElement('select', 'param_showSubgroupContent', array(
            'filters' => array('StringTrim'),
            'label' => '显示子分类产品：',
        	'multiOptions' => array(
        		'y' => '显示',
        		'n' => '不显示'
       		),
            'required' => false
        ));
        
    	$paramArr = array('param_showSubgroupContent');
    	$form->setParam($paramArr);
    	return $form;
    }
}