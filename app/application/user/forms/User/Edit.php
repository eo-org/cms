<?php
class Form_User_Edit extends Zend_Form
{
	public function init()
	{
		$this->addElement('select', 'sex', array(
			'multiOptions' =>array(
				'male' => '男',
				'female'=>'女'    
			),
			'filters' => array('StringTrim'),
			'label' => '性别：',
			'required'=>FALSE,
		));

		$this->addElement('text', 'birthday', array(
			'filters' => array('StringTrim'),
			'label' => '出生日期：',
			'validators' => array(
				'Date'
			),
			'required'=>FALSE,
			'description' => '例如: 2009-10-10'
		));

      $this->addElement('text', 'mobile_phone', array(
      'filters' => array('StringTrim'),
      'label' => '手机：',
        'validators' => array(
            'Digits'
            ),
      'required'=>FALSE,
            ));

            $this->addElement('text', 'ID_number', array(
      'filters' => array('StringTrim'), 
      'label' => '身份证号码：',
        'validators' => array(
            'Digits'
            ),
      'required'=>FALSE,
            ));

            $this->addElement('text', 'home_phone', array(
      'filters' => array('StringTrim'),
      'label' => '家庭电话：',
      'required'=>FALSE,
            ));
             
            $this->addElement('text', 'office_phone', array(
      'filters' => array('StringTrim'),
      'label' => '办公电话：',
             
      'required'=>FALSE,
            ));

            $this->addElement('text', 'qq', array(
      'filters' => array('StringTrim'),
      'label' => 'QQ：',
        'validators' => array(
            'Digits'
            ),
      'required'=>FALSE,
            ));
             
            $this->addElement('text', 'msn', array(
      'filters' => array('StringTrim'),
      'label' => 'MSN：',
      'required'=>FALSE,
            ));

            $this->addElement('checkbox', 'newsletter', array(
      'uncheckedValue' => 0,
      'checkedValue' => 1,
      'filters' => array('StringTrim'),
      'label' => '接受产品信息：',
      'required'=>FALSE,
            ));

            $this->addElement('submit', 'submit', array(
      'ignore' => true,
      'label' => '保存',
            ));

            $this->setDecorators(array(
      'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'personal-info')),
            array('Description', array('placement' => 'prepend')),
      'Form'
      ));

	}

	public function loadDefaultDecorators()
	{

	}
}