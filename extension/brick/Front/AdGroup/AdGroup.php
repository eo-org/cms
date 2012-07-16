<?php
class Front_AdGroup extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$sectionId = $this->getParam('sectionId');
    	    	
    	$co = App_Factory::_m('Ad');
    	$rowset = $co->addFilter('sectionId', $sectionId)
    		->fetchDoc();
//    	
//    	$numPerSlide = $this->getParam('numPerSlide');
//    	$numPerSlide = empty($numPerSlide) ? 1 : $numPerSlide;
//    	$numPage = ceil($rowset->count()/$numPerSlide);
//    	$this->view->numPage = $numPage;
        $this->view->rowset = $rowset;
    }
    
    public function configParam($form)
    {
    	$co = App_Factory::_m('Ad_Section');
    	$options = $co->fetchArr('label');
    	
        $form->addElement('select', 'param_sectionId', array(
            'filters' => array('StringTrim'),
            'label' => '广告组：',
            'required' => true,
        	'multiOptions' => $options
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
        $form->addElement('select', 'param_numSwitching', array(
        		'label' => '切换图片数量：',
        		'required' => true,
        		'multiOptions' => array(1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 8 => '8')
        ));
		
    	$paramArr = array('param_groupId', 'param_showLabel', 'param_showDescription', 'param_width', 'param_height', 'param_margin', 'param_delay', 'param_numPerSlide');
    	$form->setParam($paramArr);
    	return $form;
    }
}