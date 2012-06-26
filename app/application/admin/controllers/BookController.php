<?php
class Admin_BookController extends Zend_Controller_Action
{
	public function init()
	{
		$this->view->headLink()->appendStylesheet(Class_Server::libUrl().'/admin/style/book-editor.css');
	}
	
    public function indexAction()
    {
    	$this->_helper->template->head('Book列表');

        $hashParam = $this->getRequest()->getParam('hashParam');
        $labels = array(
			'label' => '书目名',
			'~contextMenu' => ''
		);
		$partialHTML = $this->view->partial('select-search-header-front.phtml', array(
			'labels' => $labels,
			'selectFields' => array(),
			'url' => '/admin/book/get-book-json/',
			'actionId' => 'id',
			'click' => array(
				'action' => 'contextMenu',
				'menuItems' => array(
					array('编辑', '/admin/book/edit/id/')
				)
			),
			'initSelectRun' => 'true',
			'hashParam' => $hashParam
		));

        $this->view->partialHTML = $partialHTML;
        $this->_helper->template->actionMenu(array('create'));
    }
    
    public function createAction()
    {
    	require APP_PATH.'/admin/forms/Book/Create.php';
    	$form = new Form_Book_Create();
    	$co = App_Factory::_m('Book');
    	if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
    		$doc = $co->create();
    		$doc->label = $form->getValue('label');
    		$doc->save();
    		$this->_helper->redirector->gotoSimple('edit', null, null, array(
    			'id' => $doc->getId()
    		));
    	}
    	$this->view->form = $form;
    	$this->_helper->template->head('创建书目')
        	->actionMenu(array('save'));
    }
    
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        
		$co = App_Factory::_m('Book');
		$book = $co->find($id);
		$book->readPages();
		
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
        	
        }
        
        $this->view->book = $book;
        $this->_helper->template->head('编辑BOOK:<em>'.$book->title.'</em>')
        	->actionMenu(array(
        		'create-page' => array('label' => '添加新书页', 'callback' => '/admin/book/edit-page/book-id/'.$id),
        		'save',
        		'delete'
        	));
    }
    
    public function editPageAction()
    {
    	$id = $this->getRequest()->getParam('id');
    	$co = App_Factory::_m('Book_Page');
    	if(!is_null($id)) {
    		$pageDoc = $co->find($id);
    		$bookId = $pageDoc->bookId;
    		$op = 'edit';
    	} else {
    		$pageDoc = $co->create();
    		$bookId = $this->getRequest()->getParam('book-id');
    		$op = 'create';
    	}
    	if(is_null($bookId)) {
    		throw new Exception('book id missing');
    	}
    	$bookDoc = App_Factory::_m('Book')->setFields(array('label'))
    		->find($bookId);
    	
    	require APP_PATH.'/admin/forms/Book/Page/Edit.php';
    	$form = new Form_Book_Page_Edit();
    	$form->populate($pageDoc->toArray());
    	if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
    		$pageDoc->setFromArray($form->getValues());
    		if($op == 'create') {
    			$pageDoc->bookId = $bookId;
    			$pageDoc->parentId = 0;
    			$pageDoc->sort = 0;
    		}
    		$pageDoc->save();
    		
    		$this->_helper->redirector->gotoSimple('edit', null, null, array('id' => $bookId));
    	}
    	
    	$this->view->form = $form;
    	$this->_helper->template->head('编辑 <em>'.$bookDoc->label.'</em> 章节')
        	->actionMenu(array('save', 'delete'));
    }
    
    public function deleteAction()
    {
    	$id = $this->getRequest()->getParam('id');
    	$co = App_Factory::_m('Article');
    	$doc = $co->find($id);
    	if(is_null($doc)) {
            throw new Class_Exception_AccessDeny('没有权限访问此内容，或者内容id不存在');
        }
        
        $doc->delete();
		
        $this->_helper->flashMessenger->addMessage('文章:'.$doc->label.'已删除！');
		$this->_helper->switchContent->gotoSimple('index');
    }
    
    public function pageSortAction()
    {
    	$jsonStr = $this->getRequest()->getParam('pageSortJsonStr');
    	$pageArr = Zend_Json::decode($jsonStr);
    	
    	$co = App_Factory::_m('Book_Page');
    	$pageDocs = $co->setFields(array('parentId', 'sort', 'level'))
			->sort('sort', 1)
			->fetchDoc();
    	foreach($pageDocs as $pageDoc) {
    		$pageId = $pageDoc->getId();
    		$newPageValue = $pageArr[$pageId];
    		$sort = intval($newPageValue['sort']);
    		$parentId = $newPageValue['parentId'];
    		if($pageDoc->sort != $sort || $pageDoc->parentId != $parentId) {
    			$pageDoc->sort = $sort;
    			$pageDoc->parentId = $parentId;
    			$pageDoc->save();
    		}
    	}
    	
    	$this->_helper->json(array('result' => 'success'));
    }
    
    public function redoNaviAction()
    {
    	$id = $this->getRequest()->getParam('id');
    	
		$bookDoc = App_Factory::_m('Book')->find($id);
    	$bookDoc->readPages();
    	
    	$pageIndex = $bookDoc->buildIndex();
    	
    	$bookDoc->pageIndex = $pageIndex;
    	$bookDoc->save();
    	
    	$this->_helper->json(array('result' => 'success'));
    }
    
    public function getBookJsonAction()
    {
        $pageSize = 20;
		$currentPage = 1;
		
		$co = App_Factory::_m('Book');
		$co->setFields(array('id', 'label'));
		$queryArray = array();
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                	case 'label':
                		$co->addFilter('label', new MongoRegex("/^".$value."/"));
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
        $co->sort('_id', -1);
        
		$co->setPage($currentPage)->setPageSize($pageSize);
		$data = $co->fetchAll(true);
		$dataSize = $co->count();
		
		$result['data'] = $data;
        $result['dataSize'] = $dataSize;
        $result['pageSize'] = $pageSize;
        $result['currentPage'] = $currentPage;
        
        return $this->_helper->json($result);
    }
}