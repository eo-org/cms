<?php
class Front_Navi_Flow extends Class_Brick_Solid_Abstract
{
	protected $_effectFiles = array(
    	'navi/flow/plugin.js',
		'navi/flow/plugin.css'
    );
	
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
		
		$form->addElement('select', 'param_style', array(
            'label' => '动态显示方向：',
    		'multiOptions' => array('down' => '向下', 'left' => '向左'),
            'required' => true
        ));
        
    	$paramArr = array('param_naviId', 'param_display', 'param_style');
    	$form->setParam($paramArr);
    	return $form;
    }
}
