<?php
class Form_Layout_SetPage extends Class_Form_Edit
{
	protected $_type;
	
	public function __construct($type)
	{
		$this->_type = $type;
		parent::__construct();
	}
	
	public function init()
	{
		$tb = Class_Base::_('Layout');
		$selector = $tb->select(false)->from($tb, array('id', 'label'))
			->where('type = ?', $this->_type);
		$rowset = $tb->fetchAll($selector);
		$rowsetArr = array();
		foreach($rowset as $row) {
			$rowsetArr[$row->id] = $row->label;
		}
		if(count($rowsetArr) > 0) {
			$this->addElement('select', 'selectedLayoutId', array(
				'label' => '选择布局名：',
				'multiOptions' => $rowsetArr
			));
			
			$this->_main = array('selectedLayoutId');
		}
	}
}