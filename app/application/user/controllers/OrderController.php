<?php
class User_OrderController extends Zend_Controller_Action
{
	public function preDispatch()
	{
		
	}
	
	public function indexAction()
    {
    	$this->view->headTitle("订单管理 ");
    	
    }
	
    public function detailAction()
    {
    	$orderId = $this->getRequest()->getParam('order-id');
    	$orderDoc = App_Factory::_am('Order')->find($orderId);
    	
    	$this->view->orderDoc = $orderDoc;
    }
    
    public function historyListAction()
    {
    	
    }
    
    public function cancelledListAction()
    {
    	
    }
    
	public function cancelAction()
    {
//        $orderId = intval($this->getRequest()->getParam('id'));
//        $orderObj = new Class_Model_Order();
//        $orderObj->setData('id', $orderId)
//            ->load();
//        if (!is_null($orderObj->getData('id')) && $orderObj->getData('status') == 'new') {
//            $orderObj->restoreStock();
//        }
//        $this->_redirector->gotoSimple('order-list', 'user');
    }
    
    public function getOrderListJsonAction()
    {
    	$pageSize = 20;
		$currentPage = 1;
		
		$orderCo = App_Factory::_am('Order');
		$orderCo->setFields(array('fullAddress'));
		$queryArray = array();
		
        $result = array();
		$orderCo->setPage($currentPage)->setPageSize($pageSize);
		$data = $orderCo->fetchAll(true);
		$dataSize = $orderCo->count();
		
		$result['data'] = $data;
        $result['dataSize'] = $dataSize;
        $result['pageSize'] = $pageSize;
        $result['currentPage'] = $currentPage;
        
        return $this->_helper->json($result);
    }
}