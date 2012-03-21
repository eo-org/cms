<?php
class Admin_ExtensionController extends Zend_Controller_Action
{
	public function editAction()
	{
		$extName = $this->getRequest()->getParam('extname');
		$extTb = Class_Base::_('Extension');
		$extRow = $extTb->find($extName)->current();
		
		if(is_null($extRow)) {
			$siteDb = Zend_Registry::get('siteDb');
	    	$tb = new Zend_Db_Table(array(
	    		Zend_Db_Table_Abstract::ADAPTER => $siteDb,
	    		Zend_Db_Table_Abstract::NAME => 'extension'
	    	));
	    	$row = $tb->find($extName)->current();
	    	if(is_null($row)) {
				throw new Exception('Extension not exist in system');
	    	}
	    	
			$extRow = $extTb->createRow($row->toArray());
		}
		$solidBrick = $extRow->createSolidBrick($this->getRequest());
		$form = $solidBrick->getGlobalForm();
		if(!is_null($extRow->params)) {
	    	$params = Zend_Json_Decoder::decode($extRow->params);
	    	foreach($params as $key => $value) {
	    		$el = $form->getElement('param_'.$key);
	    		if(!is_null($el)) {
	    			$el->setValue($value);
	    		}
	    	}
    	}
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$paramArr = array();
    		foreach($form->getValues() as $key => $value) {
    			if(strpos($key, 'param_') !== false) {
    				$jsonKey = substr($key, 6);
    				$paramArr[$jsonKey] = $value;
    			}
    		}
    		$paramStr = Zend_Json_Encoder::encode($paramArr);
    		$extRow->params = $paramStr;
    		$extRow->save();
		}
		
		$this->view->form = $form;
	}
}