<?php
class Admin_ProductController extends Zend_Controller_Action
{
    public function indexAction()
    {    	
        $labels = array(
        	'name' => '产品编号',
        	'label' => '产品名',
        	'sku' => 'SKU',
        	'price' => '价格',
        	'~contextMenu' => ''
        );
        
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header-front.phtml', array(
            'labels' => $labels,
            'selectFields' => array(
                'id' => NULL,
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
		$attributesetCo = new Class_Mongo_Attributeset_Co();
		
		$attrDocSet = $attributesetCo->setFields(array('label'))
			->addFilter('type', 'product')
			->fetchAll();
			
		$this->view->attrRowset = $attrDocSet;
	}
	
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$attributesetId = $this->getRequest()->getParam('attributeset-id');
		
		$productCo = App_Factory::_m('Product');
		$attributesetCo = App_Factory::_m('Attributeset');
		
		if(empty($id)) {
			$productDoc = $productCo->create();
			$productDoc->attributesetId = $attributesetId;
		} else {
			$productDoc = $productCo->find($id);
		}
		
		$attributesetId = $productDoc->attributesetId;
		$attributesetDoc = $attributesetCo->find($attributesetId);
		$attrElements = $attributesetDoc->getZfElements();
		
		require APP_PATH."/admin/forms/Product/Edit.php";
		$form = new Form_Product_Edit();
		$form->addElements($attrElements, 'main');
		$form->populate($productDoc->getData());
		
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$result = $productDoc->setFromArray($form->getValues())
				->save(false);
			if($result) {
				$this->_helper->flashMessenger->addMessage('产品已经成功保存');
				$this->_helper->switchContent->gotoSimple('index');
			}
		}
		
		$this->view->form = $form;
		
		if(empty($id)) {
			$this->_helper->template->actionMenu(array('save'));
		} else {
			$this->_helper->template->actionMenu(array('save', 'delete'));
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
		$productDoc->delete();
		
		$this->_helper->switchContent->gotoSimple('index');
	}
	
	public function getProductJsonAction()
    {
    	$pageSize = 20;
		$currentPage = 1;
		
		$productCo = new Class_Mongo_Product_Co();
		$productCo->setFields(array('name', 'label', 'sku', 'price'));
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