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
			$idArr[] = $groupId;
			foreach($subGroupRow as $r) {
				$idArr[] = $r->getId();
			}
			$groupId = $idArr;
		}
		
		$productCo = App_Factory::_m('Product');
		$productCo->addFilter('groupId', $groupId)
			->setFields(array('id', 'name', 'sku', 'label', 'introicon', 'introtext', 'price'));
		switch($this->getParam('defaultSort')) {
			case 'sw':
				$productCo->sort('weight', 1);
				break;
			case 'sc':
				break;
			case 'sn':
				$productCo->sort('name', 1);
				break;
		}
			
        
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
        
    	$paramArr = array('param_showSubgroupContent', 'param_defaultSort');
    	$form->setParam($paramArr);
    	return $form;
    }
}