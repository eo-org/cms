<?php
class Form_Brick_EditTpl extends Zend_Form
{
    public function init()
    {
    	$this->addElement('text', 'tplFileName', array(
    		'label' => 'TPL文件名：',
    		'validators' => array(
                array('regex', true, array("/[a-z]+\.tpl$/"))
            ),
        	'required' => true
    	));
    	
    	$this->addElement('textarea', 'tplFileContent', array(
    		'filters' => array('StringTrim'),
    		'label' => 'CSS内容',
    		'required' => true
    	));
    }
}