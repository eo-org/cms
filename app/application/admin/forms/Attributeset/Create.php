<?php
class Form_Attributeset_Create extends Zend_Form
{
    public function init()
    {
        $this->addElement('text', 'label', array(
            'filters' => array('StringTrim'),
            'label' => '表名：',
            'required' => true
        ));
    }
}