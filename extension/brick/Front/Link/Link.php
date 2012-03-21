<?php
class Front_Link extends Class_Brick_Solid_Abstract
{
	protected $_id = null;
	
	protected function _prepareGearLinks()
	{
		$this->_addGearLink('修改目录连接', '/admin/category/index/sectionId/'.$this->_id)
			->_addGearLink('新增目录连接', '/admin/category/create/sectionId/'.$this->_id);
	}
	
    public function prepare()
    {
		$sectionId = $this->_params->sectionId;
		$this->_id = $sectionId;
		
		$naviTb = Class_Base::_('Category');
		$selector = $naviTb->select()->where('sectionId = ?', $sectionId)
		    ->order('order ASC');
		$naviRows = $naviTb->fetchAll($selector);
		Class_Link_Controller::setRenderer(new Class_Link_Renderer_Default());
		
		$linkController = new Class_Link_Controller($naviRows);
		$head = $linkController->getLinkHead();
		
		$this->view->head = $head;
    }
    
    public function configParam($form)
    {
		$tb = new Class_Model_Category_Section_Tb();
		$rowset = $tb->fetchAll();
		$rowsetArr = Class_Func::buildArr($rowset, 'id', 'name');
    	
    	$form->addElement('select', 'param_sectionId', array(
            'label' => '选择目录组：',
    		'multiOptions' => $rowsetArr,
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
