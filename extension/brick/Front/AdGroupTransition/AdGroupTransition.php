<?php
class Front_AdGroupTransition extends Class_Brick_Solid_Abstract
{
	protected $_effectFiles = array(
		'ad/transition/plugin.js',
		'ad/transition/plugin.css'
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
		
		$form->addElement('select', 'param_delay', array(
            'filters' => array('StringTrim'),
            'label' => '切换时间：',
        	'multiOptions' => array(
        		'4000' => '4秒',
				'3000' => '3秒',
        		'2000' => '2秒',
				'6000'   => '6秒'
        	),
            'required' => true
        ));
		
    	$paramArr = array('param_sectionId', 'param_delay');
    	$form->setParam($paramArr);
    	return $form;
    }
}