<?php
class Rest_AttributeController extends Zend_Rest_Controller 
{
	public function init()
	{
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
	}
	
	public function indexAction()
	{
		$attributesetId = $this->getRequest()->getHeader('Attributeset_Id');
	
		$co = App_Factory::_am('Attribute')->addFilter('attributesetId', $attributesetId);
		
		$data = $co->fetchArr(false);
        return $this->_helper->json($data);
	}
	
	public function getAction()
	{
		
	}
	
	public function postAction()
	{
		$attributesetId = $this->getRequest()->getHeader('Attributeset_Id');
		
		$data = file_get_contents('php://input');
		$jsonArray = Zend_Json::decode($data);
		
		$attributeDoc = App_Factory::_am('Attribute')->create($jsonArray);
		$attributeDoc->attributesetId = $attributesetId;
		$attributeDoc->save();
		
		$this->getResponse()->setHeader('result', 'sucess');
		$this->_helper->json(array('id' => $attributeDoc->getId()));
	}
	
	public function putAction()
	{
		$id = $this->getRequest()->getParam('id');
		
		$data = file_get_contents('php://input');
		$jsonArray = Zend_Json::decode($data);
		
		$attributeDoc = App_Factory::_am('Attribute')->find($id);
		$attributeDoc->setFromArray($jsonArray);
		$attributeDoc->save();
		
		$this->getResponse()->setHeader('result', 'sucess');
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$attributeDoc = App_Factory::_am('Attribute')->find($id);
		$attributeDoc->delete();
		$this->getResponse()->setHeader('result', 'sucess');
	}
}