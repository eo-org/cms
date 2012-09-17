<?php
class Front_Navi_Draw extends Class_Brick_Solid_Abstract
{
	protected $_effectFiles = array(
    	'navi/draw/plugin.js',
		'navi/draw/plugin.css'
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
		
		$form->addElement('text', 'param_naviWidth', array(
            'filters' => array('StringTrim'),
			'validators' => array('Alnum'),
			'label' => '背景图宽度：',
            'required' => true
        ));
		
		$form->addElement('text', 'param_naviMargin', array(
            'filters' => array('StringTrim'),
			'validators' => array('Alnum'),
			'label' => '图片右间距：',
            'required' => true
        ));
        
    	$paramArr = array('param_naviId', 'param_naviWidth', 'param_naviMargin');
    	$form->setParam($paramArr);
    	return $form;
    }
}
