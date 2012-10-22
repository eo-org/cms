<?php
class Form_MessagePattern_EditQuestion extends Class_Form_Edit
{
	public function init() {
		$this->addElement('text', 'label', array(
			'filters' => array('StringTrim'),
	            'label' => '问题标题：',
	            'required' => true
        ));
	}
}
	