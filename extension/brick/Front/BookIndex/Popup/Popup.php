<?php
class Front_BookIndex_Popup extends Class_Brick_Solid_Abstract
{
	protected $_effectFiles = array(
    	'navi/popup/plugin.js',
		'navi/popup/plugin.css'
    );
	
    public function prepare()
    {
    	$clf = Class_Layout_Front::getInstance();
    	
    	$type = $clf->getType();
    	
    	if($type != 'book') {
    		throw new Exception('this extension is only suitable for a book typed layout!');
    	}
    	
    	$bookDoc = $clf->getResource();
    	
    	$this->view->bookAlias = $bookDoc->alias;
    	$this->view->bookIndex = $bookDoc->bookIndex;
    }
    
    public function configParam($form)
    {
//		$co = App_Factory::_m('Navi');
//    	$docArr = $co->setFields('label')->fetchArr('label');
//		
//    	$form->addElement('select', 'param_sectionId', array(
//            'label' => '选择目录组：',
//    		'multiOptions' => $docArr,
//            'required' => true
//        ));
//    	
//        $form->addElement('select', 'param_display', array(
//            'label' => '显示方式：',
//    		'multiOptions' => array('text' => '文字', 'bg' => '背景图'),
//            'required' => true
//        ));
//
    	return $form;
    }
}
