<?php
class Form_CategoryEdit extends Class_Form_Edit
{
    public function init()
    {
    	$tb = new Zend_Db_Table('category_section');
    	$rowset = $tb->fetchAll($tb->select(false)->from($tb, array('id', 'name')));
    	$optArr = array();
    	foreach($rowset as $row) {
    		$optArr[$row->id] = $row->name;
    	}
        $this->addElement('select', 'sectionId', array(
        	'label' => '目录分类',
        	'multiOptions' => $optArr,
        	'required' => true,
        ));
        
        $this->addElement('text', 'label', array(
        	'label' => '目录名',
        	'required' => true,
        	'filters' => array('StringTrim')
        ));
//        $this->addElement('text', 'title', array(
//        	'label' => '目录TITLE',
//        	'required' => true,
//        	'filters' => array('StringTrim')
//        ));
        
        $this->addElement('text', 'link', array(
            'label' => '链接地址',
        	'required' => true,
            'filters' => array('StringTrim')
        ));
        
//        $this->_main = array('linkType', 'label', 'title', 'active');
		$this->_main = array('sectionId', 'label', 'link');
//        $this->_dependent = array('link');
    }
}