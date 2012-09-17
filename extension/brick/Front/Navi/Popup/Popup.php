<?php
class Front_Navi_Popup extends Class_Brick_Solid_Abstract
{
	protected $_effectFiles = array(
    	'navi/popup/plugin.js',
		'navi/popup/plugin.css'
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

    	$paramArr = array('param_naviId');
    	$form->setParam($paramArr);
    	return $form;
    }
}
