<?php
class Form_Article_Edit extends Class_Form_Edit
{
    public function init()
    {
        $this->addElement('text', 'label', array(
            'filters' => array('StringTrim'),
            'label' => '文章名：',
            'required' => true
        ));
        $this->addElement('textarea', 'fulltext', array(
            'filters' => array('StringTrim'),
            'label' => '文章内容：',
            'required' => true,
            'id' => 'ck_text_editor'
        ));
        $this->addElement('button', 'appendImage', array(
            'filters' => array('StringTrim'),
            'label' => '插入图片',
            'required' => false,
        	'id' => 'append-image'
        ));
        
        $clc = Class_Link_Controller::factory('article');
        $items = $clc->toMultiOptions();
        $this->addElement('select', 'groupId', array(
            'label' => '文章分类：',
            'multiOptions' => $items
        ));
        
        $this->addElement('text', 'introicon', array(
        	'filters' => array('StringTrim'),
        	'label' => '摘要图片：',
        	'required' => false,
        	'class' => 'icon-selector'
        ));
        $this->addElement('textarea', 'introtext', array(
            'filters' => array('StringTrim'),
            'label' => '文章摘要：',
        	'style' => 'width: 280px; height: 80px;',
            'required' => false
        ));
        $this->addElement('checkbox', 'featured', array(
        	'label' => '加入精选：',
        	'required' => false
        ));
        
        $this->addElement('text', 'link', array(
            'filters' => array('StringTrim'),
            'label' => '文章静态链接：',
        	'validators' => array(
        		array('Regex', true, array('/^[a-z-\.\/]+$/', 'messages' => array(
        			Zend_Validate_Regex::NOT_MATCH => '静态链接只能包含小写字母,"."和"－"',
        			Zend_Validate_Regex::INVALID => '静态链接只能包含小写字母,"."和"－"'
        		)))
        	),
            'required' => false
        ));
        
        $this->_main = array('label', 'fulltext', 'appendImage');
        $this->_required = array('groupId');
        $this->_optional = array('layoutId', 'introicon', 'introtext', 'featured');
		$this->_param = array('link');
    }
}