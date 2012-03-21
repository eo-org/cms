<?php
class Shop_OrderController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $cart = App_Cart::factory('general');
        if($cart->isEmpty()) {
//            Class_Cart::addErrorMsg('在付款之前,请选择您需要的商品!');
            $this->_helper->redirector->gotoSimple('index', 'index', 'shop');
        }
        $csu = Class_Session_User::getInstance();
        if(!$csu->isLogin()) {
//            Class_Customer::addErrorMsg('请登录之后再付款! 新用户请点击 <a href=\'/user/register/\'>注册新用户</a>');
            $this->_helper->redirector->gotoSimple('login', 'index', 'user', array('ref' => base64_encode('/order/')));
        }
    }
    
    public function init()
    {
        $this->view->headTitle("订单处理");
//        $this->view->headScript()->appendFile('/scripts/plugins/city-finder.js');
//        $this->view->headScript()->appendFile('/scripts/plugins/json-parser.js');
//        $this->view->headScript()->appendFile('/scripts/order.js');
//        $this->view->headLink()->appendStylesheet('/style/order.css');
//        $this->view->headLink()->appendStylesheet('/style/order-carttable.css');
        
//        $this->_redirector = $this->_helper->getHelper('Redirector');
//        $this->_redirector->setCode(303);
//        
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('get-form', 'html')
//            ->addActionContext('get-address', 'html')
//            ->addActionContext('validate-address-json', 'json')
//            ->addActionContext('get-payment', 'html')
//            ->addActionContext('get-checkout-json', 'json')
            ->initContext();
    }

    public function indexAction()
    {
        //require (APP_PATH . '/default/forms/Order.php');
//        require (APP_PATH . '/default/forms/Order/Invoice.php');
//        require (APP_PATH . '/default/forms/Order/Message.php');
//        
//        $invoiceForm = new Form_Order_Invoice();
//        $messageForm = new Form_Order_Message();
        $cart = App_Cart::factory('general');
        
//        if($this->getRequest()->isPost()) {
//            $invoiceForm->setDefaults($this->getRequest()->getParams());
//            $messageForm->setDefaults($this->getRequest()->getParams());
//            $orderId = 0;
//            if(Class_Order::validate()) {
//                try {
//                	Class_Order::setData('message_content', $messageForm->getValue('message_content'));
////                    Class_Order::setData('pointGiven', Class_Cart::getTotalPoint());
//					
//                    //add by tery on 2010-1-14
//                    $customerId = null;
//                    if(! Class_Customer::isRegistered()) {
//						$cellphone = Class_Order::getAddressData('mobile');
//						$customer = Class_Core::_('Customer')->setData(array('cellphone' => $cellphone, 'registered' => 0))
//							->load();
//							
//						if(!is_null($customer->getData('entityId'))) {
//							$customerId = $customer->getData('entityId');
//						} else {
//						    $customer->setData(array(
//						    	'cellphone' => $cellphone,
//						        'registered' => '0'
//						    ))->save();
//						    $customerId = $customer->getData('entityId');
//						}
//						Class_Customer::updateSession($customer);
//					} else {
//						$customerId = Class_Customer::getData('entityId');
//						if(is_null(Class_Order::getAddressData('addressId'))) {
//    					    $address = Class_Core::_('Address')->create();
//						} else {
//						    $address = Class_Core::_('Address')->setData(array(
//						    	'customerId' => $customerId,
//						        'addressId' => Class_Order::getAddressData('addressId')
//						        ))
//						    ->load();
//						}
//						
//						$address->setData('customerId', $customerId)
//							->setData('consignee',Class_Order::getAddressData('consignee'))
//							->setData('mobile',Class_Order::getAddressData('mobile'))
//							->setData('phone',Class_Order::getAddressData('phone'))
//							->setData('postcode',Class_Order::getAddressData('postcode'))
//							->setData('provinceUnitId',Class_Order::getAddressData('provinceUnitId'))
//							->setData('cityUnitId',Class_Order::getAddressData('cityUnitId'))
//							->setData('addressDetail',Class_Order::getAddressData('addressDetail'))
//							->save();
//						
//						$addressId = $address->getData('addressId');
//						$client = new Zend_Session_Namespace('client');
//						if(isset($client->orderAddress)){
//							$client->orderAddress['addressId'] = $addressId;
//						}
//					}
//					// end add 
////					Class_Order::setData('customerId', $customerId);
////                    $orderId = Class_Order::saveOrder($this->getRequest()->getParams());
//                    $cart = Class_Cart::getCart('general');
//					$order = Class_Core::_('Order')->setData($this->getRequest()->getPost())
//            	        ->setData('customerId', $customerId)
//            	        ->setCart($cart)
////            	        ->setItem()
//            	        ->setAddress($address)
//            	        ->save();
////                    Zend_Debug::dump($order);
////                    
////                    Zend_Debug::dump($this->getRequest()->getPost());
////                    
////                    Zend_Debug::dump($this->getRequest()->getPost());
////                    
////                    die();
//					
//					if(Class_Customer::getData('entityId') != 0){
////						Class_Customer::updatePoint(Class_Cart::getTotalPoint());	
//					}
//					
//					$client = new Zend_Session_Namespace('client');
//					$customerRefId = $client->customerRefId;
//					if(!is_null($customerRefId)){
//						$cps = Class_Core::_('cps')->create()
//							->setData('orderId',$orderId)
//							->setData('customerRefId',$customerRefId)
//							->save();
//					}
//                } catch(Exception $e) {
//                    throw $e;
//                }
//                
//                $cart->clear();
//				
//                if(Class_Order::isPaymentUponArrival()) {
////                    Class_Push::sms(Class_Order::getData('addressMobile'), '您的订单'.$orderId.'已保存。我们会在送货时收取货款。');
//                    $this->_helper->redirector->gotoSimple(
//                    	'index',
//                    	'payment-gateway',
//                    	'default',
//                        array('orderId' => $orderId)
//                    );
//                } else {
////                    Class_Push::sms(Class_Order::getData('addressMobile'), '您的订单'.$orderId.'已保存。在线付款遇到困难请联系客服。');
//                    $this->_helper->redirector->gotoSimple(
//                    	'index',
//                    	'payment-gateway',
//                    	'default',
//                        array('orderId' => $orderId)
//                    );
//                }
//            }
//        }
        
        
//        if(!Class_Order::isAddressSet()) {
//            require (APP_PATH . '/default/forms/Order/Address.php');
//            $addressForm = new Form_Order_Address();
//            if(Class_Customer::isRegistered()) {
//    			$addressList = Class_Core::_list('Address')->loadAllForUser(Class_Customer::getData('entityId'))
//                    ->getListData();
//                $addressArr = array();
//                foreach($addressList as $add) {
//                    $addressArr[$add->getData('addressId')] = "<span>".$add->getData('consignee').", ".$add->getData('provinceName').$add->getData('cityName').$add->getData('addressDetail')." ".$add->getData('postcode')."<br /><span class='extra'>联系手机：".$add->getData('mobile').'</span></span>';
//                }
//                $addressArr[0] = "<span>使用新的配送地址<span class='extra'></span></span>";
//                $addressForm->addressId->setMultiOptions($addressArr);
//                $addressForm->addressId->setValue(0);
//    		} else {
//                $addressArr[0] = "<span>使用新的配送地址<span class='extra'></span></span>";
//                $addressForm->addressId->setMultiOptions($addressArr);
//                $addressForm->addressId->setValue(0);
//    		}
//            $this->view->addressForm = $addressForm;
//            $this->view->paymentText = "请先填写并保存您的收货地址!";
//        } else {
//            if(!Class_Order::isPaymentSet()) {
//                require (APP_PATH.'/default/forms/Order/Payment.php');
//                $paymentForm = new Form_Order_Payment();
//    			$paymentData = array(
//    				array('member_name'=>2,'member_value'=>'在线支付-支付宝')
//    			);
//                $paymentDesc = array(
//                    2 => ' 新店开张暂不支持货到付款, 不便之处请多多包涵!'
//                );
//                foreach($paymentData as $k => $v)
//                {
//                     $paymentDataArray[$v['member_name']] = " &nbsp;<img style='width: 90px; height: 70px; vertical-align: middle;' src='/images/payment/".$v['member_name'].".jpg' /><span class='member_value'>".htmlspecialchars($v['member_value'])."</span><span class='desc'>".$paymentDesc[$v['member_name']].'</span>';   
//                }
//                $paymentForm->paymentMethod->setMultiOptions($paymentDataArray);
//                if(!Class_Order::getAddressData('paymentUponArrival')) {
//                    $paymentForm->paymentMethod->setAttrib('disable', array(1));
//                    if(is_null(Class_Order::getData('paymentMethod'))) {
//                        $paymentForm->paymentMethod->setValue(2);
//                    }
//                }
//                $this->view->paymentForm = $paymentForm;
//            } else {
//                $this->view->paymentText = " 付款方式：<br />在线支付 - 支付宝<br />根据您选择的地区和付款方式，您当前的运费为：￥ ".Class_Order::getShippingPrice();
//            }
//        }
        
//        $productData = array();
//        $productIdList = Class_Cart::getProductList();
//        $productData = Class_Core::_list('Product')->addFieldToFilter('entityId', array('in' => array_keys($productIdList)))
//            ->load()
//            ->getListData();
//        Class_Cart::setRenderType('order', array('name' => '', 'qty' => '', 'price' => '', 'subtotal' => ''));
        $this->view->cart = $cart;
//        $this->view->invoiceForm = $invoiceForm;
//        $this->view->messageForm = $messageForm;
    }
    
    public function setAddressJsonAction()
    {
    	$addressId = $this->getRequest()->getParam('address-id');
    	
    	$csu = Class_Session_User::getInstance();
    	$addressDoc = App_Factory::_am('Address')->find($addressId);
    	if(!is_null($addressDoc) && $addressDoc->userId != $csu->getUserId()) {
    		throw new Exception('addressId not found');
    	}
    	$cart = App_Cart::factory('general');
    	$cart->setAddress($addressId, $addressDoc->fullAddress)->save();
    	
    	$this->_helper->json(array('fullAddress' => $addressDoc->fullAddress));
    }
    
