<?php
class Rest_TreeleafController extends Zend_Rest_Controller 
{
	public function init()
	{
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
	}
	
	public function indexAction()
	{
		$treeType = $this->getRequest()->getHeader('Tree_Type');
		$treeId = $this->getRequest()->getHeader('Tree_Id');
		switch($treeType) {
			case 'navi':
				$co = App_Factory::_m('Navi_Link')
					->addFilter('naviId', $treeId)
					->setFields(array('label', 'link', 'parentId', 'className', 'sort', 'description'));
				break;
			case 'book':
				$co = App_Factory::_m('Book_Page')
					->addFilter('bookId', $treeId)
					->setFields(array('label', 'link', 'parentId', 'sort'));
				break;
			case 'group':
				$co = App_Factory::_m('Group_Item')
					->addFilter('groupType', $treeId)
					->setFields(array('label', 'alias', 'parentId', 'className', 'sort'));
				break;
		}
		
		$data = $co->addSort('sort', 1)
			->addSort('_id', -1)
			->fetchAll(true);
        return $this->_helper->json($data);
	}
	
	public function getAction()
	{
		$co = App_Factory::_m('Book_Page');
		$data = $co->setFields(array('label', 'link', 'parentId', 'sort'))
			->sort('sort', 1)
			->fetchAll(true);
        return $this->_helper->json($data);
	}
	
	public function postAction()
	{
		$treeType = $this->getRequest()->getHeader('Tree_Type');
		$treeId = $this->getRequest()->getHeader('Tree_Id');
		switch($treeType) {
			case 'navi':
				$co = App_Factory::_m('Navi_Link');
				$doc = $co->create();
				$doc->naviId = $treeId;
				break;
			case 'book':
				$co = App_Factory::_m('Book_Page');
				$doc = $co->create();
				$doc->bookId = $treeId;
				break;
			case 'group':
				$co = App_Factory::_m('Group_Item');
				$doc = $co->create();
				$doc->groupType = $treeId;
				break;
		}
		
		$data = file_get_contents('php://input');
		$jsonArray = Zend_Json::decode($data);
		
		$doc->setFromArray($jsonArray);
		$doc->save();
		
		$this->getResponse()->setHeader('result', 'sucess');
		$this->_helper->json(array('id' => $doc->getId()));
	}
	
	public function putAction()
	{
		$id = $this->getRequest()->getParam('id');
		
		$treeType = $this->getRequest()->getHeader('Tree_Type');
		switch($treeType) {
			case 'navi':
				$co = App_Factory::_m('Navi_Link');
				break;
			case 'book':
				$co = App_Factory::_m('Book_Page');
				break;
			case 'group':
				$co = App_Factory::_m('Group_Item');
				break;
		}
		
		$data = file_get_contents('php://input');
		$jsonArray = Zend_Json::decode($data);
		
		$doc = $co->find($id);
		$doc->setFromArray($jsonArray);
		$doc->save();
		
		$this->getResponse()->setHeader('result', 'sucess');
		$this->_helper->json(array('id' => $id));
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		
		$treeType = $this->getRequest()->getHeader('Tree_Type');
		switch($treeType) {
			case 'navi':
				$co = App_Factory::_m('Navi_Link');
				break;
			case 'book':
				$co = App_Factory::_m('Book_Page');
				break;
			case 'group':
				$co = App_Factory::_m('Group_Item');
				break;
		}
		
		$childDoc = $co->addFilter('parentId', $id)
			->fetchOne();
		if(is_null($childDoc)) {
			$doc = $co->find($id);
			$doc->delete();
			$this->getResponse()->setHeader('result', 'sucess');
		} else {
			$this->getResponse()->setHeader('result', 'fail');
			echo "不能删除非空的节点！";
		}
	}
}