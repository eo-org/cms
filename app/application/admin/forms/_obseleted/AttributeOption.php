<?php
class Form_AttributeOption extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('text', 'value', array(
          'label' => "属性值",
          'required' => true,
          'filters' => array('StringTrim')
        ));
        
        $this->addElement('text', 'code', array(
          'label' => "属性代码",
          'required' => true,
          'filters' => array('StringTrim')
        ));
        
        $this->addElement('text', 'index', array(
          'label' =>"排序",
          'required' => true,
          'filters' => array('StringTrim')
        ));
        
        $this->addElement('hidden', 'attributeId', array(
          'required' => true,
          'order' => 99
        ));
    }
}