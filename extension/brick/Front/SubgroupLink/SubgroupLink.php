<?php
class Front_SubgroupLink extends Class_Brick_Solid_Abstract
{
	public function _init()
	{
		$this->_useTwig = true;
	}
	
	public function prepare()
    {
    	$id = $this->getParam('groupId');
    	if($id == 0) {
			$routerName = Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();
			
			if($routerName == 'list') {
				$id = $this->_request->getParam('id');
			} else if($routerName == 'artical') {
				$aid = $this->_request->getParam('id');
				$artical = Class_Base::_('Artical')->find($aid)->current();
				if(!is_null($artical)) {
					$id = $artical->groupId;
				}
			}
			$currentId = $id;
			
			$group = Class_Tree_Solid_Group::findBranchById($id);
			if($group->parentId != 0) {
				$id = $group->parentId;
				$group = Class_Tree_Solid_Group::findBranchById($id);
			}
    	} else {
    		$group = Class_Tree_Solid_Group::findBranchById($id);
    	}
    	
    	$this->view->group = $group;
    	$this->view->currentId = $currentId;
    }
    
	public function configParam(Class_Form_Edit $form)
    {
    	$form->addElement('select', 'param_groupHeader', array(
            'filters' => array('StringTrim'),
            'label' => '组头部：',
        	'multiOptions' => array(
    			'sublinks' => '仅显示子链接',
    			'both' => '同时显示父链接和子链接'
    		),
            'required' => true
        ));
        
        $table = new Class_Model_GroupV2_Tb();
        $selector = $table->select()->where('type = ?', 'article');
        $items = $table->fetchSelectOption(array(0 => '自动适应'), $selector);
        
        $form->addElement('select', 'param_groupId', array(
            'label' => '选择分组',
            'multiOptions' => $items,
        	'required' => true
        ));
    	
    	$paramArr = array('param_groupHeader', 'param_groupId');
    	$form->setParam($paramArr);
    	return $form;
    }
}