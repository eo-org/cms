<?php class Form_Order_Customer extends Zend_Form
{
	public function init()
	{
		$email = new Zend_Form_Element_Text('email', array('filters' => array('StringTrim', 'StringToLower'),
      		'label' => '邮箱：',
            'Description' => '用于下次购物时的登录名',
      		'required'=>true
        ));
    
        $validatorDB = new Zend_Validate_Db_NoRecordExists('customer_entity', 'email');
        $validatorDB->setMessages(array(
            Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND => '很抱歉，此Email地址已被注册。'
        ));
        $validatorEmail = new Class_Validate_EmailSimple();
        $email->addValidators(array($validatorDB, $validatorEmail));
        
        $this->addElement($email);

        $this->addElementPrefixPath('Class_Form_Decorator',
                    'Class/Form/Decorator',
                    'decorator');
        $this->setDecorators(array(
    		'FormElements',
            array('HtmlTag', array('tag' => 'div', 'class' => 'register-form')),
      		'Form'
	    ));
	}
}