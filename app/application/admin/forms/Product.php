<?php
class Form_Product extends Zend_Form
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
        
        $this->addElement('text', 'pointPrice', array(
            'label' => '赠送积分',
            'required' => false,
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
        
		$this->addElement('select', 'show', array(
            'label' => '显示',
            'filters' => array('StringTrim'),
            'multiOptions' => array(0=>'不显示',1 => '只显示在产品列表', 2 => '只显示在搜索页', 3 => '全部都显示')
        ));
    }
}