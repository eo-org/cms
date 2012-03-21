<?php
class Form_Edit extends Class_Form_Edit
{
    public function init()
    {
        $this->addElement('text', 'brickName', array(
            'filters' => array('StringTrim'),
            'label' => '模块名：',
            'required' => true
        ));
        $this->addElement('select', 'displayBrickName', array(
            'label' => '模块名：',
        	'multiOptions' => array(
        		0 => '不显示',
        		1 => '显示标题',
        		2 => '仅显示DIV'
			),
            'required' => true
        ));
        $this->addElement('text', 'sort', array(
            'filters' => array('StringTrim'),
            'label' => '模块排序：',
        	'validators' => array(array('Float')),
        	'required' => false
        ));
        $this->addElement('text', 'cssSuffix', array(
            'filters' => array('StringTrim'),
            'label' => 'CSS后缀：',
            'required' => false
        ));
        $this->addElement('select', 'tplName', array(
            'filters' => array('StringTrim'),
            'label' => 'TPL文件：',
            'required' => true
        ));
        
        $this->addElement('hidden', 'layoutId', array(
            'filters' => array('StringTrim'),
            'required' => true
        ));
        $this->addElement('hidden', 'stageId', array(
            'filters' => array('StringTrim'),
            'required' => true
        ));
        $this->addElement('hidden', 'spriteName', array(
            'filters' => array('StringTrim'),
            'required' => true
        ));
        $this->layoutId->setDecorators(array('ViewHelper'));
        $this->stageId->setDecorators(array('ViewHelper'));
        $this->spriteName->setDecorators(array('ViewHelper'));
        
    	$this->_main = array('brickName', 'displayBrickName', 'cssSuffix', 'sort', 'tplName');
    }
}