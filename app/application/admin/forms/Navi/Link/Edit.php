<?php
class Form_Navi_Link_Edit extends Class_Form_Edit
{
    public function init()
    {
        $this->addElement('text', 'label', array(
            'filters' => array('StringTrim'),
            'label' => '连接名：',
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