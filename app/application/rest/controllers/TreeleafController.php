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
					->addFilter('naviId', $treeId);
				break;
			case 'book':
				$co = App_Factory::_m('Book_Page')
					->addFilter('bookId', $treeId);
				break;
			case 'group':
				$co = App_Factory::_m('Group_Item')
					->addFilter('groupType', $treeId);
				break;
		}
		
		$data = $co->setFields(array('label', 'link', 'parentId', 'sort'))
			->addSort('sort', 1)
			->addSort('_id', -1)
			->fetchAll(true);
        return $this->_helper->json($data);
	}
	
	public function getAction()
	{
//		if() {
//			
//		}
		$co = App_Factory::_m('Book_Page');
		$data = $co->setFields(array('label', 'link', 'parentId', 'sort'))
			->sort('sort', 1)
			->fetchAll(true);
        return $this->_helper->json($data);
	}
	
	public function postAction()
	{
		$modelString = $this->getRequest()->getParam('model');
		$jsonArray = Zend_Json::decode($modelString);
		
		$attributeDoc = App_Factory::_m('Book_Page')->create($jsonArray);
		$attributeDoc->save();
		
		$this->getResponse()->setHeader('result', 'sucess');
		$this->_helper->json(array('id' => $attributeDoc->getId()));
	}
	
	public function putAction()
	{
		$modelString = $this->getRequest()->getParam('model');
		$jsonArray = Zend_Json::decode($modelString);
		
		$attributeDoc = App_Factory::_m('Book_Page')->find($jsonArray['id']);
		$attributeDoc->setFromArray($jsonArray);
		
		$attributeDoc->save();
		$this->getResponse()->setHeader('result', 'sucess');
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$attributeDoc = App_Factory::_m('Book_Page')->find($id);
		$attributeDoc->delete();
		$this->getResponse()->setHeader('result', 'sucess');
	}
}