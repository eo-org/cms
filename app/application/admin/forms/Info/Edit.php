<?php
class Form_Info_Edit extends Class_Form_Edit
{
    public function init()
    {
    	$this->addElement('select', 'language', array(
            'filters' => array('StringTrim'),
            'label' => '网站前台主要语言：',
    		'multiOptions' => array(
    			'zh_CN' => '中文_简体',
    			'en_US' => 'English_US'
    		),
            'required' => true
        ));
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
        $this->_main = array('language', 'pageTitle');
        $this->_optional = array('metakey', 'metadesc');
    }
}