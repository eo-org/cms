<?php
class Form_Tag extends Class_Form
{
    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('text', 'name', array(
            'label' => 'TAG名字',
            'required' => true,
            'filters' => array('StringTrim')
        ));
        
        $this->addElement('text', 'value', array(
            'label' => 'TAG链接',
            'required' => true,
            'filters' => array('StringTrim')
        ));
        
        $this->addElement('text', 'index', array(
            'label' => '排序',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('between', true, array(1, 20)),
            )
        ));
        
        $this->addElement('submit', 'submit', array(
            'label' => '确认'
        ));
    }
}