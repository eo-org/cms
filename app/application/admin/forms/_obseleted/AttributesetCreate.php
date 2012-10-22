<?php
class Form_AttributesetCreate extends Zend_Form
{
  public function init()
  {
    $this->setMethod('post');
    
    $this->addElement('text', 'name', array(
      'label' => "属性组名字（内部使用）",
      'required' => true,
      'filters' => array('StringTrim')
    ));
  }
  
  public function setMultiOptions($array)
  {
    $this->basement->setMultiOptions($array);
  }
}