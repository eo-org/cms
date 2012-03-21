<?php
class Form_Site_Edit extends Class_Form_Edit
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
        $this->addElement('text', 'logoPath', array(
            'filters' => array('StringTrim'),
            'label' => '网站LOGO：',
            'required' => false
        ));
        $this->_main = array('pageTitle', 'logoPath');
        $this->_optional = array('metakey', 'metadesc');
    }
}