<?php
class Front_Im extends Class_Brick_Solid_Abstract
{
	public function getCacheId()
	{
		$groupId = $this->getParam('groupId');
		return "im_".$this->_brick->brickId;
	}
	
    public function prepare()
    {
    	$qqArr = explode(':', $this->getParam('qq'));
    	$this->view->qqArr = $qqArr;
    }
    
    public function configParam(Class_Form_Edit $form)
    {
        $form->addElement('text', 'param_qq', array(
            'filters' => array('StringTrim'),
            'label' => 'QQ号码（多个号码以\':\'分隔）：',
            'required' => true
        ));
        $form->addElement('select', 'param_display', array(
            'label' => '显示风格',
            'multiOptions' => array('fixed' => '固定位置', 'float' => '漂浮窗')
        ));
    	$paramArr = array('param_qq', 'param_display');
    	$form->setParam($paramArr);
    	return $form;
    }
}