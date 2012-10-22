<?php
class Form_Manufacturer extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('text', 'name', array(
            'label' => '产商名',
            'required' => true,
            'filters' => array('StringTrim')
        ));
        
        $this->addElement('text', 'url', array(
            'label' => '产商URL',
            'required' => true,
            'filters' => array('StringTrim')
        ));
        
        $this->addElement('checkbox', 'active', array(
            'label' => '显示'
        ));
    }
}