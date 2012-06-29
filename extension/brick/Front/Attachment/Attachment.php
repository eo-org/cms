<?php
class Front_Attachment extends Class_Brick_Solid_Abstract
{
	public function prepare()
    {
    	$clf = Class_Layout_Front::getInstance();
    	$res = $clf->getResource();
    	if(is_null($res)) {
    		$attachment = null;
    	} else {
    		$attachment = $res->attachment;
    	}
    	if(is_null($attachment) || count($attachment) == 0) {
    		$this->_disableRender = true;
    	}
    	$this->view->attachment = $attachment;
    }
}