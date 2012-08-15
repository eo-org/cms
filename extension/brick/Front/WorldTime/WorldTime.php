<?php
class Front_WorldTime extends Class_Brick_Solid_Abstract
{
	protected $_effectFiles = array(
		'other/worldtime.plugin.js',
	);
	
	public function prepare()
    {
		$country = $this->getParam('country');
    	$countryStr = Zend_Json::encode($country);
		
		$this->view->countryStr = $countryStr;
    }
    
    public function configParam($form)
    {	
        $form->addElement('multicheckbox', 'param_country', array(
			'filters' => array('StringTrim'),
			'label' => '选择国家：',
			'required' => true,
			'multiOptions' => array('beijing'=>'北京','london'=>'伦敦','newyork'=>'纽约','paris'=>'巴黎','berlin'=>'柏林','seoul'=>'首尔','tokyo'=>'东京')
		));
		
		$paramArr = array('param_country');
		$form->setParam($paramArr);
    	return $form;
    }
}