<?php
class Front_ProductNews extends Class_Brick_Solid_Abstract
{
	public function getCacheId()
	{
		return "product_news_".$this->_brick->brickId;
	}
	
    public function prepare()
    {
        $groupId = $this->getParam('groupId');
    	if($groupId == 'auto') {
    		$groupId = $this->_request->getParam('id');
    	}
    	if(is_null($groupId)) {
    		$groupId = 0;
    	}
    	
		$groupTb = Class_Base::_('GroupV2');
		$groupRow = $groupTb->find($groupId)->current();
		
		$productTable = Class_Base::_('Product');
        $selector = $productTable->select(false)->setIntegrityCheck(false)
        	->from($productTable, array('id', 'groupId', 'title', 'sku', 'introicon', 'introtext', 'price', 'origPrice'))
        	->limit($this->getParam('limit'), 0);
		
//		$featuredOnly = $this->getParam('featuredOnly');
		$selector->order('featured DESC')
			->order('id DESC');
		
    	if(!is_null($groupRow)) {
			if($groupRow->hasChildren == 1) {
				$subgroupRowset = $groupTb->fetchAll($groupTb->select(false)
					->from($groupTb, array('id'))
					->where('parentId = ?', $groupRow->id)
				);
				$subgroupIdArr = Class_Func::buildArr($subgroupRowset, 'id', 'id');
				$selector->where('groupId in ('.implode(',', $subgroupIdArr).')');
			} else {
				$selector->where('groupId = ?', $groupRow->id);
			}
    	} else if($groupId == 0) {
    	
    	} else {
    		$this->setParam('header', 'none');
    	}
    	
    	if($this->getParam('titlePrefix') == 'group') {
    		$selector->joinLeft(
    			array('g' => 'group'),
    			'product.groupId = g.id',
    			array('g.label')
    		);
    	}
    	$productRowset = $productTable->fetchAll($selector);
		
    	$numPerSlide = $this->getParam('numPerSlide');
    	$numPerSlide = empty($numPerSlide) ? 1 : $numPerSlide;
    	$numPage = ceil($productRowset->count()/$numPerSlide);
		$this->view->numPage = $numPage;
    	$this->view->groupId = $groupId;
		$this->view->rowset = $productRowset;
    }
    
    public function configParam(Class_Form_Edit $form)
    {
    	$table = new Class_Model_GroupV2_Tb();
        $selector = $table->select()->where('type = ?', 'product');
        $options = $table->fetchSelectOption(array('auto' => '自动选择', 0 => '全部商品'), $selector);
        $form->addElement('select', 'param_groupId', array(
            'label' => '产品数据源',
            'multiOptions' => $options
        ));
        $form->addElement('select', 'param_limit', array(
            'filters' => array('StringTrim'),
            'label' => '产品数量：',
        	'multiOptions' => array(1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8', 9 => '9', 10 => '10', 12 => '12', 15 => '15'),
            'required' => true
        ));
        $form->addElement('text', 'param_width', array(
            'filters' => array('StringTrim'),
        	'validators' => array('Alnum'),
            'label' => '宽度：',
            'required' => true
        ));
        $form->addElement('text', 'param_height', array(
            'filters' => array('StringTrim'),
        	'validators' => array('Alnum'),
            'label' => '高度：',
            'required' => true
        ));
        $form->addElement('text', 'param_margin', array(
            'filters' => array('StringTrim'),
        	'validators' => array('Alnum'),
            'label' => '左右间隔：',
            'required' => true
        ));
        $form->addElement('select', 'param_delay', array(
            'filters' => array('StringTrim'),
            'label' => '切换时间：',
        	'multiOptions' => array(
        		'4000' => '4秒',
        		'3000' => '3秒',
        		'2000' => '2秒',
        		'6000' => '6秒',
        	),
            'required' => true
        ));
        $form->addElement('select', 'param_numPerSlide', array(
            'label' => '单页产品数量：',
            'required' => true,
        	'multiOptions' => array(1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 8 => '8')
        ));
        
        $paramArr = array('param_groupId', 'param_limit', 'param_width', 'param_height', 'param_margin', 'param_delay', 'param_numPerSlide');
        $form->setParam($paramArr);
        return $form;
    }
}