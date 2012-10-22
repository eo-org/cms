<?php
class Form_Order extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('select', 'status', array(
          	'label' => "订单状态",
          	'multiOptions' => array('new' => '新订单', 'processed' => '已确认订单', 'sent' => '已发送订单', 'complete' => '已完成订单')
        ));
        
        $this->addElement('select', 'paid', array(
          	'label' => "订单已付款",
            'multiOptions' => array(0 => '未付款', 1 => '已付款')
        ));
    }
}