<?php
class Front_AdGroupAccordion extends Class_Brick_Solid_Abstract
{
	protected $_effectFiles = array(
		'ad/accordion/plugin.js',
		'ad/accordion/plugin.css'
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
		
    	$paramArr = array('param_sectionId');
    	$form->setParam($paramArr);
    	return $form;
    }
}