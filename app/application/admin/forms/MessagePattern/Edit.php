<?php
class Form_MessagePattern_Edit extends Zend_Form
{
    public function init()
    {
        $this->addElement('text', 'label', array(
            'filters' => array('StringTrim'),
            'label' => '问卷名：',
            'required' => true
        ));
        $this->addElement('text', 'name', array(
            'filters' => array('StringTrim'),
            'label' => '问卷URL (eg: "/form/ask.shtml")：',
            'required' => true
        ));
    }
}