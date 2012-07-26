<?php
class Rest_HeadFileController extends Zend_Rest_Controller
{
	public function init()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout()->disableLayout();
	}
	
	public function indexAction()
	{
		$co = App_Factory::_m('HeadFile');
		$data = $co->fetchAll(true);
		
//		var_export($data);
		
//		Zend_Debug::dump($data);
		
		return $this->_helper->json($data);
	}
	
	public function getAction()
	{

	}
	
	public function postAction()
	{
		//$modelString = $this->getRequest()->getParam('model');
		//$jsonArray = Zend_Json::decode($modelString);
		
		$jsonString = file_get_contents('php://input');
		$jsonArray = Zend_Json::decode($jsonString);
		
		$doc = App_Factory::_m('HeadFile')->create($jsonArray);
		$doc->save();
		
		
		$this->getResponse()->setHeader('result', 'success');
		$this->_helper->json(array('id' => $doc->getId()));		
	}
	
	public function putAction()
	{
		$id = $this->getRequest()->getParam('id');
		
		$jsonString = file_get_contents('php://input');
		$jsonArray = Zend_json::decode($jsonString);
		
		$co = App_Factory::_m('HeadFile');
		$doc = $co->find($id);
		$doc->setFromArray($jsonArray);
		$doc->save();
		
		$this->getResponse()->setHeader('result','success');
		$this->_helper->json(array('id' => $id));
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$co = App_Factory::_m('HeadFile');
		$doc = $co->find($id);
		$doc->delete();
		$this->getResponse()->setHeader('result','success');
	}		
}