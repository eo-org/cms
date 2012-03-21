<?php
class Form_ContactUs_Edit extends Class_Form
{
    public function init()
    {
        $this->addElement('text', 'companyName', array(
            'filters' => array('StringTrim'),
            'label' => '公司名：',
            'required' => false
        ));
        $this->addElement('text', 'contact', array(
            'filters' => array('StringTrim'),
            'label' => '联系人：',
            'required' => false
        ));
        $this->addElement('text', 'tel', array(
            'filters' => array('StringTrim'),
            'label' => '电话：',
            'required' => false
        ));
        $this->addElement('text', 'add', array(
            'filters' => array('StringTrim'),
            'label' => '地址：',
            'required' => false
        ));
        $this->addElement('text', 'postcode', array(
            'filters' => array('StringTrim'),
            'label' => '邮编：',
            'required' => false
        ));
        $this->addElement('text', 'email', array(
            'filters' => array('StringTrim'),
            'label' => '邮箱：',
            'required' => false
        ));
    }
}