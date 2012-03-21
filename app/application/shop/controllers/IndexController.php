<?php
class Shop_IndexController extends Zend_Controller_Action
{
	public function init()
	{
		$this->view->headTitle("我的购物袋");
	}

	public function indexAction()
    {
		
    }

    public function addAction()
    {
        $this->getHelper('layout')->disableLayout();
        $productId = $this->getRequest()->getParam('productId');
        $pcount = $this->getRequest()->getParam('pcount');
        if(!$pcount){
            $pcount = 1 ;
        }

        if(intval($productId) == 0) {
            throw new Exception('product id could not be found!');
        }
        
        if(Class_Cart::addProduct($productId, $pcount)) {
            $this->_redirector->gotoSimple('index');
        } else {
            Class_Cart::addErrorMsg('您选择的商品库存暂时不足,请缩小商品数量或者选择其他商品!');
            $this->_redirector->gotoSimple('index');
        }
    }
    
    public function addJsonAction()
    {
    	$productId = $this->getRequest()->getParam('product-id');
		
    	$productDoc = App_Factory::_m('Product')->find($productId);
    	if(is_null($productDoc)) {
    		$this->_helper->json(array(
	        	'result' => 'fail',
	            'errMsg' => 'product id '.$productId.' not found in db'
	    	));
    	}
    	
    	$generalCart = App_Cart::factory('general');
		$generalCart->addItem($productDoc->getId(), 1, $productDoc->price, array(
			'name' => $productDoc->name,
			'label' => $productDoc->label,
			'introicon' => $productDoc->introIcon
		));
		$generalCart->save();
		
    	$this->_helper->json(array(
        	'result' => 'success',
            'name' => $productDoc->name,
			'label' => $productDoc->label,
        	'price' => $productDoc->price)
    	);
    }

    public function adjustJsonAction()
    {
        $jsonArr = array();
        $productId = $this->getRequest()->getParam('productId');
        $qty = intval($this->getRequest()->getParam('qty'));
		$qty = $qty == 0 ? 1 : $qty;
		$cart = Class_Cart::getCart('general');
		$item = $cart->getItemFromKey($productId);
		if(!is_null($item)){
			$item->setQty($qty);
            $jsonArr['productTotal'] = number_format($cart->getSubtotal(), 2);
            $jsonArr['itemTotal'] = $item->getSubtotal();
            $jsonArr['itemCount'] = $item->getQty();
            $jsonArr['result'] = true;
            return $this->_helper->json($jsonArr);
        } else {
            $jsonArr['result'] = false;
            return $this->_helper->json($jsonArr);
        }
    }
    
    public function deleteJsonAction()
    {
        $pid = $this->getRequest()->getParam('product-id');
        $cart = App_Cart::factory('general');
        $cart->removeItem($pid);
        $cart->save();
        $this->_helper->json(array('result' => 'success', 'subtotal' => $cart->getSubtotal()));
    }
    
	public function clearAction()
    {
        $cart = Class_Cart::getCart('general');
        $cart->clear();
        $this->_redirector->gotoSimple(
            'index',
            'cart'
        );
    }
}