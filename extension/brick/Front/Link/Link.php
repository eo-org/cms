<?php
class Front_Link extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$id = $this->getParam('naviId');
    	$co = App_Factory::_m('Navi');
    	$doc = $co->find($id);
    	
    	$this->view->naviDoc = $doc;
    }
    
    public function configParam($form)
    {
		$co = App_Factory::_m('Navi');
    	$docArr = $co->setFields('label')->fetchArr('label');
		
    	$form->addElement('select', 'param_naviId', array(
            'label' => '选择目录组：',
    		'multiOptions' => $docArr,
            'required' => true
        ));
    	
        $form->addElement('select', 'param_display', array(
            'label' => '显示方式：',
    		'multiOptions' => array('text' => '文字', 'bg' => '背景图'),
            'required' => true
        ));
        
    	$paramArr = array('param_naviId', 'param_display');
    	$form->setParam($paramArr);
    	return $form;
    }
}
