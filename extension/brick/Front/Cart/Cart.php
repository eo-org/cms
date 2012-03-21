<?php
class Front_Cart extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$cart = App_Cart::factory('general');
    	
    	$itemList = $cart->getItemList();
    	$this->view->itemList = $itemList;
    	$this->view->subtotal = $cart->getSubtotal();
    	$this->view->shipping = 10;
    	$this->view->total = 10000;
    }
}