<?php
class Form_Login extends Zend_Form
{
    public function init()
    {
        $this->addElement('text', 'loginName', array(
    		'filters' => array('StringTrim', 'StringToLower'),
        	'required' => true,
        	'label' => '邮箱'
        ));
        $validatorEmail = new Class_Validate_EmailSimple();
        $this->loginName->addValidators(array($validatorEmail));
        
        $this->addElement('password', 'password', array(
        	'filters' => array('StringTrim'),
        	'required' => true,
        	'label' => '密码'
        ));
    
        $this->addElement('submit', 'login', array(
            'label' => '',
            'value' => '确认'
        ));
    
        $this->setDecorators(array(
        	'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'login-form')),
            array('Description', array('placement' => 'prepend')),
        	'Form'
        ));
        
        $this->addElementPrefixPath('Class_Form_Decorator',
                    'Class/Form/Decorator',
                    'decorator');
        $this->setDecorators(array(
    		'FormElements',
            array('HtmlTag', array('tag' => 'div', 'class' => 'register-form')),
            'Description',
      		'Form'
	    ));
	    
	    $this->setElementDecorators(array('Composite'));
    }
}