<?php
class Form_Login extends Zend_Form
{
    public function init()
    {
    	$tanslator = Zend_Registry::get('Zend_Translate');
    	
        $this->addElement('text', 'loginName', array(
    		'filters' => array('StringTrim', 'StringToLower'),
        	'required' => true,
        	'label' => $tanslator->translate('email')
        ));
        $validatorEmail = new Class_Validate_EmailSimple();
        $this->loginName->addValidators(array($validatorEmail));
        
        $this->addElement('password', 'password', array(
        	'filters' => array('StringTrim'),
        	'required' => true,
        	'label' => $tanslator->translate('password')
        ));
    
        $this->addElement('submit', 'login', array(
        	'label' => '',
            'value' => $tanslator->translate('confirm')
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