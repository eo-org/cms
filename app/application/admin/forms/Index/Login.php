<?php
class Form_Index_Login extends Class_Form
{
    public function init()
    {
        $this->setMethod('post');
        
		$nickname = new Zend_Form_Element_Text('loginName', array('filters' => array('StringTrim'),
      		'label' => '用户名：',
      		'required'=>true
        ));
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
        $password->addValidator($validatorPass);
        $this->addElement($password);
        
		$this->addElement('submit', 'submit', array(
            'label' => '登录'
        ));
		
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