//    public function validateAddressJsonAction()
//    {
//        require (APP_PATH.'/default/forms/Order/Address.php');
//        $addressId = $this->getRequest()->getParam('addressId');
//        $result = 'false';
//        
//        $addressData = null;
//        if(!empty($addressId)) {
//            $address = Class_Core::_('Address')
//                ->setData('addressId', $addressId)
//                ->setData('customerId', Class_Customer::getData('entityId'))
//                ->load();
//            if(!is_null($address->getData('addressId'))) {
//                $address->loadAreaName();
//                $result = 'true';
//            } else {
//                throw new Exception('internal error, address not exist!');
//            }
//        } else {
//            $form = new Form_Order_Address();
//            $form->addressId->setRegisterInArrayValidator(false);
//            $addressData = $form->processAjax($this->getRequest()->getParams());
//            
//            if($addressData === "true") {
//                $formValues = $form->getValues();
//                $formValues['addressId'] = "";
//                $address = Class_Core::_('Address')
//					->setData($formValues)
//                    ->setData('customerId', Class_Customer::getData('entityId'));
//                $address->loadAreaName();
//                $result = 'true';
//            }
//        }
//        
//        if($result == 'true') {
//            Class_Order::setAddressData($address);
//            $addressData = array(
//                'addressId' => Class_Order::getAddressData('addressId'),
//            	'consignee' => Class_Order::getAddressData('consignee'),
//                'fullAddress' => Class_Order::getAddressData('fullAddress'),
//                'postcode' => Class_Order::getAddressData('postcode'),
//                'mobile' => Class_Order::getAddressData('mobile'),
//                'phone' => Class_Order::getAddressData('phone'),
//            );
//        }
//        
//        $this->_helper->json(array('result' => $result, 'data' => $addressData));
//    }
    
