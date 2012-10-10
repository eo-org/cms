<?php
class Front_Download extends Class_Brick_Solid_Abstract
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
    	} else {
    		$download = array();
    		foreach($attachment as $atta) {
    			if($atta['filetype'] == 'download') {
    				$download[] = $atta;
    			}
    		}
    	}
    	$this->view->download = $download;
    }
}