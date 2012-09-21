<?php
class Front_ArticleList extends Class_Brick_Solid_Abstract
{
	protected $_id = null;
	
	protected function _prepareGearLinks()
	{
		if($this->_id == null) {
			return parent::_prepareGearLinks();
		} else {
			$this->_addGearLink('添加新文章', '/admin/artical/edit/groupId/'.$this->_id);
		}
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
		
		if($groupDoc == 'none' || $groupDoc == null) {
			$this->_disableRender = 'no-resource';
		} else {
			$groupId = $groupDoc->getId();
			
			$co = App_Factory::_m('Article');
			$co->setFields(array('id', 'label', 'introtext', 'introicon', 'created'))
				->addFilter('groupId', $groupId)
				->setPage($page)
				->setPageSize($pageSize)
				->sort('weight');
				
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
	        
			$data = $co->fetchDoc();
			$this->view->displayBrickName = $this->_brick->displayBrickName;
			$this->view->title = $groupDoc->label;
			$this->view->rowset = $data;
			$this->view->paginator = $paginator;
		}
	}
	
	public function configParam(Class_Form_Edit $form)
    {
    	$form->addElement('select', 'param_showSubgroupContent', array(
            'filters' => array('StringTrim'),
            'label' => '显示子分类文章：',
        	'multiOptions' => array(
        		'y' => '显示',
        		'n' => '不显示'
       		),
            'required' => false
        ));
        $options = Class_Func::getNumericArray(1, 40);
        $form->addElement('select', 'param_pageSize', array(
            'filters' => array('StringTrim'),
            'label' => '每页新闻条目：',
        	'multiOptions' => $options,
            'required' => false
        ));
        $form->addElement('select', 'param_showIntrotext', array(
            'filters' => array('StringTrim'),
            'label' => '显示文章摘要：',
        	'multiOptions' => array(
        		'none' => '不显示',
        		'one' => '显示首条摘要信息',
        		'all' => '显示全部摘要信息'
       		),
            'required' => false
        ));
        $form->addElement('select', 'param_showIntroicon', array(
            'filters' => array('StringTrim'),
            'label' => '显示摘要图片：',
        	'multiOptions' => array(
        		'none' => '不显示',
        		'one' => '显示首条摘要图片',
        		'all' => '显示全部摘要图片'
       		),
            'required' => false
        ));
        $form->addElement('select', 'param_created', array(
            'filters' => array('StringTrim'),
            'label' => '显示日期：',
        	'multiOptions' => array('n' => '否', 'y' => '是'),
            'required' => false
        ));
    	$paramArr = array(
    		'param_pageSize',
    		'param_showSubgroupContent',
    		'param_showIntrotext',
    		'param_showIntroicon',
    		'param_created',
    		'param_paginatorLanguage'
    	);
    	$form->setParam($paramArr);
    	return $form;
    }
}