//    public function getFormAction()
//    {
//        $type = $this->getRequest()->getParam('type');
//        switch($type) {
//            case 'address-selector':
//            	$csu = Class_Session_User::getInstance();
//            	$addressList = App_Factory::_am('Address')->addFilter('userId', $csu->getUserId())->fetchAll();
//            	
//            	$this->view->addressList = $addressList;
//            	$this->render('address-selector');
//            	
//            	
//            	
////                require (APP_PATH.'/default/forms/Order/Address.php');
////                $form = new Form_Order_Address();
////                if(Class_Customer::isRegistered()) {
////                    $addressList = Class_Core::_list('Address')->loadAllForUser(Class_Customer::getData('entityId'))
////                        ->getListData();
////                    $addressArr = array();
////                    foreach($addressList as $add) {
////                        $addressArr[$add->getData('addressId')] = "<span style='float: left; display: block;'>".$add->getData('consignee')." ".$add->getData('provinceName').$add->getData('cityName').$add->getData('addressDetail')." ".$add->getData('postcode')."<span class='extra'>  ".$add->getData('mobile').'</span></span>';
////                    }
////                    $addressArr[0] = "<span style='float: left; display: block;'>使用其它地址</span>";
////                    $form->addressId->setMultiOptions($addressArr);
////                    $form->addressId->setValue(Class_Order::getData('addressId'));
////        		} else {
////                    $addressArr[0] = "<span style='float: left; display: block;'>使用新的配送地址<span class='extra'></span></span>";
////                    $form->addressId->setMultiOptions($addressArr);
////                    $form->addressId->setValue(0);
////        		}
////        		
////        		$provinceId = Class_Order::getAddressData('provinceUnitId');
////        		$citiesArr = array();
////                $cityCollectionData = Class_Core::_('Address')->getCityCollectionData($provinceId);
////                $citiesArr = $cityCollectionData;
////                $form->cityUnitId->setMultiOptions($citiesArr);
////                
////        		$form->setDefaults(Class_Order::getAddressData());
////                $this->view->form = $form;
//                break;
//            case 'payment':
//                require (APP_PATH.'/default/forms/Order/Payment.php');
//                $form = new Form_Order_Payment();
//                
//                $paymentData = array(
//                	//array('member_name'=>1,'member_value'=>'货到付款'),
//                	array('member_name'=>2,'member_value'=>'在线支付-支付宝')
//                	//array('member_name'=>3,'member_value'=>'在线支付-快钱')
//                	);
//                $paymentDesc = array(
//	                //1 => ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当面付款后验货 支持现金支付 <a href="http://www.pmatch.cn/help/delivery-prepay" target="_blank">查看运费及配送范围</a>',
//	                2 => ' 新店开张暂不支持货到付款, 不便之处请多多包涵!'
//	                //3 => ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;使用快钱支付或使用银行卡、信用卡（通过快钱）在线直接支付'
//	                );
//                foreach($paymentData as $k => $v)
//                {
//                     $paymentDataArray[$v['member_name']] = " &nbsp;<img style='width: 90px; height: 70px; vertical-align: middle;' src='/images/payment/".$v['member_name'].".jpg' />".htmlspecialchars($v['member_value'])."<span class='desc'>".$paymentDesc[$v['member_name']].'</span>';   
//                }
//                $form->paymentMethod->setMultiOptions($paymentDataArray);
//                
//                if(!Class_Order::getAddressData('paymentUponArrival')) {
//                    Class_Order::setData('paymentMethod', 0);
//                    $form->paymentMethod->setAttrib('disable', array(1));
//                }
//                $this->view->form = $form;
//                break;
//        }
//    }
    
