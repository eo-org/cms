<?php
class Form_Book_Create extends Class_Form_Edit
{
    public function init()
    {
        $this->addElement('text', 'label', array(
            'filters' => array('StringTrim'),
            'label' => '书名：',
            'required' => true
        ));
    }
}