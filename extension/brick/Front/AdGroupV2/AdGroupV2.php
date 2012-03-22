<?php
class Front_AdGroupV2 extends Class_Brick_Solid_Abstract
{
	protected $_id = null;
	
	protected function _prepareGearLinks()
	{
		$this->_addGearLink('增加新广告图', '/admin/ad/create/groupId/'.$this->_id);
	}
	
    public function prepare()
    {
    	$this->_id = $this->getParam('groupId');
    	
    	$tb = Class_Base::_('Ad');
    	$selector = $tb->select()->where('groupId = ?', $this->_id);
    	$rowset = $tb->fetchAll($selector);
    	
    	$numPerSlide = $this->getParam('numPerSlide');
    	$numPerSlide = empty($numPerSlide) ? 1 : $numPerSlide;
    	$numPage = ceil($rowset->count()/$numPerSlide);
    	$this->view->numPage = $numPage;
        $this->view->rowset = $rowset;
    }
    
    public function configParam($form)
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
        $form->addElement('select', 'param_showLabel', array(
            'label' => '显示广告名：',
            'required' => true,
        	'multiOptions' => array('n' => '不显示', 'y' => '显示')
        ));
        $form->addElement('select', 'param_showDescription', array(
            'label' => '显示广告介绍：',
            'required' => true,
        	'multiOptions' => array('n' => '不显示', 'y' => '显示')
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
        $form->addElement('text', 'param_margin', array(
            'filters' => array('StringTrim'),
        	'validators' => array('Alnum'),
            'label' => '左右间隔：',
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
        $form->addElement('select', 'param_numPerSlide', array(
            'label' => '单页广告数量：',
            'required' => true,
        	'multiOptions' => array(1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 8 => '8')
        ));
    	$paramArr = array('param_groupId', 'param_showLabel', 'param_showDescription', 'param_width', 'param_height', 'param_margin', 'param_delay', 'param_numPerSlide');
    	$form->setParam($paramArr);
    	return $form;
    }
}