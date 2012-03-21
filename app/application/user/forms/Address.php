<?php
class Form_Address extends Zend_Form
{
  public function init()
  {
    $this->setMethod('post');

//    $this->addElement('select', 'provinceUnitId', array(
//        'label' => '省份/直辖市',
//        'required' => true
//    ));
//    $this->addElement('select', 'cityUnitId', array(
//        'label' => '市/区',
//        'required' => true,
//        'registerInArrayValidator' => false
//    ));
    $this->addElement('text', 'addressDetail', array(
        'label' => "街道地址",
        'filters' => array('StringTrim'),
        'required' => true
    ));
    $this->addElement('text', 'postcode', array(
        'label' => "邮编",
        'filters' => array('StringTrim'),
        'required' => true
    ));
    $this->addElement('text', 'consignee', array(
        'label' => "收货人姓名",
        'filters' => array('StringTrim'),
        'required' => true
    ));        
    $this->addElement('text', 'mobilePhone', array(
        'label' => "手机",
        'filters' => array('StringTrim'),
        'required' => true
    ));        
    $this->addElement('text', 'landLine', array(
        'label' => "电话",
        'filters' => array('StringTrim'),
        'required' => false
    ));  

    $this->addElement(new Zend_Form_Element_Submit(
                            'addressSubmit', 
                            array(
                                'label'    => '保存新地址',
                                'required' => false,
                                'ignore'   => true
                            )
    ));          

    $this->setDecorators(array(
        'FormElements',
        array('HtmlTag', array('tag' => 'dl', 'class' => 'address_subform')),
        array('Description', array('placement' => 'prepend'))
    ));    
  }
}