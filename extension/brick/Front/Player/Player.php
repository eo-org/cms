<?php
class Front_Player extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	
    }
    
    public function configParam(Class_Form_Edit $form)
    {
        $form->addElement('text', 'param_fileurl', array(
            'filters' => array('StringTrim'),
            'label' => '媒体文件url：',
            'required' => true
        ));
		
		$paramArr = array('param_fileurl');
		$form->setParam($paramArr);
    	return $form;
    }
}