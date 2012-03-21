<?php
class Front_Logo extends Class_Brick_Solid_Abstract
{
	public function prepare()
    {
//    	$siteInfo = Zend_Registry::get('siteInfo');
//    	if($siteInfo['type'] == 'multiple') {
//    		$id = $siteInfo['subdomain']['id'];
//    		$db = Zend_Registry::get('db');
//    		$subdomainArr = $db->fetchRow('select logoPath from subdomain where id = ?', $id);
//    		
//    		$logoPath = $subdomainArr['logoPath'];
//    		$this->view->logoPath = $logoPath;
//    	} else {
//    		$db = Zend_Registry::get('db');
//    		$subdomainArr = $db->fetchRow('select logoPath from site_general where id = 0');
//    		
//    		$logoPath = $subdomainArr['logoPath'];
    		$this->view->logoPath = $this->getParam('logoPath');
//    	}
    }
    
	public function configParam(Class_Form_Edit $form)
    {
    	$form->addElement('text', 'param_logoPath', array(
            'filters' => array('StringTrim'),
    		'class' => 'icon-selector',
            'label' => 'logo图片：',
            'required' => true
        ));
        $paramArr = array('param_logoPath');
    	$form->setParam($paramArr);
        return $form;
    }
}