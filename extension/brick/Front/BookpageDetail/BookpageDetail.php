<?php
class Front_BookpageDetail extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$clf = Class_Layout_Front::getInstance();
    	$actionName = $clf->getCurrentActionName();
    	$co = App_Factory::_m('Book_Page');
    	
    	$pageDoc = $co->addFilter('$or', array(
    		array('_id' => new MongoId($actionName)),
    		array('link' => $actionName)
    	))->fetchOne();
    	
    	$this->view->doc = $pageDoc;
    }
    
    public function configParam($form)
    {
    	$showTitle = new Zend_Form_Element_Select('param_showTitle', array(
    		'filters' => array('StringTrim'),
    		'label' => '显示标题',
    		'multiOptions' => array('n' => '不显示', 'y' => '显示'),
    		'required' => false
    	));
        $form->addElement($showTitle);
        
        $paramArr = array('param_showTitle');
        $form->setParam($paramArr);
        
        return $form;
    }
}