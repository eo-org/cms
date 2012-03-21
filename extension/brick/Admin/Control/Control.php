<?php
class Admin_Control extends Class_Brick_Solid_Abstract
{
    protected $_removeControls = array();
    
    public function removeControls(Array $removeList)
    {
        $this->_removeControls = $removeList;
        return $this;
    }
    
    public function prepare()
    {
		$this->_config = new Zend_Config_Ini(
        	APP_PATH.'/configs/resource/page/admin.ini',
			null,
			array('skipExtends' => true, 'allowModifications' => true)
		);
        
        $controllerName = $this->_request->getControllerName();
        /*
         * getActionName gets the redirected action name, eg:edit(create -> edit)
         * getParam gets the original action name
         */ 
        $actionName = $this->_request->getParam('action');
        $hashParam = urlencode($this->_request->getParam('hashParam'));
        $controls = array();
        $title = null;
        
        if(isset($this->_config->{$controllerName})) {
            $title = $this->_config->{$controllerName}->title;
            if(!is_null($this->_config->{$controllerName}->control->$actionName)) {
                $tempStr = $this->_config->{$controllerName}->control->$actionName;
            } else {
                $tempStr = $this->_config->{$controllerName}->control->default;
            }
            
            if(!is_null($tempStr)) {
                $controls = explode(',', $tempStr);
            }
        }
        if(count($controls) == 0) {
            $this->setRender(false);
        } else {
            foreach($controls as $key => $con) {
                if(in_array($con, $this->_removeControls)) {
                    unset($controls[$key]);
                }
            }
        }
        
        $this->view->hashParam = $hashParam;
        $this->view->controllerName = $controllerName;
        $this->view->actionName = $actionName;
        $this->view->controls = $controls;
        $this->view->title = $title;
    }
}