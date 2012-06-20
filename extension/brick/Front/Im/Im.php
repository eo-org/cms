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
		$msnArr = explode(':',$this->getParam('msn'));
		$wwArr = explode(':',$this->getParam('ww'));
    	$this->view->qqArr = $qqArr;
		$this->view->msnArr = $msnArr;
		$this->view->wwArr = $wwArr;
    }
    
    public function configParam(Class_Form_Edit $form)
    {
        $form->addElement('text', 'param_qq', array(
            'filters' => array('StringTrim'),
            'label' => 'QQ号码（多个号码以\':\'分隔）：',
            'required' => true
        ));
		$form->addElement('text', 'param_msn', array(
            'filters' => array('StringTrim'),
            'label' => 'MSN号码（多个号码以\':\'分隔）：',
            'required' => true
        ));
		$form->addElement('text', 'param_ww', array(
            'filters' => array('StringTrim'),
            'label' => '旺旺号码（多个号码以\':\'分隔）：',
            'required' => true
        ));
        $form->addElement('select', 'param_display', array(
            'label' => '显示风格',
            'multiOptions' => array('fixed' => '固定位置', 'float' => '漂浮窗')
        ));
    	$paramArr = array('param_qq','param_msn','param_ww','param_display');
    	$form->setParam($paramArr);
    	return $form;
    }
}