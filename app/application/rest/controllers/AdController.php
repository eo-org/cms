<?php
class Rest_AdController extends Zend_Rest_Controller 
{
	public function init()
	{
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
	}
	
	public function indexAction()
	{
		$sectionId = $this->getRequest()->getHeader('Section_Id');
		if(is_null($sectionId)) {
			throw new Exception('section id is null');
		}
		$co = App_Factory::_m('Ad');
		$data = $co->addFilter('sectionId', $sectionId)
			->addSort('sort', 1)
			->fetchArr(false);
			
		return $this->_helper->json($data);
	}
	
	public function getAction()
	{
		
	}
	
	public function postAction()
	{
		$sectionId = $this->getRequest()->getHeader('Section_Id');
		$sectionDoc = App_Factory::_m('Ad_Section')
			->find($sectionId);
		if(is_null($sectionDoc)) {
			throw new Exception('section doc not found with id:'.$sectionId);
		}
		
		$modelString = $this->getRequest()->getParam('model');
		$jsonArray = Zend_Json::decode($modelString);
		
		$doc = App_Factory::_m('Ad')->create($jsonArray);
		$doc->sectionId = $sectionId;
		$doc->save();
		$sectionDoc->updatePreview();
		
		$this->getResponse()->setHeader('result', 'sucess');
		$this->_helper->json(array('id' => $doc->getId()));
	}
	
	public function putAction()
	{
		
	}
	
	public function deleteAction()
	{
		
	}
}