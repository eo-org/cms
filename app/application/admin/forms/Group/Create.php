<?php
class Form_Group_Edit extends Class_Form_Edit
{
    public function init()
    {
        $this->addElement('text', 'label', array(
            'filters' => array('StringTrim'),
            'label' => '分类名：',
            'required' => true
        ));
        
//        $sort = new Zend_Form_Element_Select('sort', array('label' => '排序:',
//        	'multiOptions' => array(),
//        	'required' => true
//		));
//        $sort->setRegisterInArrayValidator(false);
//        $this->addElement($sort);
//        
//        $this->addElement('text', 'introCssSuffix', array(
//            'filters' => array('StringTrim'),
//            'label' => '简表CSS后缀：',
//            'required' => false
//        ));
//        $this->addElement('text', 'introImagePath', array(
//            'filters' => array('StringTrim'),
//            'label' => '简表图片路径：',
//            'required' => false
//        ));
//        $this->addElement('text', 'imagePath', array(
//            'filters' => array('StringTrim'),
//            'label' => '列表图片路径：',
//            'required' => false
//        ));
//        $this->addElement('select', 'attrib_display', array(
//            'filters' => array('StringTrim'),
//            'label' => '显示样式：',
//        	'multiOptions' => array('' => '默认', 'list' => '列表', 'blog' => 'BLOG'),
//            'required' => false
//        ));
//        
//        $this->_main = array('label', 'parentId', 'sort');
//        $this->_optional = array('introCssSuffix', 'introImagePath', 'imagePath');
//        $this->_param = array('attrib_display');
    }
}