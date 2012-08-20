<?php
class Form_Book_Page_Edit extends Class_Form_Edit
{
    public function init()
    {
        $this->addElement('text', 'label', array(
            'filters' => array('StringTrim'),
            'label' => '章节名：',
            'required' => true
        ));
        $this->addElement('textarea', 'fulltext', array(
            'filters' => array('StringTrim'),
            'label' => '章节内容：',
            'required' => true,
            'id' => 'ck_text_editor'
        ));
        $this->addElement('button', 'appendImage', array(
            'filters' => array('StringTrim'),
            'label' => '插入图片',
            'required' => false,
        	'class' => 'icon-selector',
        	'callback' => 'appendToEditor'
        ));
        
        $this->addElement('text', 'link', array(
            'filters' => array('StringTrim'),
            'label' => '静态链接：',
        	'validators' => array(
        		array('Regex', true, array('/^[a-z-\.\/]+$/', 'messages' => array(
        			Zend_Validate_Regex::NOT_MATCH => '静态链接只能包含小写字母,"."和"－"',
        			Zend_Validate_Regex::INVALID => '静态链接只能包含小写字母,"."和"－"'
        		)))
        	),
            'required' => false
        ));
        
        $this->_main = array('label', 'fulltext', 'appendImage');
		$this->_param = array('link');
    }
}