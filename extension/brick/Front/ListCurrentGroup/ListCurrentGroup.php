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
		$articleCo = App_Factory::_m('Article');
		$articleCo->setFields(array('id', 'label', 'link', 'introtext', 'introicon', 'created'))
			->addFilter('groupId', $groupId)
			->sort('weight');
		$articleDocs = $articleCo->fetchDoc();
//		Zend_Debug::dump($articleDocs);
		$linkController = Class_Link_Controller::factory('article');
		$link = $linkController->getLink($groupId);
		
		$this->view->rowset = $articleDocs;
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