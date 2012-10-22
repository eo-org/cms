<?php
class Form_Account extends Zend_Form
{
    public function init()
    {
        $this->addElement('text', 'loginName', array(
            'filters' => array('StringTrim'),
            'class' => 'loginName',
            'label' => '用户名：',
            'required' => true
        ));
        
        $this->addElement('password', 'password_old', array(
            'filters' => array('StringTrim'),
            'label' => '原密码：',
            'required' => true
        ));
        
        $this->addElement('password', 'password', array(
            'filters' => array('StringTrim'),
            'label' => '新密码：',
            'validators' => array(array('StringLength',true,array(6,12)))
        ));
        $this->addElement('password', 'password_2', array(
            'filters' => array('StringTrim'),
            'label' => '确认密码：'
        ));
        $this->password_2->addValidator('identical', true, array(isset($_POST['password']) ? $_POST['password'] : ''));
        
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'user-password')),
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));
    }
}