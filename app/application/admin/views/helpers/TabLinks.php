<?php
class Admin_View_Helper_TabLinks extends Zend_View_Helper_Abstract
{
	public function tabLinks()
	{
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$controllerName = $request->getControllerName();
		
		$tabsGroup = $controllerName;
		if(!is_null($request->getParam('tabs-group'))) {
			$tabsGroup = $request->getParam('tabs-group');
		}
		$config = new Zend_Config_Ini(APP_PATH.'/configs/tablinks.ini');
		$naviArr = array();
		if(!is_null($config->$tabsGroup)) {
			$naviArr = $config->$tabsGroup;
		}
		
		$HTML = "<div class='tab-links'>";
		foreach($naviArr as $navi) {
			$componts = explode(':', $navi);
			if($componts[2] == $controllerName) {
				$HTML.= "<a class='link selected' href='".$componts[1]."'>".$componts[0]."</a>";
			} else {
				$HTML.= "<a class='link' href='".$componts[1]."'>".$componts[0]."</a>";
			}
		}
		$HTML.= "</div>";
		return $HTML;
	}
}