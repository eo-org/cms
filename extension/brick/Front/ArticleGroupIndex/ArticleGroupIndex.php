<?php
class Front_ArticleGroupIndex extends Class_Brick_Solid_Abstract
{
	public function prepare()
	{
		$clf = Class_Layout_Front::getInstance();
		$layoutType = $clf->getType();
		
		$groupItemId = null;
		$resource = $clf->getResource();
		if($resource == 'none') {
			$groupItemId = 0;
		} else {
			if($layoutType == 'article') {
				$groupItemId = $resource->groupId;
			} else if($layoutType == 'list') {
				$groupItemId = $resource->getId();
			}
		}
		
		$groupDoc = $groupDoc = App_Factory::_m('Group')->findArticleGroup();
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