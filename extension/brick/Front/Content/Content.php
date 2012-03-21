<?php
class Front_Content extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$layout = Zend_Layout::getMvcInstance();
    	$content = $layout->content;
		$this->view->content = $content;
    }
}