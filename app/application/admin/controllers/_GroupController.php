<?php
class Admin_GroupController extends Zend_Controller_Action
{
	public function init()
	{
		$type = $this->getRequest()->getParam('type');
		switch($type) {
			case 'article':
				$this->_helper->template->portal(array(
					array('label' => '内容', 'controllerName' => 'artical', 'href' => '/admin/artical/index'),
					array('label' => '内容分类', 'controllerName' => 'group', 'href' => '/admin/group/list/type/article'),
				));
				break;
			case 'product':
				$this->_helper->template->portal(array(
					array('label' => '产品', 'controllerName' => 'product', 'href' => '/admin/artical/index'),
					array('label' => '产品分类', 'controllerName' => 'group', 'href' => '/admin/group/list/type/product'),
				));
				break;
			case 'ad':
				$this->_helper->template->portal(array(
					array('label' => '广告', 'controllerName' => 'ad', 'href' => '/admin/artical/index'),
					array('label' => '广告分类', 'controllerName' => 'group', 'href' => '/admin/group/list/type/ad'),
				));
				break;
			default:
				break;
		}
	}
	
    public function indexAction()
    {
    	$this->_helper->template->head('分组管理');
    }
    
	public function listAction()
	{
		$type = $this->getRequest()->getParam('type');
    	if(empty($type)) {
    		throw new Exception('group type not defined');
    	}
    	$groupTb = Class_Base::_('GroupV2');
    	$selector = $groupTb->select()->from($groupTb, array('id', 'parentId', 'label', 'sort'))
    		->where('type = ?', $type);
        $data = $groupTb->fetchAll($selector);
        Class_Link_Controller::setRenderer(new Class_Link_Renderer_GroupDrag());
        $linkControl = new Class_Link_Controller($data);
        
        $this->view->linkHead = $linkControl->getLinkHead();
		$this->_helper->template->actionMenu(array(
				array(
					'label' => '保存排序',
					'callback' => '/admin/group/update-order-json/type/'.$type,
					'method' => 'func'
				),
			'create'));
		switch($type) {
			case 'article':
				$this->_helper->template->head('文章分组');
				break;
			case 'file':
				$this->_helper->template->head('文件分组');
				break;
			case 'ad':
				$this->_helper->template->head('广告分组');
				break;
			case 'product':
				$this->_helper->template->head('产品分组');
				break;
		}
	}
	
    public function createAction()
    {
		$type = $this->getRequest()->getParam('type');
    	if(is_null($type)) {
    		throw new Exception('group type not defined');
    	}
    	
    	$table = Class_Base::_('Group');
        $row = $table->createRow();
		
        require APP_PATH.'/admin/forms/Group/Create.php';
        $form = new Form_Group_Edit();
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
            $row->setFromArray($form->getValues());
            $row->parentId = 0;
            $row->type = $type;
            
			$row->save();
			$this->_helper->flashMessenger->addMessage('新分类已创建！');
	        $this->_helper->switchContent->gotoSimple('list', null, null, array('type' => $type));
        }
        $this->view->form = $form;
        
