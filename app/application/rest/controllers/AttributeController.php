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
		$co = App_Factory::_am('Attribute');
		$data = $co->fetchAll(true);
        return $this->_helper->json($data);
	}
	
	public function getAction()
	{
		
	}
	
	public function postAction()
	{
		$modelString = $this->getRequest()->getParam('model');
		$jsonArray = Zend_Json::decode($modelString);
		
		$attributeDoc = App_Factory::_am('Attribute')->create($jsonArray);
		$attributeDoc->save();
		
		$this->getResponse()->setHeader('result', 'sucess');
		$this->_helper->json(array('id' => $attributeDoc->getId()));
	}
	
	public function putAction()
	{
		$id = $this->getRequest()->getParam('id');
		$modelString = $this->getRequest()->getParam('model');
		$jsonArray = Zend_Json::decode($modelString);
		
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