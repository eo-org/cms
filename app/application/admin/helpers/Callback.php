<?php
class Callback extends Zend_Controller_Action_Helper_Abstract
{
	public function gsoto()
	{
		$request = $this->_actionController->getRequest();
		echo "??";
		echo $request->getActionName();
		if($request->isXmlHttpRequest()) {
			die('joy');
		}
		die('pain');
	}
}