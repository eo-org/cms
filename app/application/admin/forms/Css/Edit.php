<?php
class Form_Css_Edit extends Zend_Form
{
    public function init()
    {
    	$this->addElement('text', 'id', array(
    		'label' => '对应模块名：',
        	'required' => true,
    		'readonly' => true
    	));
    	
    	$this->addElement('textarea', 'content', array(
    		'filters' => array('StringTrim'),
    		'label' => 'CSS内容',
    		'id' => 'cm-editor',
    		'required' => true
    	));
    }
}