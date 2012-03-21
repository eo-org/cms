<?php
class Admin_View_Helper_Control extends Zend_View_Helper_Abstract
{
	public function control(Array $controls)
	{
		$HTML = "<div class='control-buttons'>";
		foreach($controls as $key => $setting) {
			$title = '';
			$method = '';
			$type = null;
			$callback = null;
			if(is_array($setting)) {
				$type = $key;
				$callback = $setting['callback'];
			} else {
				$type = $setting;
				$urlHelper = new Zend_View_Helper_Url();
//				$callback = $urlHelper->url();
			}
			switch($type) {
				case 'save':
				case 'ajax-save':
					$title = '保存';
					$method = 'post';
					if(empty($callback)) {
						$callback = $urlHelper->url();
					}
					break;
				case 'delete':
				case 'ajax-delete':
					$title = '删除';
					$method = 'link';
					if(empty($callback)) {
						$callback = $urlHelper->url(array('action' => 'delete'));
					}
					break;
				case 'ajax-func':
					$title = '确认';
					$method = 'func';
					break;
			}
			$HTML.= "<input callback='".$callback."' method='$method' class='control-button' type='button' name='$key' value='$title' />";
		}
		$HTML.= "</div>";
		return $HTML;
	}
}