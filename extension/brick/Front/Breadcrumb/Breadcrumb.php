<?php
class Front_Breadcrumb extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$clf = Class_Layout_Front::getInstance();
    	$layoutType = $clf->getType();
    	
		$id = null;
		$trailType = null;
		switch($layoutType) {
			case 'list':
				$id = $clf->getCurrentActionName();
				$trailType = 'article';
				break;
			case 'article':
				$artical = $clf->getResource();
				
				$id = $artical->groupId;
				$trailType = 'article';
				$this->view->tailMark = $artical->title;
				break;
			case 'product-list':
				$id = $clf->getCurrentActionName();
				$trailType = 'product';
				break;
			case 'product';
				$product = $clf->getResource();
				$id = $product->groupId;
				$trailType = 'product';
				$this->view->tailMark = $product->title;
		}
		
		
		
		
		$linkController = Class_Link_Controller::factory($trailType);
		
		$trailArr = array();
		if(is_null($id)) {
			$this->_disableRender = true;
		} else {
			$trailArr = $linkController->getTrail($id);
		}
		$this->view->trailArr = $trailArr;
    }
}