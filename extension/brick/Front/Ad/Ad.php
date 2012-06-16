<?php
class Front_Ad extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$id = $this->getParam('id');
    	
    	$co = App_Factory::_m('Ad');
    	$doc = $co->find($id);
        $this->view->row = $doc;
    }
    
    public function configParam(Class_Form_Edit $form)
    {
    	$tb = Class_Base::_('Ad');
    	$rowset = $tb->fetchAll();
    	$rowsetArr = Class_Func::buildArr($rowset, 'id', 'name');
    	
        $form->addElement('select', 'param_id', array(
            'filters' => array('StringTrim'),
            'label' => '广告：',
            'required' => true,
        	'multiOptions' => $rowsetArr
        ));
        $form->addElement('select', 'param_showLabel', array(
            'label' => '显示广告名：',
            'required' => true,
        	'multiOptions' => array('hide' => '不显示', 'show' => '显示')
        ));
        $form->addElement('select', 'param_showDescription', array(
            'label' => '显示广告介绍：',
            'required' => true,
        	'multiOptions' => array('hide' => '不显示', 'show' => '显示')
        ));
    	$paramArr = array('param_id', 'param_showLabel', 'param_showDescription');
    	$form->setParam($paramArr);
    	return $form;
    }
}