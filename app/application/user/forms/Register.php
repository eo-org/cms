<?php
class Form_Register extends Zend_Form
{
	public function init()
	{
		$loginName = new Zend_Form_Element_Text('loginName', array('filters' => array('StringTrim', 'StringToLower'),
      		'label' => '邮箱',
            'Description' => '用于您下次登录时的用户名',
      		'required'=>true
		));

		$validatorDB = new Class_Form_Validator_Mongo_NoRecordExists('User', 'loginName');
		$validatorDB->setMessages(array(
			Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND => '很抱歉，此Email地址已被注册。'
		));
		$validatorEmail = new Class_Validate_EmailSimple();
		$loginName->addValidators(array($validatorDB, $validatorEmail));
		$this->addElement($loginName);

		$password = new Zend_Form_Element_Password('password', array(
      		'filters' => array('StringTrim'),
      		'label' => '密码',
            'Description' => '您登录时需要的密码',
        	'required' => true
		));
		$validatorPass = new Zend_Validate_StringLength(6, 16);
		$validatorPass->setMessages(array(
			Zend_Validate_StringLength::TOO_SHORT => '很抱歉，密码请设为6-16位的字母、数字。',
			Zend_Validate_StringLength::TOO_LONG => '很抱歉，密码请设为6-16位的字母、数字。'
		));
		$validatorPass2Value = new Zend_Validate_Identical(isset($_POST['password_2']) ? $_POST['password_2'] : '');
		$validatorPass2Value->setMessages(array(
			Zend_Validate_Identical::MISSING_TOKEN => '请填写确认密码。',
			Zend_Validate_Identical::NOT_SAME => '很抱歉，两次确认密码不正确。'
		));
		$password->addValidators(array($validatorPass, $validatorPass2Value));
		$this->addElement($password);

		$password2 = new Zend_Form_Element_Password('password_2', array(
      		'filters' => array('StringTrim'),
      		'label' => '确认密码',
            'Description' => '请再次输入您的密码',
        	'required'=>true
		));
		$this->addElement($password2);

		$submit = new Zend_Form_Element_Submit('register', array(
      		'label' => '',
            'value' => '确认',
            'order' => 100
		));
		$this->addElement($submit);

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