		$this->_helper->template->actionMenu(array('save'))->head('创建新的分类');
    }
    
    public function editAction()
    {
    	$table = Class_Base::_('Group');
    	$id = $this->getRequest()->getParam('id');
    	if(is_null($id)) {
        	$row = $table->createRow();
        } else {
			$row = $table->find($id)->current();
        }
		
        require APP_PATH.'/admin/forms/Group/Edit.php';
        $form = new Form_Group_Edit($row->hasChildren);
        
    	if(is_null($row)) {
        	throw new Exception('brick not found');
        }
        $form->populate($row->toArray());
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
            $row->setFromArray($form->getValues());
			$row->save();
	        $this->_helper->flashMessenger->addMessage('分类详情已修改！');
	        $this->_helper->switchContent->gotoSimple('list', null, null, array('type' => $row->type));
        }
        $form->populate($row->toArray());
    	
        $this->view->form = $form;
        $this->_helper->template->actionMenu(array('save', 'delete'))->head('编辑分组详情');
    }
    
    public function deleteAction()
    {
    	$id = $this->getRequest()->getParam('id');
    	if(is_null($id)) {
        	throw new Exception('specify a brick id to edit');
        }
        $table = Class_Base::_('Group');
		$row = $table->find($id)->current();
		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		try {
			$db->query('update artical set groupId = 0 where groupId = '.$row->id);
			$db->query('update `group` set parentId = 0 where parentId = '.$row->id);
	    	$row->delete();
	    	$db->commit();
		} catch (Exception $e) {
			$db->rollback();
			throw $e;
		}
    	$this->_helper->redirector->gotoSimple('index');
    }
    
    public function getIframeAction()
    {
        $labels = array(
            'id' => 'ID',
            'label' => '内容分类',
        );
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header.phtml', array(
            'labels' => $labels,
        	'selectFields' => array(
                'id' => null
            ),
            'url' => '/admin/group/get-group-json/',
            'actionId' => 'id',
        	'doubleClickAction' => 'updateParent',
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));

        $this->view->assign('partialHTML', $partialHTML);
    }
    
    public function getGroupJsonAction()
    {
        $pageSize = 30;
        
	    $table = Class_Base::_('Group');
	    $selector = $table->select()->order('sort')
	        ->limitPage(1, $pageSize);
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                    case 'label':
                        $selector->where('label like ?', '%'.$value.'%');
                        break;
                    case 'parentId':
                    	$selector->where('parentId = ?', $value);
                        break;
            		case 'page':
            		    $selector->limitPage(intval($value), $pageSize);
                        $result['currentPage'] = intval($value);
            		    break;
            		case 'selectedIds':
					    if($value != 'all') {
					        $selector->where('id in (?)', explode(',', $value));
					    }
					    break;
                }
            }
        }
        $rowset = $table->fetchAll($selector);
        $rowsetArr = array();
        if(!is_null($rowset)) {
            $rowsetArr = $rowset->toArray();
        }
        
        $result['data'] = $rowsetArr;
        $result['pageSize'] = $pageSize;
        
        return $this->_helper->json($result);
    }
    
	public function updateOrderJsonAction()
    {
    	$type = $this->getRequest()->getParam('type');
    	
    	$param = $this->getRequest()->getParam('param');
    	$param = substr($param, 0, -1);
    	$groupArr = explode('|', $param);
    	$tb = Class_Base::_('GroupV2');
    	$i = 0;
    	
    	foreach($groupArr as $cat) {
    		$i++;
    		$catArr = explode(':', $cat);
    		$id = $catArr[0];
    		$parentId = $catArr[1];
    		$row = $tb->find($id)->current();
    		$row->parentId = $parentId;
    		$row->sort = $i;
    		$row->save();
    	}
    	$this->_helper->flashMessenger->addMessage('新的排序已保存！');
    	$this->_helper->switchContent->gotoSimple('list', null, null, array('type' => $type));
    }
    
    public function updateParentJsonAction()
    {
        $id = intval($this->getRequest()->getParam('id'));
        $pid = intval($this->getRequest()->getParam('pid'));
        $ids = $this->getRequest()->getParam('indexes');
        $orderArr = explode('-', $ids);
        
        $table = Class_Base::_('Group');
        $selector = $table->select()->from($table, array(
            'id', 'parentId', 'sort'
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
    
    public function moveAction()
    {
    	$id = intval($this->getRequest()->getParam('id'));
    	$direction = $this->getRequest()->getParam('direction');
    	
    	$result = 'fail';
    	$table = Class_Base::_('Group');
        $selector = $table->select(false)->from($table, array(
            'id', 'parentId', 'sort'
        ))->where('id = ?', $id);
        $row = $table->fetchRow($selector);
        if(is_null($row)) {
        	$result = "can't find row";
        } else {
	        if($direction == 'up') {
	        	$prevRow = $table->fetchRow($table->select(false)
	        		->where('parentId = ?', $row->parentId)
	        		->where('sort < ?', $row->sort)
	        		->order('sort DESC')
	        	);
	        	if(!is_null($prevRow)) {
	        		$row->sort = $prevRow->sort;
	        		$prevRow->sort++;
		        	$row->save();
		        	$prevRow->save();
		        	$result = 'success';
	        	} else {
	        		$result = 'alread top';
	        	}
	        } else if($direction == 'down') {
	        	$nextRow = $table->fetchRow($table->select(false)
	        		->where('parentId = ?', $row->parentId)
	        		->where('sort > ?', $row->sort)
	        		->order('sort')
	        	);
	        	if(!is_null($nextRow)) {
		        	$nextRow->sort = $row->sort;
		        	$row->sort++;
		        	$row->save();
		        	$nextRow->save();
		        	$result = 'success';
	        	}
	        }
        }
        $this->_helper->json(array('result' => $result));
    }
}