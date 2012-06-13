<?php
class Admin_CategoryController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $sectionId = $this->getRequest()->getParam('sectionId');
        if(is_null($sectionId)) {
            throw new Exception('need section Id');
        }
        
        $categoryTb = Class_Base::_('Category');
        $selector = $categoryTb->select()->from($categoryTb, array('id', 'parentId', 'level', 'label', 'title', 'linkType', 'link', 'order'))
            ->where('sectionId = ?', $sectionId);
        $data = $categoryTb->fetchAll($selector);
        Class_Link_Controller::setRenderer(new Class_Link_Renderer_CategoryDrag());
        $linkControl = new Class_Link_Controller($data);
        
        $this->view->linkHead = $linkControl->getLinkHead();
		$this->_helper->template->actionMenu(array(
			array(
				'label' => '保存排序',
				'callback' => '/admin/category/update-order-json/sectionId/'.$sectionId,
				'method' => 'func'
			),
			'create')
		)->head('连接排序');
    }
    
    public function createAction()
    {
        $this->_forward('edit');
    }
    
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $sectionId = $this->getRequest()->getParam('sectionId');
        $categoryTb = Class_Base::_('Category');
        $category = null;
        if(empty($id)) {
            $category = $categoryTb->createRow();
        } else {
            $category = $categoryTb->fetchRow($categoryTb->select()
                ->where('id = ?', $id)
            );
            $sectionId = $category->sectionId;
        }
        
        require(APP_PATH.'/admin/forms/Category/Edit.php');
        $form = new Form_CategoryEdit();
        $form->populate($category->toArray());
        if(!empty($sectionId)) {
        	$form->getElement('sectionId')->setValue($sectionId);
        }
        if($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getPost())) {
            	$category->setFromArray($form->getValues());
            	$category->linkType = 'url';
                $category->save();
                if($category->order == null) {
                    $category->order = $category->id;
                    $category->save();
                }
                $sectionId = $category->sectionId;
	            $this->_helper->switchContent->gotoSimple('index', null, null, array('sectionId' => $sectionId));
            }
        }
        
        $this->view->assign('form', $form);
		$this->_helper->template->actionMenu(array('save', 'delete'));
		
		$this->getRequest()->setParam('sectionId', $sectionId);
    }
    
    public function deleteAction()
    {
    	$id = intval($this->getRequest()->getParam('id'));
    	$tb = Class_Base::_('Category');
    	$row = $tb->find($id)->current();
    	if(!is_null($row)) {
    		$row->delete();
    	}
    	echo "success";
    	exit(0);
    }
    
    public function updateOrderJsonAction()
    {
    	$sectionId = $this->getRequest()->getParam('sectionId');
    	
    	$param = $this->getRequest()->getParam('param');
    	$param = substr($param, 0, -1);
    	$categoryArr = explode('|', $param);
    	$tb = Class_Base::_('Category');
    	$i = 0;
    	
    	foreach($categoryArr as $cat) {
    		$i++;
    		$catArr = explode(':', $cat);
    		$id = $catArr[0];
    		$parentId = $catArr[1];
    		$row = $tb->find($id)->current();
    		$row->parentId = $parentId;
    		$row->order = $i;
    		$row->save();
    	}
    	$this->_helper->flashMessenger->addMessage('新的排序已保存！');
    	$this->_helper->switchContent->gotoSimple('index', null, null, array('sectionId' => $sectionId));
    }
    
    public function updateParentJsonAction()
    {
        $id = intval($this->getRequest()->getParam('id'));
        $pid = intval($this->getRequest()->getParam('pid'));
        $ids = $this->getRequest()->getParam('indexes');
        $orderArr = explode('-', $ids);
        
        $table = Class_Base::_('Category');
        $selector = $table->select()->from($table, array(
            'id', 'parentId', 'order'
        ))->where('id in ('.implode(',', $orderArr).')');
        $rowset = $table->fetchAll($selector);
        
        try {
            foreach($rowset as $cat) {
                $catId = $cat->id;
                $order = array_search($catId, $orderArr);
                $cat->order = $order;
                if($catId == $id) {
                    $cat->parentId = $pid;
                }
                $cat->save();
            }
        } catch(Exception $e) {
            $this->_helper->json(array(
            	'flag' => 'failed'
            ));
        }
        $this->_helper->json(array(
        	'flag' => 'success'
        ));
    }
    
    public function getLinkTypeAction()
    {
        
    }
    
    public function getCategoryJsonAction()
    {
    	$pageSize = 3;
        $type = $this->getRequest()->getParam('type');
        
	    $tb = Class_Base::_('Category');
	    $selector = $tb->select()->limitPage(1, $pageSize);
        $result = array();
        
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
            		case 'page':
            		    $selector->limitPage(intval($value), $pageSize);
                        $result['currentPage'] = intval($value);
            		    break;
                }
            }
        }
        
        $rowset = $tb->fetchAll($selector)->toArray();
        $result['data'] = $rowset;
        $result['dataSize'] = Class_Func::count($selector);
        $result['pageSize'] = $pageSize;
        
        if(empty($result['currentPage'])) {
        	$result['currentPage'] = 1;
        }
        return $this->_helper->json($result);
    }
}