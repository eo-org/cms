<?php
class BrickProductGroupRender extends Class_Link_Renderer_Abstract
{
	private $_groupId = null;
	public function setGroupId($val)
	{
		$this->_groupId = $val;
	}
	
	public function run($link)
    {
        echo "<ul>"."\n";
        foreach($link->getChildren() as $cLink) {
            $id = $cLink->getId();
            $pid = $cLink->getParentId();
            $classString = "";
            if($id == $this->_groupId) {
            	$classString = " class='selected'";
            }
            echo "<li$classString>"."<a$classString href='".$cLink->getHref()."'>".$cLink->label.'</a>'."\n";
            
            if($cLink->hasChildren()) {
                echo $cLink->render();
            }
            echo "</li>"."\n";
        }
        echo "</ul>"."\n";
    }
}

class BrickProductGroupRenderIcon extends Class_Link_Renderer_Abstract
{
	private $_groupId = null;
	public function setGroupId($val)
	{
		$this->_groupId = $val;
	}
	
	public function run($link)
    {
        echo "<ul>"."\n";
        foreach($link->getChildren() as $cLink) {
            $id = $cLink->getId();
            $pid = $cLink->getParentId();
            $classString = "";
            if($id == $this->_groupId) {
            	$classString = " class='selected'";
            }
            echo "<li$classString>";
            echo "<a class='img' href='".$cLink->getHref()."'><img src='".Class_HTML::outputImage($cLink->introicon)."' /></a>"."\n";
            echo "<a$classString href='".$cLink->getHref()."'>".$cLink->label.'</a>'."\n";
            
            if($cLink->hasChildren()) {
                echo $cLink->render();
            }
            echo "</li>"."\n";
        }
        echo "</ul>"."\n";
    }
}

class Front_ProductGroups extends Class_Brick_Solid_Abstract
{
	public function prepare()
	{
		$groupId = $this->_request->getParam('action');
		
		$linkController = Class_Link_Controller::factory('product');
		
		if($this->getParam('displayIntroicon') == 1) {
			$renderer = new BrickProductGroupRenderIcon();
		} else {
			$renderer = new BrickProductGroupRender();
		}
		$renderer->setGroupId($groupId);
		Class_Link_Controller::setRenderer($renderer);
		
		$groupIdParam = $this->getParam('groupId');
		if(empty($groupIdParam) || $groupIdParam == 0) {
			$head = $linkController->getLinkHead();
		} else {
			$head = $linkController->getLink($groupIdParam);
		}
		
		$this->view->title = $this->_brick->brickName;
		$this->view->head = $head;
	}
	
	public function configParam(Class_Form_Edit $form)
    {
    	$displayIntroicon = new Zend_Form_Element_Select('param_displayIntroicon', array(
    		'filters' => array('StringTrim'),
    		'label' => '选择产品分类',
    		'multiOptions' => array(0 => '不显示', 1 => '显示'),
    		'required' => true
    	));
    	
    	$linkController = Class_Link_Controller::factory('product');
    	$multiOptions = $linkController->toMultiOptions();
    	$multiOptions['Show All'] = array(0 => '全部分类');
    	$groupId = new Zend_Form_Element_Select('param_groupId', array(
    		'filters' => array('StringTrim'),
    		'label' => '选择产品分类',
    		'multiOptions' => $multiOptions,
    		'required' => true
    	));
    	
    	$form->addElement($displayIntroicon);
        $form->addElement($groupId);
    	
        $paramArr = array('param_displayIntroicon', 'param_groupId');
        $form->setParam($paramArr);
        return $form;
    }
}