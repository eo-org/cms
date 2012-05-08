<?php
class Form_Ad_Group_Edit extends Zend_Form
{
    public function init()
    {
        $this->addElement('text', 'label', array(
            'filters' => array('StringTrim'),
            'label' => '广告名（系统）：',
            'required' => true
        ));
    }
}