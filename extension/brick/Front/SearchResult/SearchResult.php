<?php
class Front_SearchResult extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$type = $this->_request->getParam('type');
    	$keywords = $this->_request->getParam('keywords');
    	$page = $this->_request->getParam('page');
    	if(empty($page)) {
    		$page = 1;
    	}
    	$pageSize = 20;
    	
    	$rowset = array();
    	
    	if(!empty($keywords)) {
    		if(is_null($type) || $type == 'product') {
    			$type = 'product';
    			$co = App_Factory::_m('Product');
    			
    			$rowset = $co->setFields(array('label', 'introtext', 'introicon', 'attributeDetail', 'created'))
    				->addFilter('$or', array(
    					array('label' => new MongoRegex("/".$keywords."/")),
    					array('name' => new MongoRegex("/^".$keywords."/"))
    				))->setPage($page)
    				->setPageSize($pageSize);
    		} else {
    			$co = App_Factory::_m('Article');
    			
    			$rowset = $co->setFields(array('label', 'introtext', 'introicon', 'created'))
    				->addFilter('label', new MongoRegex("/^".$keywords."/"))
    				->setPage($page)
    				->setPageSize(50);
    		}
    	}
    	
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination-search.phtml');
		Zend_Paginator::setDefaultScrollingStyle('Sliding');
		 
		$dataSize = $co->count();
		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Null($dataSize));
		$paginator->setCurrentPageNumber($page)
			->setItemCountPerPage($pageSize);
		 
		$rowset = $co->fetchDoc();
		
		$this->view->rowset = $rowset;
		$this->view->paginator = $paginator;
    	
    	$this->view->rowset = $rowset;
    	$this->view->type = $type;
    }
}