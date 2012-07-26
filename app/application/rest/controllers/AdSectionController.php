<?php
class Rest_AdSectionController extends Zend_Rest_Controller 
{
	public function init()
	{
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
	}
	
	public function indexAction()
	{
		$co = App_Factory::_m('Ad_Section');
		$data = $co->fetchAll(true);
        return $this->_helper->json($data);
	}
	
	public function getAction()
	{
		
	}
	
	public function postAction()
	{
		//$modelString = $this->getRequest()->getParam('model');
		$modelString = file_get_contents('php://input');
		$jsonArray = Zend_Json::decode($modelString);
		
		$doc = App_Factory::_m('Ad_Section')->create($jsonArray);
		$doc->save();
		
		$this->getResponse()->setHeader('result', 'sucess');
		$this->_helper->json(array('id' => $doc->getId()));
	}
	
	public function putAction()
	{
		$id = $this->getRequest()->getParam('id');
		
		$jsonString = file_get_contents('php://input');
		$jsonArray = Zend_json::decode($jsonstring);
		
		$co = App_Factory::_m('Ad_Section');
		$doc = $co->find($id);
		$doc->setFromArray($jsonArray);
		$doc->save();
		
		$this->getResponse()->setHeader('result','success');
		$this->_helper->json(array('id' => $id));
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$co = App_Factory::_m('Ad_Section');
		$doc = $co->find($id);
		$doc->delete();
		$this->getResponse()->setHeader('result','success');
	}
}