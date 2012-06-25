<?php
class Rest_BookpageController extends Zend_Rest_Controller 
{
	public function init()
	{
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
	}
	
	public function indexAction()
	{
		$co = App_Factory::_m('Book_Page');
		$data = $co->setFields(array('label', 'link', 'parentId', 'sort'))
			->sort('sort', 1)
			->fetchAll(true);
        return $this->_helper->json($data);
	}
	
	public function getAction()
	{
		
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