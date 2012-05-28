<?php
class Front_ListCurrentGroup extends Class_Brick_Solid_Abstract
{
	protected $_artical = null;
	
	public function _init()
	{
		$this->_useTwig = true;
	}
	
	public function getCacheId()
	{
		$groupId = $this->getParam('groupId');
		return 'listcurrentgroup_'.$groupId;
	}
	
	public function prepare()
	{
		$groupId = $this->getParam('groupId');
		if($groupId == 'x') {
			$clf = Class_Layout_Front::getInstance();
			$resource = $clf->getResource();
			if($resource != 'none') {
				$groupId = $resource->groupId;
			}
		}
		$articleTb = Class_Base::_('Artical');
		$selector = $articleTb->select(false)->from($articleTb, array('id', 'title', 'alias', 'introtext', 'introicon', 'created'))
			->where('groupId = ?', $groupId)
			->order('id DESC');
		$articleRowset = $articleTb->fetchAll($selector);
		
		$linkController = Class_Link_Controller::factory('article');
		$link = $linkController->getLink($groupId);
		
		$this->view->rowset = $articleRowset;
		if($groupId != 'x') {
			$this->view->title = $link->label;
		}
	}
	
	public function configParam(Class_Form_Edit $form)
    {
    	$tb = Class_Base::_('GroupV2');
    	$select = $tb->select()->where('type = ?', 'article');
    	$rowset = $tb->fetchAll($select);
        $groupArr = Class_Func::buildArr($rowset, 'id', 'label', array('x' => '动态分配'));
    	
        $form->addElement('select', 'param_groupId', array(
        	'label' => '文章分组：',
        	'multiOptions' => $groupArr
        ));
        
        $paramArr = array('param_groupId');
        $form->setParam($paramArr);
    	return $form;
    }
}