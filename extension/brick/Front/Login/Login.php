<?php
class Front_Login extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$linksOnly = false;
    	if($this->_params->linksOnly == 'y') {
    		$linksOnly = true;
    	}
    	
    	$this->view->linksOnly = $linksOnly;
    }
    
    public function configParam(Class_Form_Edit $form)
    {
    	$form->addElement('select', 'param_linksOnly', array(
    		'label' => '前台显示方式',
    		'multiOptions' => array('y' => '仅显示链接', 'n' => '显示登录框'),
    		'required' => true
    	));
    	
    	$form->setParam(array('param_linksOnly'));
    	return $form;
    }
}