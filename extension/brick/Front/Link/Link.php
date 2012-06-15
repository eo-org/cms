<?php
class Front_Link extends Class_Brick_Solid_Abstract
{
	protected $_id = null;
	
	protected function _prepareGearLinks()
	{
		$this->_addGearLink('修改目录连接', '/admin/navi/edit/id/'.$this->_id);
	}
	
    public function prepare()
    {
    	$co = App_Factory::_m('Navi');
    	$doc = $co->fetchOne();
    	
    	$this->view->naviDoc = $doc;
    	
//		$sectionId = $this->_params->sectionId;
//		$this->_id = $sectionId;
//		
//		$naviTb = Class_Base::_('Category');
//		$selector = $naviTb->select()->where('sectionId = ?', $sectionId)
//		    ->order('order ASC');
//		$naviRows = $naviTb->fetchAll($selector);
//		Class_Link_Controller::setRenderer(new Class_Link_Renderer_Default());
//		
//		$linkController = new Class_Link_Controller($naviRows);
//		$head = $linkController->getLinkHead();
//		
//		$this->view->head = $head;
    }
    
    public function configParam($form)
    {
		$co = App_Factory::_m('Navi');
    	$docArr = $co->setFields('label')->fetchArr('label');
		
    	$form->addElement('select', 'param_sectionId', array(
            'label' => '选择目录组：',
    		'multiOptions' => $docArr,
            'required' => true
        ));
    	
        $form->addElement('select', 'param_display', array(
            'label' => '显示方式：',
    		'multiOptions' => array('text' => '文字', 'bg' => '背景图'),
            'required' => true
        ));
        
    	$paramArr = array('param_sectionId', 'param_display');
    	$form->setParam($paramArr);
    	return $form;
    }
}
