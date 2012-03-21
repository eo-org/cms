<?php
class Form_Product_Gift extends Zend_Form
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
        
        $this->addElement('textarea', 'shortDescription', array(
            'label' => '产品简单介绍',
            'required' => false,
            'filters' => array('StringTrim')
        ));
        
        $this->addElement('textarea', 'description', array(
            'label' => '产品详细介绍',
            'required' => false,
            'filters' => array('StringTrim')
        ));
        
        $this->addElement('text', 'pointPrice', array(
            'label' => '购置点数',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('digits')
            )
        ));
    
        $this->addElement('select', 'rank', array(
            'label' => '等级',
            'filters' => array('StringTrim'),
            'multiOptions' => array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5)
        ));
        
        $this->addElement('checkbox', 'display', array(
            'label' => '显示'
        ));
    }
}