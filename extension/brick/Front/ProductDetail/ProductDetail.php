<?php
class Front_ProductDetail extends Class_Brick_Solid_Abstract
{
	public function _init()
	{
		$clf = Class_Layout_Front::getInstance();
    	$product = $clf->getResource();
		if($product == 'none') {
			$this->_disableRender = 'brick-product-detail';
		}
	}
	
    public function prepare()
    {
    	$clf = Class_Layout_Front::getInstance();
    	$product = $clf->getResource();
        
        $this->view->row = $product;
    }
    
    public function configParam(Class_Form_Edit $form)
    {
    	$form->addElement('select', 'param_showBuy', array(
            'label' => '显示购买按钮：',
        	'multiOptions' => array(
    			'y' => '显示',
        		'n' => '不显示'
       		),
            'required' => false
        ));
        $paramArr = array('param_showBuy');
        $form->setParam($paramArr);
        return $form;
    }
}