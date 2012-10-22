<?php
class Form_Order_Payment extends Zend_Form
{
    public function init()
    {
        $config = new Zend_Config_Ini(APP_PATH.'/configs/paymentSocket.ini');
        
        $paymentMethods = $config->paymentMethods->toArray();
        $paymentId = Class_Order::getData('paymentMethod');
        if(is_null($paymentId)) {
            $paymentId = 1;
        }
        
        $this->addElement('radio', 'paymentMethod', array(
            'label' => '',
            'multiOptions' => $paymentMethods,
            'value' => $paymentId,
            'separator'=>'<br />',
        	'escape' => false,
            'required' => true
        ));
        
        $this->setDecorators(array(
            'FormElements'
        ));
    }
}