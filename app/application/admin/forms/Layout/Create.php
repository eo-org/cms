<?php
class Form_Layout_Create extends Class_Form_Edit
{
	public function init()
	{
		$this->addElement('text', 'label', array(
			'filters' => array('StringTrim'),
			'label' => '标题[中文]：',
			'required' => true
		));
		$this->addElement('text', 'controllerName', array(
			'filters' => array('StringTrim'),
			'label' => '布局名[a-z]：',
			'required' => true
		));
		$this->addElement('select', 'type', array(
			'filters' => array('StringTrim'),
			'label' => '布局类型：',
			'multiOptions' => array(
				'article' => '单个文章',
				'list' => '文章列表',
				'product' => '单个产品',
				'product-list' => '产品列表',
				'book' => '手册',
				'frontpage' => '综合页面'
			),
			'required' => true
		));
		$this->_main = array('label', 'controllerName', 'type');
	}
}