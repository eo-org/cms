<?php
class Form_Info_Edit extends Class_Form_Edit
{
    public function init()
    {
        $this->addElement('textarea', 'pageTitle', array(
            'filters' => array('StringTrim'),
            'label' => '网站标题：',
            'required' => true
        ));
        $this->addElement('textarea', 'metakey', array(
            'filters' => array('StringTrim'),
            'label' => 'Keywords：',
            'required' => false
        ));
        $this->addElement('textarea', 'metadesc', array(
            'filters' => array('StringTrim'),
            'label' => 'Description：',
            'required' => false
        ));
        $this->_main = array('pageTitle');
        $this->_optional = array('metakey', 'metadesc');
    }
}