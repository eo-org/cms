<?php
class Form_Product_Type_Edit extends Class_Form_Edit
{
    public function init()
    {
        $this->addElement('text', 'label', array(
            'label' => '产品类型',
            'required' => true,
            'filters' => array('StringTrim')
        ));
    }
    
//    public function populate($valueArray)
//    {
//    	
//    }
}