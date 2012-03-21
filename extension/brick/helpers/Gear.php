<?php
class Helper_Gear extends Zend_View_Helper_Abstract
{
	public function gear()
	{
		return $this;
	}
	
	public function links()
	{
		return "";
//		if(Class_Session_Admin::isLogin() && is_array($this->view->gearLinks)) {
//			$links = $this->view->gearLinks;
//			
//			if(Class_Session_Admin::getRoleId() == 0) {
//				array_push($links, "<a href='#/admin/brick/edit/brick-id/".$this->view->brickId."'>模块设定</a>");
////	        	array_push($links, "<a href='#/admin/brick/edit-css/brick-id/".$this->view->brickId."'>编辑CSS</a>");
//			}
//			
//			$HTML = "<div class='gear'>";
//			$HTML.= "<div class='icon'></div>";
//			$HTML.= "<ul>";
//			foreach($links as $link) {
//				$HTML.= "<li>".$link."</li>";
//			}
//			
//			$HTML.= "</ul></div>";
//			return $HTML;
//		} else {
//			return "";
//		}
	}
	
	public function frontCss()
	{
		if(Class_Session_Admin::isLogin()) {
			return ' admin-front';
		} else {
			return '';
		}
	}
}