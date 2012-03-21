<?php
class Form_Brick_Create extends Class_Form_Edit
{
    public function init()
    {
    	$this->setAction('/admin/brick/edit');
    	$this->setMethod('GET');
    	$siteDb = Zend_Registry::get('siteDb');
    	$tb = new Zend_Db_Table(array(
    		Zend_Db_Table_Abstract::ADAPTER => $siteDb,
    		Zend_Db_Table_Abstract::NAME => 'extension'
    	));
    	$rowset = $tb->fetchAll($tb->select()->order('sort'));
    	$rowsetArr = Class_Func::buildArr($rowset, 'name', 'label');
    	
    	$this->addElement('select', 'extName', array(
    		'label' => '选择要创建的模块类型：',
    		'multiOptions' => $rowsetArr,
        	'required' => true
    	));
        
    	$this->addElement('hidden', 'poster', array(
    		'value' => 'create'
    	));
    	
        $this->_main = array('extName');
    }
}