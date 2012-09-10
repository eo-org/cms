<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initAutoloader()
	{
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('App_');
		$autoloader->registerNamespace('Class_');
		$autoloader->registerNamespace('Twig_');
	}
	
	protected function _initSession()
	{
		Zend_Session::start();
	}
	
	protected function _initMongoDb()
	{
		Class_Server::setLibVersion('v2');
		
		$siteId = Class_Server::getSiteId();
		$mongoAdapter = new App_Mongo_Db_Adapter('cms_'.$siteId, Class_Server::getMongoServer());
		App_Mongo_Db_Collection::setDefaultAdapter($mongoAdapter);
		
		$mongo = $mongoAdapter->getMongo();
		Zend_Registry::set('mongo', $mongo);
		
		
		Zend_Registry::set('mongoAdapter', $mongoAdapter);
		
	}
	
    protected function _initController()
    {
    	Zend_Controller_Action_HelperBroker::addPath(APP_PATH.'/helpers', 'Helper');
    	
        $controller = $this->getPluginResource('frontController')->getFrontController();
        
        $controller->setControllerDirectory(array(
            'default' => APP_PATH.'/default/controllers',
        	'user' => APP_PATH.'/user/controllers',
        	'shop' => APP_PATH.'/shop/controllers',
            'admin' => APP_PATH.'/admin/controllers',
        	'rest' => APP_PATH.'/rest/controllers'
		));
        $controller->throwExceptions(true);
                
        Zend_Layout::startMvc();
        $layout = Zend_Layout::getMvcInstance();
        $layout->getView()->addScriptPath(APP_PATH."/layout-scripts");
        $layout->setLayout('template');
        
        $csa = Class_Session_Admin::getInstance();
        
        $controller->registerPlugin(new App_Plugin_BackendSsoAuth(
        	$csa,
        	App_Plugin_BackendSsoAuth::CMS,
        	Class_Server::API_KEY
        ));
        $controller->registerPlugin(new Class_Plugin_HeadFile());
        $controller->registerPlugin(new Class_Plugin_LayoutSwitch($layout));
        $controller->registerPlugin(new Class_Plugin_BrickRegister($layout));
        
		$view = new Zend_View();
		$view->headTitle()->setSeparator('_');
		
		Zend_Registry::set('Locale', 'zh_CN');
		$co = App_Factory::_m('Info');
		$doc = $co->fetchOne();
		if(!is_null($doc)) {
			$view->headTitle($doc->pageTitle);
			$view->headMeta()->appendName('keywords', $doc->metakey);
			$view->headMeta()->appendName('description', $doc->metadesc);
			if(!is_null($doc->language)) {
				Zend_Registry::set('Locale', $doc->language);
			}
		}
    }
    
    protected function _initRouter()
    {
        $controller = $this->getPluginResource('frontController')->getFrontController();
        $router = $controller->getRouter();
        $router->addRoute('article', new Zend_Controller_Router_Route_Regex(
            'article-(\w+)\.shtml',
            array('controller' => 'article'),
            array(
                1 => 'action'
            ),
            'article-%1$s.shtml'
        ));
        $router->addRoute('list', new Zend_Controller_Router_Route_Regex(
            'list-(\w+)/page(\d+)\.shtml',
            array(
                'controller' => 'list',
            	'page' => 1),
            array(
                1 => 'action',
                2 => 'page'
            ),
            'list-%1$s/page%2$s.shtml'
        ));
        $router->addRoute('product', new Zend_Controller_Router_Route_Regex(
            'product-(\w+)\.shtml',
            array('controller' => 'product'),
            array(
                1 => 'action'
            ),
            'product-%1$s.shtml'
        ));
        $router->addRoute('product-list', new Zend_Controller_Router_Route_Regex(
            'product-list-(\w+)/page(\d+)\.shtml',
            array(
                'controller' => 'product-list',
            	'page' => 1),
            array(
                1 => 'action',
                2 => 'page'
            ),
            'product-list-%1$s/page%2$s.shtml'
        ));
        $router->addRoute('product-list-static', new Zend_Controller_Router_Route_Static(
            'product-list.shtml',
            array(
                'controller' => 'product-list',
            	'action' => null,
            	'page' => 1
            )
        ));
        
        $router->addRoute('rest', new Zend_Rest_Route($controller, array(), array('rest')));
        
        unset($router);
    }
}