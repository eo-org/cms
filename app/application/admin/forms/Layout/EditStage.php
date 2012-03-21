<?php
class Form_Layout_EditStage extends Class_Form_Edit
{
	public function init()
	{
		$this->addElement('text', 'uniquId', array(
			'filters' => array('StringTrim'),
			'label' => 'STAGE ID：',
			'required' => true
		));
		$this->_main = array('uniquId');
	}
}