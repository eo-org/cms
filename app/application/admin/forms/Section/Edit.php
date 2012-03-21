<?php
class Form_Edit extends Class_Form_Edit
{
    public function init()
    {
        $this->addElement('text', 'name', array(
            'filters' => array('StringTrim'),
            'label' => '目录组名：',
            'required' => true
        ));
        
        $this->addElement('text', 'title', array(
            'filters' => array('StringTrim'),
            'label' => '目录组简介：',
            'required' => true
        ));
        
//        $tb = Class_Base::_('Subdomain');
//        $rowset = $tb->fetchAll();
//        $rowArr = Class_Func::buildArr($rowset, 'id', 'label');
//        $rowArr[0] = '全部站点';
//        $this->addElement('select', 'subdomainId', array(
//            'filters' => array('StringTrim'),
//            'label' => '分配给站点：',
//        	'multiOptions' => $rowArr,
//            'required' => true
//        ));
        
        $this->_main = array('name', 'title');
//        $this->_required = array('subdomainId');
    }
}