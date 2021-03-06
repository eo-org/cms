<?php
class Front_Html extends Class_Brick_Solid_Abstract
{
	public function prepare()
    {
//    	$this->view->content = $this->_params->content;
    }
    
    public function configParam(Class_Form_Edit $form)
    {
    	$form->addElement('textarea', 'param_content', array(
            'filters' => array('StringTrim'),
            'label' => 'HTML：',
            'required' => false,
    		'id' => 'ck_text_editor'
        ));
        $form->addElement('button', 'appendImage', array(
            'filters' => array('StringTrim'),
            'label' => '插入图片',
            'required' => false,
        	'id' => 'append-image'
        ));
        $paramArr = array('param_content', 'appendImage');
    	$form->setParam($paramArr);
        return $form;
    }
}