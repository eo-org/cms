<?php
class Admin_ProductTypeController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$labels = array(
        	'label' => '产品类型',
        	'~contextMenu' => ''
        );
        
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header-front.phtml', array(
            'labels' => $labels,
            'selectFields' => array(
                'id' => NULL,
        		'created' => NULL
            ),
            'url' => '/admin/product-type/get-product-type-json/',
            'actionId' => 'id',
            'click' => array(
            	'action' => 'contextMenu',
            	'menuItems' => array(
            		array('编辑', '/admin/product-type/edit/id/')
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
		$this->_forward('edit');
	}
	
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$attributesetCo = App_Factory::_m('Attributeset');
		if(empty($id)) {
			$doc = $attributesetCo->create();
		} else {
			$doc = $attributesetCo->find($id);
		}
		require APP_PATH."/admin/forms/Product/Type/Edit.php";
		$form = new Form_Product_Type_Edit();
		
//		$mongoDb = Zend_Registry::get('mongoDb');
//		$ptCollection = $mongoDb->product_type;
//		$row = $ptCollection->findOne(array('_id' => new MongoID($id)));
//		$productType = new Class_Mongo_Product_Type_Row($row);
		
		$form->populate($doc->toArray());
//		$form = $productType->appendAttributeList($form);
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$doc->setFromArray($form->getValues());
			$doc->type = 'product';
			$doc->save();
			$this->_helper->redirector->gotoSimple('index');
		}
		
		$this->view->form = $form;
		
		if(empty($id)) {
			$this->_helper->template->actionMenu(array('save'));
		} else {
			$this->_helper->template->actionMenu(array('save', 'delete'));
		}
//		Zend_Debug::dump($row);
	}
	
	public function deleteAction()
	{
		
	}
	
	public function getProductTypeJsonAction()
	{
		$pageSize = 20;
		$currentPage = 1;
		
        $attributesetCo = App_Factory::_m('Attributeset');
		$queryArray = array();
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                	case 'label':
                		$attributesetCo->addFilter('label', new MongoRegex("/^".$value."/"));
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
		
		$attributesetCo->setPage($currentPage)->setPageSize($pageSize);
		$data = $attributesetCo->fetchAll(true);
		$dataSize = $attributesetCo->count();
		
		$result['data'] = $data;
        $result['dataSize'] = $dataSize;
        $result['pageSize'] = $pageSize;
        $result['currentPage'] = $currentPage;
        
        return $this->_helper->json($result);
	}
}