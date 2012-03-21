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
		
	}
	
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		require APP_PATH."/admin/forms/Product/Type/Edit.php";
		$form = new Form_Product_Type_Edit();
		
		$mongoDb = Zend_Registry::get('mongoDb');
		$ptCollection = $mongoDb->product_type;
		$row = $ptCollection->findOne(array('_id' => new MongoID($id)));
		$productType = new Class_Mongo_Product_Type_Row($row);
		
		$form->populate($row);
		$form = $productType->appendAttributeList($form);
		
		
		$this->view->form = $form;
		Zend_Debug::dump($row);
	}
	
	public function deleteAction()
	{
		
	}
	
	public function getProductTypeJsonAction()
	{
		$pageSize = 20;
		$currentPage = 1;
		
        $mongoDb = Zend_Registry::get('mongoDb');
		$ptCollection = $mongoDb->product_type;
		$queryArray = array();
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                	case 'label':
                		$queryArray['label'] = new MongoRegex("/^".$value."/");
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
		
		$dataSize = $ptCollection->find($queryArray)->count();
		
		$startRow = ($currentPage - 1)*$pageSize ;
		$rowset = $ptCollection->find($queryArray, array('id', 'label'))
			->limit($pageSize)
			->skip($startRow);
		
		$data = array();
		foreach($rowset as $id => $row) {
			$row['id'] = $id;
			$data[] = $row;
		}
		
		$result['data'] = $data;
        $result['dataSize'] = $dataSize;
        $result['pageSize'] = $pageSize;
        $result['currentPage'] = $currentPage;
        
        return $this->_helper->json($result);
	}
}