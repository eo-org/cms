<?php
class Form_Layout_Position_Edit extends Class_Form_Edit
{
	public function init()
	{
		$position = new Zend_Form_Element_Text('position',
			array('filters' => array('StringTrim'),
            'label' => '内部定位名：',
            'required' => true)
    	);
		$request = Zend_Controller_Front::getInstance()->getRequest();
    	$id = $request->getParam('id');
    	if(is_null($id)) {
    		$id = 0;
    	}
    	$position->addValidator(new Zend_Validate_Regex(array('pattern' => '/^[a-z-]+$/')))
    		->addValidator(new Zend_Validate_Db_NoRecordExists(array(
		        'table' => 'layout_position',
		        'field' => 'position',
		        'exclude' => array('field' => 'id', 'value' => $id)
		    )));
        $this->addElement($position);
        
    	$this->addElement('text', 'label', array(
			'filters' => array('StringTrim'),
            'label' => '标题名：',
            'required' => true
		));
    	
		$this->addElement('select', 'style', array(
            'label' => '样式：',
			'multiOptions' => array('none' => '无', 'tab-front' => '选项卡'),
            'required' => true
		));
		
		$tb = Class_Base::_('Layout_Position');
		$rowset = $tb->fetchAll(
			$tb->select(false)->from($tb, array('label', 'sort'))
			->order('sort')
		);
		
		$rowsetArr = array();
		$rowsetArr[] = '页面开始[body]';
		foreach($rowset as $row) {
			$rowsetArr[] = $row->label.'('.$row->sort.')';
		}
		
		$this->addElement('select', 'sort', array(
            'label' => '排序：',
			'multiOptions' => $rowsetArr,
            'required' => true
		));
		
		$this->addElement('textarea', 'header', array(
			'label' => '模块头部（如果不具备html常识，请不要修改）',
			'required' => false
		));
		
		$this->addElement('textarea', 'footer', array(
			'label' => '模块尾部（如果不具备html常识，请不要修改）',
			'required' => false
		));
		
		$this->_main = array('position', 'label', 'style', 'sort');
		$this->_optional = array('header', 'footer');
	}
}