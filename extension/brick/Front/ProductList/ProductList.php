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
		$pageSize = $this->getParam('pageSize');
		if(empty($pageSize)) {
			$pageSize = 20;
		}
		
		$page = $this->_request->getParam('page');
		$clf = Class_Layout_Front::getInstance();
		
		$groupItemId = null;
		$groupDoc = $clf->getResource();
		
		$co = App_Factory::_m('Product');
		$co->addFilter('groupId', $groupDoc->getId())
			->setFields(array('id', 'name', 'sku', 'label', 'introicon', 'introtext', 'price', 'attributeDetail'))
			->setPage($page)
			->setPageSize($pageSize);
		
		switch($this->getParam('defaultSort')) {
			case 'sw':
				$co->sort('weight', 1);
				break;
			case 'sc':
				break;
			case 'sn':
				$co->sort('name', 1);
				break;
		}

		if($this->getParam('paginatorLanguage') == 'en') {
			Zend_View_Helper_PaginationControl::setDefaultViewPartial(
                        'pagination-control.en.phtml' 
                        );
		} else {
			Zend_View_Helper_PaginationControl::setDefaultViewPartial(
                        'pagination-control.phtml' 
                        );
		}
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		 
		$dataSize = $co->count();
		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Null($dataSize));
		$paginator->setCurrentPageNumber($page)
		->setItemCountPerPage($pageSize);
		 
		$rowset = $co->fetchDoc();
		
		if(is_null($groupDoc)) {
			$this->view->title = '产品列表';
		} else {
			$this->view->title = $groupDoc->label;
		}
		$this->view->rowset = $rowset;
		$this->view->paginator = $paginator;
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
        
        $form->addElement('select', 'param_defaultSort', array(
            'filters' => array('StringTrim'),
            'label' => '产品默认排序：',
        	'multiOptions' => array(
        		'sw' => '权重排序',
        		'sc' => '产品添加顺序',
        		'sn' => '产品名排序'
       		),
            'required' => true
        ));
        
        $form->addElement('select', 'param_pageSize', array(
            'filters' => array('StringTrim'),
            'label' => '单页产品数量：',
        	'multiOptions' => array(
        		5 => 5,
        		6 => 6,
        		7 => 7,
        		8 => 8,
        		9 => 9,
        		10 => 10,
        		15 => 15,
        		20 => 20
       		),
            'required' => true
        ));
        
    	$paramArr = array('param_showSubgroupContent', 'param_defaultSort', 'param_pageSize');
    	$form->setParam($paramArr);
    	return $form;
    }
}