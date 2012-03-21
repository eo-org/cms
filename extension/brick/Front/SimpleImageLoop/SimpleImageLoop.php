<?php
class Front_SimpleImageLoop extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$sectionId = $this->getParam('sectionId');
    	if(!empty($sectionId)) {
	    	$tb = Class_Base::_('Ad');
	    	$rowset = $tb->fetchAll($tb->select()->where('sectionId = ?', $sectionId));
    	} else {
    		$rowset = array();
    	}
    	$this->view->rowset = $rowset;
    }
    
    public function configParam(Class_Form_Edit $form)
    {
//    	$paramArr = array('param_adArr', 'param_slidingType', 'param_interval', 'param_width', 'param_height');
//    	$form->setParam($paramArr);
    	return $form;
    }
}