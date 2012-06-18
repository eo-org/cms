<?php
class Front_BookNavi extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
//    	$co = App_Factory::_m('Navi');
//    	$doc = $co->fetchOne();
//    	
//    	$this->view->naviDoc = $doc;
		
    	$clf = Class_Layout_Front::getInstance();
    	
    	$bookDoc = $clf->getResource();
    	$bookPageDoc = App_Factory::_m('Book_Page')->setFields(array('label', 'link'))
			->addFilter('bookId', $bookDoc->getId())
			->fetchArr();
		
    	$pageIds = $bookDoc->pageIds;
    	$pageLinks = array();
    	foreach($pageIds as $pId){
    		$pageLinks[] = array('id' => $pId,'label' => $bookPageDoc[$pId]['label'], 'link' => $bookPageDoc[$pId]['link']);
    	}
    	
    	$this->view->bookName = $bookDoc->name;
    	$this->view->pageLinks = $pageLinks;
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
//    	$paramArr = array('param_sectionId', 'param_display');
//    	$form->setParam($paramArr);
    	return $form;
    }
}
