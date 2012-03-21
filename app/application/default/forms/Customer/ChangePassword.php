<?php
class Form_Customer_ChangePassword extends Zend_Form
{
	public function init()
	{
		$this->addElement('password', 'password_old', array(
			'filters' => array('StringTrim'),
			'label' => '原密码：',   
			'required'=>true,
		));

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
        
		$this->addElement('submit', 'changePassword', array(
	        'ignore' => true,
	        'label' => '确认',
		));
	}
}