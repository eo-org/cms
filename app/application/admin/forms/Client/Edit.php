<?php
class Form_EditClient extends Class_Form
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
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $siteId = $request->getParam('sid');
        $dbValidator = new Zend_Validate_Db_NoRecordExists(array(
        	'table' => 'eo_site',
        	'field' => 'companyName',
            'exclude' => array(
                'field' => 'id',
                'value' => $siteId
            )
        ));
        $companyName = new Zend_Form_Element_Text('companyName', array(
      		'filters' => array('StringTrim'),
      		'label' => '公司名：',
        	'required' => true,
        	'validators' => array($dbValidator)
        ));
        $validToDate = new Zend_Form_Element_Select('validToDate', array(
      		'label' => '有效时间延长：',
        	'multiOptions' => array(
        		1 => '一年正式帐户',
        		3 => '三年正式帐户'
        	),
        	'required' => true
        ));
        $this->addElement($domainName);
        $this->addElement($companyName);
        $this->addElement($validToDate);
    }
}