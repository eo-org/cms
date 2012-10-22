<?php
class Form_Order_Address extends Zend_Form
{
    public function init()
    {
        $cityValidator = new Zend_Validate_GreaterThan(1);
        $cityValidator->setMessages(
            array(Zend_Validate_GreaterThan::NOT_GREATER => '请选择一个有效的城市')
        );
        $postcodeValidator1 = new Zend_Validate_Digits();
        $postcodeValidator1->setMessages(array(
            Zend_Validate_Digits::NOT_DIGITS => '请确保输入的全为数字 '
        ));
        $postcodeValidator2 = new Zend_Validate_StringLength(6, 6);
        $postcodeValidator2->setMessages(array(
            Zend_Validate_StringLength::TOO_SHORT => '请确保输入邮编为6位 ',
            Zend_Validate_StringLength::TOO_LONG => '请确保输入邮编为6位 '
        ));
        
        $mobielValidator1 = new Zend_Validate_Digits();
        $mobielValidator1->setMessages(array(
            Zend_Validate_Digits::NOT_DIGITS => '请确保输入的全为数字 '
        ));
        $mobielValidator2 = new Zend_Validate_StringLength(11, 11);
        $mobielValidator2->setMessages(array(
            Zend_Validate_StringLength::TOO_SHORT => '输入手机号不少于11位 ',
            Zend_Validate_StringLength::TOO_LONG => '输入手机号不多于11位 '
        ));
        
        $this->addElement('text', 'consignee', array(
            'label' => '收  件  人：',
            'filters' => array('StringTrim'),
        	'required' => true,
            'class' => 'input-element'
        ));
        $provinceData = Class_Core::_('Address')->getProvinceCollectionData();
        $provinceData[0] = '请选择';
        ksort($provinceData);
        $this->addElement('select', 'provinceUnitId', array(
            'label' => '省　　市：',
            'required' => false,
            'registerInArrayValidator' => false,
            'multiOptions' => $provinceData,
            'class' => 'select-element'
        ));
        $this->addElement('select', 'cityUnitId', array(
            'label' => '',
            'required' => true,
            'registerInArrayValidator' => false,
            'multiOptions' => array(0 => '----------'),
            'class' => 'select-element',
            'validators' => array($cityValidator)
        ));
        $this->addElement('text', 'addressDetail', array(
            'label' => '街道地址：',
            'filters' => array('StringTrim'),
            'required' => true,
            'class' => 'input-element'
        ));
        $this->addElement('text', 'postcode', array(
            'label' => '邮政编码：',
            'filters' => array('StringTrim'),
            'required' => false,
            'class' => 'input-element',
            'validators' => array($postcodeValidator1, $postcodeValidator2)
        ));
        $this->addElement('text', 'mobile', array(
            'label' => '手　　机：',
            'value' => '',
            'filters' => array('StringTrim'),
            'required' => true,
            'class' => 'input-element',
            'description' => '(必填) ',
            'validators' =>array($mobielValidator1, $mobielValidator2)
        ));        
        $this->addElement('text', 'phone', array(
            'label' => "电　　话：",
            'filters' => array('StringTrim'),
            'required' => false,
            'class' => 'input-element'
        ));
        $this->addDisplayGroup(
            array('consignee', 'provinceUnitId','cityUnitId','addressDetail','postcode','mobile','phone'),
            'address'
        );
        $this->addElement('checkbox', 'saveAddress', array(
            'label' => "保存地址到地址簿：",
            'filters' => array('StringTrim'),
            'required' => false
        ));
        $this->setDecorators(array(
            'FormElements'
        ));
    }
}