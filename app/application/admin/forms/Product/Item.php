<?php
class Form_Product_Item extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id', 'quickSaveForm');
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
                array('stringLength', true, array(5, 30))
            )
        ));
        
        $this->addElement('checkbox', 'autoSkuTrigger', array(
            'label' => '自动生成产品库存代码和产品名?',
            'required' => false,
            'value' => 1
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
        
        $this->addElement('checkbox', 'display', array(
            'label' => '显示',
            'value' => 0
        ));
        
        $this->addElement('hidden', 'parentId', array(
            'value' => ''
        ));
        
        $this->addElement('button', 'ajax-save', array(
            'label' => '保存',
            'onclick' => 'postQuickProduct();',
            'order' => 1000
        ));
    }
}