<?php
class Front_AdGroupFancyTransition extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$groupId = $this->getParam('groupId');
    	
    	$tb = Class_Base::_('Ad');
    	$selector = $tb->select()->where('groupId = ?', $groupId);
    	$siteInfo = Zend_Registry::get('siteInfo');
    	$rowset = $tb->fetchAll($selector);
        $this->view->rowset = $rowset;
    }
    
    public function configParam(Class_Form_Edit $form)
    {
    	$tb = Class_Base::_('GroupV2');
    	$selector = $tb->select();
    	$rowset = $tb->fetchAll($selector->where('type = ?', 'ad'));
    	$rowsetArr = Class_Func::buildArr($rowset, 'id', 'label');
    	
        $form->addElement('text', 'param_groupId', array(
            'filters' => array('StringTrim'),
            'label' => '广告组：',
            'required' => true,
        	'class' => 'ad-group-selector'
        ));
        $form->addElement('text', 'param_width', array(
            'filters' => array('StringTrim'),
        	'validators' => array('Alnum'),
            'label' => '宽度：',
            'required' => true
        ));
        $form->addElement('text', 'param_height', array(
            'filters' => array('StringTrim'),
        	'validators' => array('Alnum'),
            'label' => '高度：',
            'required' => true
        ));
        $form->addElement('select', 'param_delay', array(
            'filters' => array('StringTrim'),
            'label' => '切换时间：',
        	'multiOptions' => array(
        		'4000' => '4秒',
        		'3000' => '3秒',
        		'2000' => '2秒',
        		'6000' => '6秒',
        	),
            'required' => true
        ));
        $form->addElement('select', 'param_showLinks', array(
            'filters' => array('StringTrim'),
            'label' => '显示连接：',
        	'multiOptions' => array('y' => '显示', 'n' => '不显示'),
            'required' => true
        ));
    	$paramArr = array('param_groupId', 'param_width', 'param_height', 'param_delay', 'param_showLinks');
    	$form->setParam($paramArr);
    	return $form;
    }
}