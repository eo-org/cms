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
    	$groupDoc = App_Factory::_m('Group_Item')->find($groupId);
    	$title = "";
    	if(!is_null($groupDoc)) {
    		$title = $groupDoc->label;
    	}
		
		$productCo = App_Factory::_m('Product');
		$productCo->addFilter('groupId', $groupId)
			->setFields(array('id', 'name', 'sku', 'label', 'introicon', 'introtext', 'price', 'attributeDetail'))
			->sort('_id', -1);
		
		$rowset = $productCo->fetchDoc();
			
    	$numPerSlide = $this->getParam('numPerSlide');
    	$numPerSlide = empty($numPerSlide) ? 1 : $numPerSlide;
    	$numPage = ceil(count($rowset)/$numPerSlide);
    	
		$this->view->numPage = $numPage;
    	$this->view->groupId = $groupId;
		$this->view->rowset = $rowset;
		$this->view->title = $title;
    }
    
    public function configParam(Class_Form_Edit $form)
    {
    	$groupDoc = App_Factory::_m('Group')->addFilter('type', 'product')
    		->fetchOne();
    	$items = $groupDoc->toMultioptions('label');
        $form->addElement('select', 'param_groupId', array(
            'label' => '产品数据源',
            'multiOptions' => $items
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