<?php
class Admin_ActionMenu extends Class_Brick_Solid_Abstract
{
	public $menuArr = array();
    
    public function setMenu($menuArr)
    {
    	if(is_array($menuArr)) {
    		$this->menuArr = $menuArr;
    	}
    }
    
    public function prepare()
    {
		$buttonArr = array();
		foreach($this->menuArr as $key => $setting) {
			$label = '';
			$method = 'link';
			$callback = null;
			if(is_array($setting)) {
				$label = $setting['label'];
				$callback = $setting['callback'];
				$method = isset($setting['method']) ? $setting['method'] : 'link';
			} else {
				$type = $setting;
				$urlHelper = new Zend_View_Helper_Url();
				switch($type) {
					case 'create':
						$label = '创建新项目';
						$method = 'link';
						if(empty($callback)) {
							$callback = $urlHelper->url(array('action' => 'create'));
						}
						break;
					case 'save':
					case 'ajax-save':
						$label = '保存';
						$method = 'post';
						if(empty($callback)) {
							$callback = $urlHelper->url();
						}
						break;
					case 'delete':
					case 'ajax-delete':
						$label = '删除';
						$method = 'link';
						if(empty($callback)) {
							$callback = $urlHelper->url(array('action' => 'delete'));
						}
						break;
					case 'func':
						$label = '确认';
						$method = 'func';
						break;
				}
			}
			
//			$buttonArr[] = "<input callback='".$callback."' method='$method' class='control-button' type='button' name='$key' value='$label' />";
			
			
    		$controller = Zend_Controller_Front::getInstance();
    		$request = $controller->getRequest();
    	
			if($method == 'link') {
				if(!$request->isXmlHttpRequest()) {
					$buttonArr[] = "<a class='action-menu' href='".$callback."'>".$label."</a>";
				} else {
					$buttonArr[] = "<a class='action-menu' href='#".$callback."'>".$label."</a>";
				}
			} else {
				$buttonArr[] = "<a class='action-menu' href='".$callback."' method='".$method."'>".$label."</a>";
			}
		}
		$this->view->buttons = $buttonArr;
    }
}