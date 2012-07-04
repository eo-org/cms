<?php
class Navi_Edit extends Class_Form_Edit
{
    public function init()
    {
        $this->addElement('text', 'label', array(
            'filters' => array('StringTrim'),
            'label' => '目录组名：',
            'required' => true
        ));
        
        $this->addElement('text', 'description', array(
            'filters' => array('StringTrim'),
            'label' => '目录组简介：'
        ));
        
        $this->_main = array('label', 'description');
    }
}