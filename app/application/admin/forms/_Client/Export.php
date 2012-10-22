<?php
class Form_Export extends Class_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->addElement('select', 'categoryId', array(
        	'label' => '模板类型',
        	'multiOptions' => array(1 => 1, 2 => 2),
        	'required' => true,
        	'filters' => array('StringTrim')
        ));
        
        $this->addDisplayGroup(array('categoryId', 'label', 'active'),
        	'main',
            array('legend' => '基本信息', 'class' => 'main-form')
        );
        
        parent::init();
    }
}