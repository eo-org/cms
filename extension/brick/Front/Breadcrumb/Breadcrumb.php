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
				$groupDoc = App_Factory::_m('Group')->findArticleGroup();
				break;
			case 'article':
				$artical = $clf->getResource();
				
				if(is_null($artical) || $artical == 'none') {
					$id = 1;
					$trailType = 'article';
					$this->view->tailMark = 'Article Name';
				} else {
					$id = $artical->groupId;
					$trailType = 'article';
					$this->view->tailMark = $artical->label;
				}
				$groupDoc = App_Factory::_m('Group')->findArticleGroup();
				break;
			case 'product-list':
				$id = $clf->getCurrentActionName();
				$trailType = 'product';
				$groupDoc = App_Factory::_m('Group')->findProductGroup();
				break;
			case 'product';
				$product = $clf->getResource();
				if(is_null($product) || $product == 'none') {
					$id = 1;
					$trailType = 'product';
					$this->view->tailMark = 'Product Name';
				} else {
					$id = $product->groupId;
					$trailType = 'product';
					$this->view->tailMark = $product->label;
				}
				$groupDoc = App_Factory::_m('Group')->findProductGroup();
		}
		
		$trailArr = array();
		if(is_null($id)) {
			$this->_disableRender = true;
		} else {
			$trailArr = $groupDoc->getTrail($id);
		}
		switch($trailType) {
			case 'article':
				$this->view->urlType = 'list';
				break;
			case 'product':
				$this->view->urlType = 'product-list';
				break;
		}
		$this->view->trailArr = $trailArr;
    }
}