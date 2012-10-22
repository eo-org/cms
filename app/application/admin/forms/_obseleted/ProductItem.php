<?php
class Form_Product_Item extends Zend_Form
{
    
    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('text', 'name', array(
            'label' => '产品名',
            'required' => true,
            'filters' => array('StringTrim')
        ));
                
        $this->addElement('text', 'sku', array(
            'label' => '产品库存代码',
            'required' => false,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('stringLength', true, array(5, 20))
            )
        ));
        
        $this->addElement('text', 'price', array(
            'label' => '零售价',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('float')
            )
        ));
        $this->addElement('text', 'origPrice', array(
            'label' => '原价(不填或0表示没有)',
            'filters' => array('StringTrim'),
            'validators' => array(
                array('float')
            )
        ));
        $this->addElement('checkbox', 'active', array(
            'label' => '显示'
        ));
        
        $this->addElement('submit', 'submit', array(
            'label' => '保存',
            'order' => 1000
        ));
    }
}