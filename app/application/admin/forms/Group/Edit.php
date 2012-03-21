<?php
class Form_Group_Edit extends Class_Form_Edit
{
	private $_hasChildren = 0;
	
	public function __construct($hasChildren)
	{
		$this->_hasChildren = $hasChildren;
		parent::__construct();
	}
	
    public function init()
    {
        $this->addElement('text', 'label', array(
            'filters' => array('StringTrim'),
            'label' => '分类名：',
            'required' => true
        ));
		$this->addElement('textarea', 'introtext', array(
            'filters' => array('StringTrim'),
            'label' => 'Group Intro：',
            'required' => false
        ));
        $this->addElement('text', 'introicon', array(
            'filters' => array('StringTrim'),
            'label' => 'Group Banner：',
            'required' => false,
        	'class' => 'icon-selector'
        ));
        $this->addElement('text', 'alias', array(
            'filters' => array('StringTrim'),
            'label' => '分组静态链接：',
        	'validators' => array(
        		array('Regex', true, array('/^[a-z-\.\/]+$/', 'messages' => array(
        			Zend_Validate_Regex::NOT_MATCH => '静态链接只能包含小写字母,"."和"－"',
        			Zend_Validate_Regex::INVALID => '静态链接只能包含小写字母,"."和"－"'
        		)))
        	),
            'required' => false
        ));
        
        $this->_main = array('label', 'introtext', 'introicon', 'alias');
    }
}