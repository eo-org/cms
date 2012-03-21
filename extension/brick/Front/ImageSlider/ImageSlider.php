<?php
class Front_ImageSlider extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$adArr = $this->getParam('adArr');
    	$tb = new Class_Model_Ad_Tb();
    	$rowset = $tb->fetchAll($tb->select()->where('id in ('.implode(',', $adArr).')'));
    	
    	$this->view->rowset = $rowset;
    	$this->view->assign('interval', $this->getParam('interval', 6000));
    	$this->view->assign('slidingType', $this->getParam('slidingType', 'vertical'));
        $this->view->width = $this->_params->width;
        $this->view->height = $this->_params->height;
    }
    
    public function configParam(Class_Form_Edit $form)
    {
    	
    	$tb = Class_Base::_('Ad');
    	$rowset = $tb->fetchAll($tb->select(false)->from($tb, array('id', 'name')));
    	$rowsetArr = Class_Func::buildArr($rowset, 'id', 'name');
    	$form->addElement('multiselect', 'param_adArr', array(
            'filters' => array('StringTrim'),
            'label' => '选择需要轮换的广告：',
    		'multiOptions' => $rowsetArr,
            'required' => true
        ));
    	
        $form->addElement('select', 'param_slidingType', array(
            'filters' => array('StringTrim'),
            'label' => '切换模式：',
            'required' => true,
        	'multiOptions' => array('vertical' => '纵向', 'horizontal' => '横向')
        ));
        
        $form->addElement('select', 'param_interval', array(
            'filters' => array('StringTrim'),
            'label' => '切换速度：',
            'required' => true,
        	'multiOptions' => array(1000 => '1秒', 3000 => '3秒', 6000 => '6秒', 100000 => '10秒')
        ));
        
        $form->addElement('text', 'param_width', array(
            'filters' => array('StringTrim'),
            'label' => '宽度：',
            'required' => false
        ));
        
        $form->addElement('text', 'param_height', array(
            'filters' => array('StringTrim'),
            'label' => '高度：',
            'required' => false
        ));
        
    	$paramArr = array('param_adArr', 'param_slidingType', 'param_interval', 'param_width', 'param_height');
    	$form->setParam($paramArr);
    	return $form;
    }
}