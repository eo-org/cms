<?php
class Front_ProductGroupIndex extends Class_Brick_Solid_Abstract
{
	public function prepare()
	{
		$clf = Class_Layout_Front::getInstance();
		$layoutType = $clf->getType();
		
		$groupItemId = null;
		$resource = $clf->getResource();
		if($layoutType == 'product') {
			$groupItemId = $resource->groupId;
		} else if($layoutType == 'product-list') {
			$groupItemId = $resource->getId();
		}
		
		$groupDoc = App_Factory::_m('Group')->findProductGroup();
		if($this->getParam('level') == 'auto') {
			$branchIndex = $groupDoc->getLevelOneTree($groupItemId);
			$branchIndexArr = array($branchIndex);
		} else {
			$branchIndexArr = $groupDoc->groupIndex;
		}
		
		$this->view->branchIndexArr = $branchIndexArr;
		$this->view->currentGroupItemId = $groupItemId;
	}
	
	public function configParam($form)
	{
    	$form->addElement('select', 'param_level', array(
            'label' => '选择目录组：',
    		'multiOptions' => array('auto' => '动态', 'all' => '全部'),
            'required' => true
        ));
        
    	$paramArr = array('param_level');
    	$form->setParam($paramArr);
    	return $form;
	}
}