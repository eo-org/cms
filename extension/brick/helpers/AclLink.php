<?php
class Helper_AclLink extends Zend_View_Helper_Abstract
{
	public function aclLink($controllerName, $actionName, $label)
	{
		$acl = Class_Acl::getInstance();
		$adminSession = Class_Session_Admin::getInstance();
		$roleId = $adminSession->getRoleId();
		
		if($acl->isAllowed($roleId, $controllerName)) {
			return "<li><a href='/admin/".$controllerName."/".$actionName."'>".$label."</a></li>";
		} else {
			return "";
		}
	}
}