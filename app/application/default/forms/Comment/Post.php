<?php
class Form_Comment_Post extends Zend_Form
{
	public function init()
	{
        $email = new Zend_Form_Element_Text('email', array(
        	'filters' => array('StringTrim'),
            'label' => '邮箱：',
			'Description' => '请填写正确的邮箱地址，以方便我们回复您的问题',
            'required' => true
        ));
        $validatorEmail = new Class_Validate_EmailSimple();
        $email->addValidators(array($validatorEmail));
        $this->addElement($email);
        
        $this->addElement('textarea', 'content', array(
            'filters' => array('HtmlEntities'),
            'label' => '留言：',
            'required' => true
        ));
        $this->addElement('submit', 'submit', array(
            'label' => '提交'
        ));
	}
}