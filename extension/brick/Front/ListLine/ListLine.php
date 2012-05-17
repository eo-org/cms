<?php
class Front_ListLine extends Class_Brick_Solid_Abstract
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
		$clf = Class_Layout_Front::getInstance();
		$groupRow = $clf->getResource();
		$page = $this->_request->getParam('page');
		$groupId = $groupRow->id;
		
		if($groupRow == 'none') {
			$this->_disableRender = 'no-resource';
		} else {
			$this->_id = $groupId;
			
//			$groupRow = Class_Tree_Solid_Group::findBranchById($groupRow->id);
//			
//			if($groupRow->hasChildren() && $this->getParam('showSubgroupContent') == 'y') {
//				$subGroupRow = $groupRow->getChildren();
//				$idArr = array();
//				foreach($subGroupRow as $r) {
//					$idArr[] = $r->getId();
//				}
//				$groupId = $groupId.','.implode($idArr, ',');
//			}
			
			$table = Class_Base::_('Artical');
			$selector = $table->select()->from($table, array('id', 'title', 'introtext', 'introicon', 'created'))
				->where('groupId in ('.$groupId.')')
				->limitPage($page, $pageSize)
				->order('id DESC');
			$siteInfo = Zend_Registry::get('siteInfo');
	        if($siteInfo['type'] == 'multiple' && $siteInfo['subdomain']['id'] != 0) {
	        	$selector->where('subdomainId = ?', $siteInfo['subdomain']['id']);
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
			
	        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($selector));
	        $paginator->setCurrentPageNumber($page)
	        	->setItemCountPerPage($pageSize);
	        
			$rowset = $table->fetchAll($selector);
			$this->view->displayBrickName = $this->_brick->displayBrickName;
			$this->view->title = $groupRow->label;
			$this->view->rowset = $rowset;
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
        $form->addElement('select', 'param_paginatorLanguage', array(
            'filters' => array('StringTrim'),
            'label' => '页码语言：',
        	'multiOptions' => array('default' => '中文', 'en' => '英文'),
            'required' => false
        ));
    	$paramArr = array(
    		'param_pageSize',
    		'param_showSubgroupContent',
    		'param_showIntrotext',
    		'param_showIntroicon',
    		'param_created'
    	);
    	$form->setParam($paramArr);
    	return $form;
    }
}