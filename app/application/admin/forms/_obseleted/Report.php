<?php
class Form_Report extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->addElement('radio', 'sort', array(
        	'label' => '分类：',
            'required'=>true,
            'filters' => array('StringTrim'),
            'separator'=>'',
            'multiOptions'=>array('Sale'=>'销售总量','User'=>'用户','Product'=>'产品','Category'=>'目录')            
        ));
        $this->addElement('text','userId',array(
        	'label'=>'用户ID：',
            'required'=>false,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('int')
            )        
        ));
        $this->addElement('text','email',array(
        	'label'=>'用户邮箱：',
            'required'=>false,
            'filters' => array('StringTrim'),      
        ));
        $validatorEmail = new Class_Validate_EmailSimple();
        $this->email->addValidators(array($validatorEmail));
                
        $this->addElement('text','productId',array(
        	'label'=>'产品ID：',
            'required'=>false,
            'filters' =>array('StringTrim'),
            'validators' => array(
                array('int')
            )        
        ));
        $this->setDecorators(array(
    		'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'report-form')),
            array('Description', array('placement' => 'prepend')),
      		'Form'
	    ));        
                                
    }
}