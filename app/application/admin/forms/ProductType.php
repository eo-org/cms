<?php
class Form_ProductPrice extends Zend_Form
{
  public function init()
  {
    $this->setMethod('post');
    
    $this->addElement('text', 'typeName', array(
      'label' => "产品尺寸",
      'required' => true,
      'filters' => array('StringTrim')
    ));
    
    $this->addElement('text', 'price', array(
      'label' => "价格",
      'required' => true,
      'filters' => array('StringTrim')
    ));
    
    $this->addElement('text', 'retailPrice', array(
      'label' => "零售价格",
      'filters' => array('StringTrim')
    ));
    
    $this->addElement('submit', 'submit', array(
      'label' => "保存",
      'order' => 100
    ));
  }
}