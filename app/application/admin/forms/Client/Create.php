<?php
class Form_CreateClient extends Class_Form
{
    public function init()
    {
        $this->setMethod('post');
        $domainNameValidator = new Zend_Validate_Hostname();
		$domainName = new Zend_Form_Element_Text('domainName', array(
			'filters' => array('StringTrim'),
      		'label' => '域名：',
      		'required' => false,
			'validators' => array($domainNameValidator)
        ));
        
        $dbValidator = new Zend_Validate_Db_NoRecordExists(array(
        	'table' => 'eo_site',
        	'field' => 'companyName'
        ));
        $companyName = new Zend_Form_Element_Text('companyName', array(
      		'filters' => array('StringTrim'),
      		'label' => '公司名：',
        	'required' => true,
        	'validators' => array($dbValidator)
        ));
        $validToDate = new Zend_Form_Element_Select('validToDate', array(
      		'label' => '有效时间：',
        	'multiOptions' => array(
        		7 => '7天测试帐户',
        		15 => '15天测试帐户',
        		365 => '一年正式帐户',
        		1095 => '三年正式帐户'
        	),
        	'required' => true
        ));
        
        $templateRows = Class_Base::_('Eo_Template')->fetchAll();
        $optionArr = array();
        foreach($templateRows as $row) {
            $optionArr[$row->id] = $row->id;
        }
        $templateSelector = new Zend_Form_Element_Select('selectedTemplate', array(
      		'label' => '模板：',
      		'multiOptions' => $optionArr,
        	'required' => true
        ));
        $this->addElement($domainName);
        $this->addElement($companyName);
        $this->addElement($validToDate);
        $this->addElement($templateSelector);
    }
}