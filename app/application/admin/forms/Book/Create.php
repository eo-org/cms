<?php
class Form_Book_Create extends Class_Form_Edit
{
    public function init()
    {
        $this->addElement('text', 'label', array(
            'filters' => array('StringTrim'),
            'label' => '手册名：',
            'required' => true
        ));
        $this->addElement('text', 'alias', array(
            'filters' => array('StringTrim'),
            'label' => '手册别名(alias)：',
            'required' => true,
        	'description' => '通过别名地址 "/{alias}.shtml" 或者 "/{alias}" 访问手册'
        ));
    }
}