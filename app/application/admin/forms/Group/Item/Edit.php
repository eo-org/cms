<?php
class Form_Group_Item_Edit extends Class_Form_Edit
{
    public function init()
    {
        $this->addElement('text', 'label', array(
            'filters' => array('StringTrim'),
            'label' => '分组名：',
            'required' => true
        ));
        
        $this->addElement('text', 'link', array(
            'filters' => array('StringTrim'),
            'label' => '链接地址：',
            'required' => false
        ));
        
        $this->addElement('text', 'css', array(
            'filters' => array('StringTrim'),
            'label' => 'CSS Name：',
            'required' => false
        ));
        
        $this->_main = array('label', 'link', 'css');
    }
}