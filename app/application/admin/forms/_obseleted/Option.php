<?php
class Form_Option extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('text', 'name', array(
          'label' => "选项名",
          'required' => true,
          'filters' => array('StringTrim')
        ));
        
        $this->addElement('text', 'optionUnit', array(
          'label' => "选项单位",
          'filters' => array('StringTrim')
        ));
        
        $this->addElement('checkbox', 'isStandard', array(
          'label' => "标准选项"
        ));
    }
}