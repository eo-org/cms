<?php
class Front_ProductNewsMarquee extends Class_Brick_Solid_Abstract
{
	protected $_effectFiles = array(
    	'common/marquee.plugin.js'
    );
	
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
			->setFields(array('id', 'name', 'sku', 'label', 'introicon', 'introtext', 'price'));
		
		$rowset = $productCo->fetchAll(true);
			
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
		$form->addElement('select', 'param_direction', array(
            'filters' => array('StringTrim'),
            'label' => '滚动方向：',
        	'multiOptions' => array(
        		'top' => '向上',
				'left' => '向左'
        	),
            'required' => true
        ));
        $form->addElement('select', 'param_delay', array(
            'filters' => array('StringTrim'),
            'label' => '切换时间：',
        	'multiOptions' => array(
        		'20' => '适中',
				'30' => '慢',
        		'10' => '快',
				'1'   => '特快'
        	),
            'required' => true
        ));
        
        $paramArr = array('param_groupId', 'param_limit',  'param_direction', 'param_delay');
        $form->setParam($paramArr);
        return $form;
    }
}