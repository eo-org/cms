<?php
class Front_AdGroup_Opacity extends Class_Brick_Solid_Abstract
{
	protected $_effectFiles = array(
    		'ad/opacity.plugin.js'
    );
	
	public function prepare()
    {
    	$sectionId = $this->getParam('sectionId');
    	    	
    	$co = App_Factory::_m('Ad');
    	$rowset = $co->addFilter('sectionId', $sectionId)
    		->fetchDoc();
    	
        $this->view->rowset = $rowset;
    }
    
    public function configParam($form)
    {
    	$co = App_Factory::_m('Ad_Section');
    	$options = $co->fetchArr('label');
    	
        $form->addElement('select', 'param_sectionId', array(
            'filters' => array('StringTrim'),
            'label' => '广告组：',
            'required' => true,
        	'multiOptions' => $options
        ));
        $form->addElement('select', 'param_showDescription', array(
            'label' => '显示广告介绍：',
            'required' => true,
        	'multiOptions' => array('n' => '不显示', 'y' => '显示')
        ));
		$form->addElement('select', 'param_thumbRotate', array(
            'label' => '小图是否自动滚动：',
            'required' => true,
        	'multiOptions' => array('n' => '不自动', 'y' => '自动')
        ));
        $form->addElement('select', 'param_delay', array(
            'filters' => array('StringTrim'),
            'label' => '切换时间：',
        	'multiOptions' => array(
        		'4000' => '4秒',
        		'2000' => '2秒',
				'5000' => '5秒',
        		'6000' => '6秒',
        		'8000' => '8秒',
				'10000' => '10秒'
        	),
            'required' => true
        ));
		
    	$paramArr = array('param_sectionId', 'param_showDescription', 'param_thumbRotate', 'param_delay');
    	$form->setParam($paramArr);
    	return $form;
    }
}