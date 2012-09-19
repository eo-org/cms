<?php
class Form_Info_Edit extends App_Form_Tab
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
        $this->addElement('text', 'thumbWidth', array(
            'filters' => array('StringTrim'),
            'label' => '缩略图宽度(px)：',
        	'validators' => array(
        		array('int'),
        		array('between', false, array('min' => 10, 'max' => 1000))
        	),
            'required' => false
        ));
        $this->addElement('text', 'thumbHeight', array(
            'filters' => array('StringTrim'),
            'label' => '缩略图高度(px)：',
        	'validators' => array(
        		array('int'),
        		array('between', false, array('min' => 10, 'max' => 1000))
        	),
            'required' => false
        ));
        $this->setTabs(array(
			'main' => array('language', 'pageTitle', 'metakey', 'metadesc'),
			'optional' => array('thumbWidth', 'thumbHeight')
		));
    }
}