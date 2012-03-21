<?php
class Front_Search extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$keywords = $this->_request->getParam('keywords');
    	if(!empty($keywords)) {
    		$table = Class_Base::_('Product');
			$selector = $table->select()->from($table, array('id', 'title', 'introtext', 'introicon', 'created'))
				->where('title like  %'.$keywords.'%')
				->limit(50)
				->order('id DESC');
			$rowset = $table->fetchAll($selector);
			$this->view->rowset = $rowset;
    	}
    }
}