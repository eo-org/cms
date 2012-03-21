<?php
class Front_SpriteSurrogate extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$cbc = Class_Brick_Controller::getInstance();
    	$surrogateId = 'surrogate-'.$this->_brick->brickId;
    	$tabs = $cbc->getBrickList($surrogateId);
    	
    	$this->view->tabs = $tabs;
		$this->view->stageId = $this->_brick->stageId;
    }
    
    public function configParam(Class_Form_Edit $form)
    {
		return $form;
    }
}