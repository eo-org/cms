<?php
class Admin_View_Helper_Action extends Zend_View_Helper_Abstract
{
	public function action($actionLinks)
	{
		$HTML = '';
		
		if($actionLinks !== null) {
			$HTML.= "<div class='action-links'>";
			foreach($actionLinks as $key => $link) {
				$HTML.= "<a class='link' href='".$link['url']."'>".$link['label']."</a>";
			}
			$HTML.= "</div>";
		}
		return $HTML;
	}
}