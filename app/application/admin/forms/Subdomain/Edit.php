<?php
class Form_Edit extends Class_Form
{
    public function init()
    {
    	$name = new Zend_Form_Element_Text('name', array('filters' => array('StringTrim'),
            'label' => '子域名：',
            'required' => true)
    	);
    	
    	$request = Zend_Controller_Front::getInstance()->getRequest();
    	$id = $request->getParam('id');
    	if(is_null($id)) {
    		$id = 0;
    	}
    	$name->addValidator(new Zend_Validate_Regex(array('pattern' => '/[a-z]/')))
    		->addValidator(new Zend_Validate_Db_NoRecordExists(array(
		        'table' => 'subdomain',
		        'field' => 'name',
		        'exclude' => array('field' => 'id', 'value' => $id)
		    )));
        $this->addElement($name);
        
        $this->addElement('text', 'label', array(
            'filters' => array('StringTrim'),
            'label' => '子站点名：',
            'required' => true
        ));
        
        $this->addElement('text', 'logoPath', array(
            'filters' => array('StringTrim'),
        	'class' => 'logo-selector',
            'label' => 'LOGO：',
            'required' => false
        ));
        
        $this->addElement('checkbox', 'active', array(
            'label' => '激活站点'
        ));
        
        $this->_main = array('name', 'label', 'logoPath', 'active');

    }
}