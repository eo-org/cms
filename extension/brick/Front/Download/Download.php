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
    	
    	$download = array();
    	if(is_null($attachment) || count($attachment) == 0) {
    		$this->_disableRender = true;
    	} else {
	    	foreach($attachment as $atta) {
	    		if($atta['filetype'] == 'download') {
	    			$download[] = $atta;
	    		}
	    	}
    	}
    	if(count($download) == 0) {
    		$this->_disableRender = true;
    	}
    	
    	$this->view->download = $download;
    }
}