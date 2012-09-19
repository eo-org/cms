<?php
class Admin_ProductController extends Zend_Controller_Action
{
	public function init()
	{
		$this->view->headLink()->appendStylesheet(Class_Server::libUrl().'/admin/style/product.css');
	}
	
    public function indexAction()
    {    	
        $labels = array(
        	'name' => '产品编号',
        	'label' => '产品名',
        	'price' => '价格',
        	'groupId' => '产品分类',
        	'~contextMenu' => ''
        );
        
        $groupDoc = App_Factory::_m('Group')->findProductGroup();
    		
    	$items = $groupDoc->toMultioptions('label');
        
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header-front.phtml', array(
            'labels' => $labels,
            'selectFields' => array(
                'id' => NULL,
        		'groupId' => $items,
        		'created' => NULL
            ),
            'url' => '/admin/product/get-product-json/',
            'actionId' => 'id',
            'click' => array(
            	'action' => 'contextMenu',
            	'menuItems' => array(
            		array('编辑', '/admin/product/edit/id/')
            	)
            ),
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));

        $this->view->assign('partialHTML', $partialHTML);
        $this->_helper->template->head('产品列表')->actionMenu(array('create'));
	}
	
	public function createAction()
	{
		$attributesetCo = App_Factory::_am('Attributeset');
		
		$attrDocSet = $attributesetCo->setFields(array('label'))
			->addFilter('type', 'product')
			->fetchAll();
			
		$this->view->attrRowset = $attrDocSet;
		$this->_helper->template->head('创建新产品');
	}
	
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$attributesetId = $this->getRequest()->getParam('attributeset-id');
		
		$productCo = App_Factory::_m('Product');
		
		if(empty($id)) {
			$productDoc = $productCo->create();
			$productDoc->attributesetId = $attributesetId;
		} else {
			$productDoc = $productCo->find($id);
		}
		
		$attributesetDoc = $productDoc->getAttributesetDoc();
		
		if(!is_null($attributesetDoc)) {
			$attrElements = $attributesetDoc->getZfElements();
		} else {
			$attrElements = array();
		}
		
		require APP_PATH."/admin/forms/Product/Edit.php";
		$form = new Form_Product_Edit();
		$form->addElements($attrElements);
		$form->populate($productDoc->toArray());
		
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$productDoc->setFromArray($form->getValues());
			$attaUrl = $this->getRequest()->getParam('attaUrl');
			$attaName = $this->getRequest()->getParam('attaName');
			$attaType = $this->getRequest()->getParam('attaType');
			$productDoc->setAttachments($attaUrl, $attaName, $attaType);
			$result = $productDoc->save();
			
			if($result) {
				$this->_helper->flashMessenger->addMessage('产品:'.$productDoc->label.' 已经成功保存');
				$this->_helper->switchContent->gotoSimple('index');
			}
		}
		
		$this->view->form = $form;
		$this->view->product = $productDoc;
		
		$co = App_Factory::_m('Info');
		$infoDoc = $co->fetchOne();
		
		$this->view->thumbWidth = empty($infoDoc->thumbWidth) ? 200 : $infoDoc->thumbWidth;
		$this->view->thumbHeight = empty($infoDoc->thumbHeight) ? 200 : $infoDoc->thumbHeight;
		
		$siteId = Class_Server::getSiteFolder();
		$time = time();
		$fileServerKey = 'gioqnfieowhczt7vt87qhitonqfn8eaw9y8s90a6fnvuzioguifeb';
		$sig = md5($siteId.$time.$fileServerKey);
		$this->view->time = $time;
		$this->view->sig = $sig;
		
		if(empty($id)) {
			$this->_helper->template->actionMenu(array('save'));
			$this->_helper->template->head('创建新产品');
		} else {
			$this->_helper->template->actionMenu(array('save', 'delete'));
			$this->_helper->template->head('编辑产品');
		}
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		
		$productCo = App_Factory::_m('Product');
		$productDoc = $productCo->find($id);
		
		if($productDoc == null){
			throw new Class_Exception_Pagemissing();
		}
		$productName = $productDoc->label;
		$productDoc->delete();
		$this->_helper->flashMessenger->addMessage('产品 '.$productName.' 已经删除');
		$this->_helper->switchContent->gotoSimple('index');
	}
	
	public function getProductJsonAction()
    {
    	$pageSize = 20;
		$currentPage = 1;
		
		$productCo = new Class_Mongo_Product_Co();
		$productCo->setFields(array('name', 'label', 'price', 'groupId'));
		$queryArray = array();
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                	case 'label':
                		$productCo->addFilter('label', new MongoRegex("/^".$value."/"));
                		break;
                    case 'page':
            			if(intval($value) != 0) {
            				$currentPage = $value;
            			}
                        $result['currentPage'] = intval($value);
            		    break;
                }
            }
        }
        $productCo->sort('_id', -1);
        
		$productCo->setPage($currentPage)->setPageSize($pageSize);
		$data = $productCo->fetchAll(true);
		$dataSize = $productCo->count();
		
		$result['data'] = $data;
        $result['dataSize'] = $dataSize;
        $result['pageSize'] = $pageSize;
        $result['currentPage'] = $currentPage;
        
        return $this->_helper->json($result);
    }
}