//    public function getPaymentAction()
//    {
//        $config = new Zend_Config_Ini(APP_PATH.'/configs/paymentSocket.ini');
//        $paymentMethods = $config->paymentMethods->toArray();
//        
//        $paymentId = $this->getRequest()->getParam('paymentId');
//       
//        if(array_key_exists($paymentId, $paymentMethods)) {
//            Class_Order::setPaymentData($paymentId);
//        } else {
//            throw new Exception('internal error, payment method not exist!');
//        }
//        
//        
//        $this->view->paymentMethod = $paymentMethods[$paymentId];
//        $this->view->shippingPrice = Class_Order::getShippingPrice();
//    }
//    
//    public function getCheckoutJsonAction()
//    {
//        $shippingPrice = Class_Order::getShippingPrice();
//		$cart = Class_Cart::getCart('general');
//        $subtotalPrice = $cart->getSubtotal();
//        $this->_helper->json(array('shippingPrice' => $shippingPrice, 'subtotalPrice' => $subtotalPrice));
//    }
    
    public function processAction()
    {
    	$cart = App_Cart::factory('general');
    	if($cart->isValid()) {
    		$order = App_Factory::_am('Order')->create();
    		$order->setFromCart($cart)
    			->save();
    		$orderId = $order->getId();
    		$cart->remove();
    		$this->_helper->redirector->gotoSimple('index', 'gateway', 'payment', array('order-id' => $orderId));
    	} else {
    		$this->_helper->redirector->gotoSimple('index', 'index', 'shop');
    	}
    }
    
    public function processedOrderAction()
    {
        Class_Cart::clear();
        $client = new Zend_Session_Namespace('client');
        if(isset($client->processedOrderId)) {
            die("all the conten of the order will be shown here the saved order id is: " . $client->processedOrderId . "
            <br />
            you can access them from your account in my orders tab!");
        } else {
            $this->_redirector->gotoSimple('index', 'index', 'default');
        }
    }
}