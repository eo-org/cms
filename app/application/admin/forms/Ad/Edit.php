<?php
class Form_Ad_Edit extends Class_Form_Edit
{
    public function init()
    {
    	$table = new Class_Model_Group_Tb();
        $rowset = $table->fetchAll($table->select()->where('type = ?', 'ad'));
        $rowsetArr = Class_Func::buildArr($rowset, 'id', 'label');
    	$this->addElement('select', 'groupId', array(
            'label' => '广告分类',
            'multiOptions' => $rowsetArr,
    		'required' => true
        ));
        $this->addElement('text', 'name', array(
            'filters' => array('StringTrim'),
            'label' => '广告名（系统）：',
            'required' => true
        ));
        $this->addElement('textarea', 'description', array(
            'filters' => array('StringTrim'),
            'label' => '广告内容：'
        ));
        
        $this->addElement('text', 'url', array(
            'filters' => array('StringTrim'),
            'label' => '广告链接：',
        	'class' => 'link-selector',
            'required' => true
        ));
        $this->addElement('text', 'image', array(
            'filters' => array('StringTrim'),
            'label' => '广告图片：',
        	'class' => 'icon-selector',
            'required' => false
        ));
        
        $this->_main = array('groupId', 'name', 'description');
        $this->_required = array('url', 'image');
    }
}