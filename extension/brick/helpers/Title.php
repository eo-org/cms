<?php
class Helper_Title extends Zend_View_Helper_Abstract
{
	public function title()
	{
		if($this->view->displayBrickName == 1) {
			return "<div class='title'>".$this->view->brickName."</div>";
		} else if($this->view->displayBrickName == 2) {
			return "<div class='title'></div>";
		} else {
			return "";
		}
	}
}