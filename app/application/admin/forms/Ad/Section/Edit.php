<?php
class Form_Ad_Section_Edit extends Class_Form_Edit
{
    public function init()
    {
    	$this->addElement('text', 'label', array(
            'filters' => array('StringTrim'),
    		'label' => '广告分类名',
    		'required' => true
        ));
        $this->_main = array('label');
    }
}