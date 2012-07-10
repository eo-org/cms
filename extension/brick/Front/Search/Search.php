<?php
class Front_Search extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$type = $this->_request->getParam('type');
    	$keywords = $this->_request->getParam('keywords');
    	$rowset = array();
    	
    	if(!empty($keywords)) {
    		if(is_null($type) || $type == 'product') {
    			$co = App_Factory::_m('Product');
    			
    			$rowset = $co->setFields('label', 'introtext', 'introicon', 'created')
    				->addFilter('label', new MongoRegex("/^".$keywords."/"))
    				->setPage(1)
    				->setPageSize(50)
    				->fetchDoc();
    		} else {
    			$co = App_Factory::_m('Article');
    			
    			$rowset = $co->setFields('label', 'introtext', 'introicon', 'created')
    				->addFilter('label', new MongoRegex("/^".$keywords."/"))
    				->setPage(1)
    				->setPageSize(50)
    				->fetchDoc();
    		}
    	}
    	$this->view->rowset = $rowset;
    }
}