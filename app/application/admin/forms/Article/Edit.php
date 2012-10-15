<?php
class Form_Article_Edit extends App_Form_Tab
{
    public function init()
    {
        $this->addElement('text', 'label', array(
            'filters' => array('StringTrim'),
            'label' => '内容名：',
            'required' => true
        ));
        $this->addElement('textarea', 'fulltext', array(
            'filters' => array('StringTrim'),
            'label' => '内容详细：',
            'required' => true,
            'id' => 'ck_text_editor'
        ));
        $this->addElement('select', 'groupId', array(
            'label' => '内容分类：'
        ));
        $this->addElement('textarea', 'introtext', array(
            'filters' => array('StringTrim'),
            'label' => '内容摘要：',
        	'style' => 'width: 280px; height: 80px;',
            'required' => false
        ));
        $this->addElement('textarea', 'metakey', array(
            'label' => '内容关键词',
            'required' => false,
            'filters' => array('StringTrim'),
        	'style' => 'width: 280px; height: 80px;'
        ));
        $this->addElement('checkbox', 'featured', array(
        	'label' => '加入精选：',
        	'required' => false
        ));
        $this->addElement('hidden', 'introicon', array(
            'required' => false,
            'filters' => array('StringTrim')
        ));
        
        $this->setTabs(array(
			'main' => array('label', 'groupId', 'fulltext'),
			'optional' => array('introtext', 'metakey', 'featured', 'introicon')
		));
        
    }
}