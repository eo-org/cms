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
        $form->addElement('select', 'param_showplayer', array(
        		'label' => '是否显示播放器：',
        		'required' => true,
        		'multiOptions' => array('n' => '不显示', 'y' => '显示')
        ));
		$paramArr = array('param_fileurl','param_showplayer');	
		$form->setParam($paramArr);
    	return $form;
    }
}