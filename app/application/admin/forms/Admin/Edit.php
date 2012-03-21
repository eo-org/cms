<?php class Form_Admin_Edit extends Zend_Form
{
	public function init()
	{
		$loginName = new Zend_Form_Element_Text('loginName', array('filters' => array('StringTrim'),
      		'label' => '用户名：',
      		'required'=>true
        ));
    
        $request = Zend_Controller_Front::getInstance()->getRequest();
    	$id = $request->getParam('id');
    	if(is_null($id)) {
    		$id = 0;
    	}
    	$loginName->addValidator(new Zend_Validate_Regex(array('pattern' => '/^[a-z\.]+$/')))
    		->addValidator(new Zend_Validate_Db_NoRecordExists(array(
		        'table' => 'admin',
		        'field' => 'loginName',
		        'exclude' => array('field' => 'id', 'value' => $id)
		    )));
        $this->addElement($loginName);
	}
}