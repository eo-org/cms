<?php
class Shop_PaymentGatewayController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->headTitle("订单付款");
    }

    public function indexAction()
    {
        $orderId = $this->getRequest()->getParam('order-id');
        
        if(empty($orderId)) {
            $this->_redirector->gotoSimple('index', 'index');
        }
        $order = App_Factory::_am('Order')->find($orderId);
        if(is_null($order)) {
            $this->_redirector->gotoSimple('no-order', 'payment-gateway');
        }
        if($order->paid == 1) {
            $this->_redirector->gotoSimple('paid-order', 'payment-gateway');
        }
        
        if($order->paymentMethod == 'payment-upon-arrival-order') {
            $this->_redirector->gotoSimple('payment-upon-arrival-order', 'payment-gateway', 'default', array('order-id' => $orderId));
        } else {
            $alipay = new Class_Payment_Socket_Alipay();
            $alipayUrl = $alipay->createUrl($order, $_SERVER['HTTP_HOST']);
            
            $this->view->link = $alipayUrl;
        }
        
        $this->view->order = $order;
    }
    
    public function returnAction()
    {
        $getParams = $this->getRequest()->getParams();
        $orderId = $this->getRequest()->getParam('out_trade_no');
        if(empty($orderId)) {
            die("order id not found! 订单ID无法找到，情拨打客服电话！错误编码：0001");
        }
        $alipay = new Class_Payment_Socket_Alipay();
        $verify_result = $alipay->returnVerify($getParams);
        if($verify_result) {
            $db = Zend_Registry::get('dbAdaptor');
            $rows_affected = $db->update('orders', array ('paid' => '1'), $db->quoteInto('id = ?', $orderId));
            if( $rows_affected > 0){
                Class_Core::log('alipay return verify - success : '.$orderId, 'payment');
            } else {
                die("order id not found! 订单ID无法找到，情拨打客服电话！");
            }
        } else {
            $log = "";
            foreach($getParams as $key => $value) {
                $log.= $key.'='.$value.'&';
            }
            Class_Core::log('alipay return verify - failed : '.$orderId.'; request :'.$log, 'payment');
            die("verification failed. 订单验证无法通过，情拨打客服电话！");
        }
    }
    
    public function notifyAction()
    {
        $alipay = new Class_Payment_Socket_Alipay();
        $verify_result = $alipay->notifyVerify();
        
        $result = "fail";
        if($verify_result) {
            $orderId = $_POST['out_trade_no'];
            if($_POST['trade_status'] == 'WAIT_BUYER_PAY') {
                $result = "success";
            } else if($_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
                $result = "success";
            } else if($_POST['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
                $result = "success";
            } else if($_POST['trade_status'] == 'TRADE_FINISHED') {
                $result = "success";
            }
        }
        
        if($result == "success") {
            $db = Zend_Registry::get('dbAdaptor');
            $rows_affected = $db->update('orders', array ('paid' => '1'), $db->quoteInto('id = ?', $orderId));
            if($rows_affected == 0) {
                $result = "fail";
            }
        }
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        echo $result;
    }
    
    public function paidOrderAction()
    {
        die('此订单已经付款');
    }
    
    public function noOrderAction()
    {
        die('找不到此订单');
    }
    
    public function paymentUponArrivalOrderAction()
    {
        $orderId = $this->getRequest()->getParam('order-id');
        $order = App_Factory::_am('Order')->find($orderId);
        
        $this->view->order = $order;
    }
}