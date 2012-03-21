<?php class Form_Admin extends Zend_Form
{
	public function init()
	{
		
        $this->setMethod('post');
		$nickname = new Zend_Form_Element_Text('nickname', array('filters' => array('StringTrim'),
      		'label' => '用户名：',
      		'required'=>true
        ));
    
		$validatorDB = new Zend_Validate_Db_NoRecordExists('admin', 'nickname');
        $validatorDB->setMessages(array(
            Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND => '很抱歉，此用户名已被注册。'
        ));
		$nickname->addValidators(array($validatorDB));
        $this->addElement($nickname);
        
        $password = new Zend_Form_Element_Password('password', array(
      		'filters' => array('StringTrim'),
      		'label' => '密码：',
        	'required' => true
        ));
        $validatorPass = new Zend_Validate_StringLength(5, 16);
        $validatorPass->setMessages(array(
            Zend_Validate_StringLength::TOO_SHORT => '很抱歉，密码为5-16位的字母、数字。',
            Zend_Validate_StringLength::TOO_LONG => '很抱歉，密码为5-16位的字母、数字。'
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
      		'label' => '确认密码：',
        	'required'=>true
        ));
        $this->addElement($password2);
		
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