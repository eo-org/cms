<?php
class Form_User_Edit extends Zend_Form
{
    public function init()
    {
    	$this->addElement('select', 'status', array(
            'label' => '用户状态：',
            'multiOptions' => array('inactive' => '冻结', 'active' => '激活')
        ));
    }
}