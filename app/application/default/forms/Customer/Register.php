<?php
class Form_Customer_Register extends Zend_Form
{
    public function init()
    {
	    $this->setDescription('注册新用户');
	    $this->setMethod('post');
	    
        $email = new Zend_Form_Element_Text('email', array(
        	'filters' => array('StringTrim', 'StringToLower'),
      		'label' => 'Email地址',
            'Description' => '请填写正确的Email作为用户名，以方便您在忘记密码时取回密码。',
      		'required'=>true
        ));
        $validatorDB = new Zend_Validate_Db_NoRecordExists('customer', 'email');
        $validatorDB->setMessages(array(
            Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND => '很抱歉，此Email地址已被注册。'
        ));
        $validatorEmail = new Class_Validate_EmailSimple();
//        $validatorEmailLength = new Zend_Validate_StringLength(5, 50);
        $email->addValidators(array($validatorEmail));
        $this->addElement($email);
        
        $password = new Zend_Form_Element_Password('password', array(
      		'filters' => array('StringTrim'),
      		'label' => '设定密码',
            'Description' => '密码请设定为6-16位数字或字母。',
        	'required' => true
        ));
        $validatorPass = new Zend_Validate_StringLength(6, 16);
        $validatorPass->setMessages(array(
            Zend_Validate_StringLength::TOO_SHORT => '很抱歉，密码请设为6-16位的字母、数字。',
            Zend_Validate_StringLength::TOO_LONG => '很抱歉，密码请设为6-16位的字母、数字。'
        ));
        $validatorPass2Value = new Zend_Validate_Identical(isset($_POST['password_2']) ? $_POST['password_2'] : '');
        $validatorPass2Value->setMessages(array(
            Zend_Validate_Identical::MISSING_TOKEN => '',
            Zend_Validate_Identical::NOT_SAME => '很抱歉，两次确认密码不正确。'
        ));
        $password->addValidators(array($validatorPass, $validatorPass2Value));
        $this->addElement($password);
        
        $password2 = new Zend_Form_Element_Password('password_2', array(
      		'filters' => array('StringTrim'),
      		'label' => '再次确认密码',
            'Description' => '',
        	'required'=>true
        ));
        $this->addElement($password2);
        
        $submit = new Zend_Form_Element_Submit('register', array(
      		'label' => '注册',
            'value' => '注册',
            'order' => 100
        ));
        $this->addElement($submit);
        
//        $this->addElementPrefixPath('Form_Decorator',
//                            APP_PATH.'/default/forms/decorator',
//                            'decorator');
//        $this->setDecorators(array(
//    		'FormElements',
//            array('HtmlTag', array('tag' => 'div', 'class' => 'register-form')),
//      		'Form'
//	    ));
//        $this->setElementDecorators(array('Simple'));
  }
}