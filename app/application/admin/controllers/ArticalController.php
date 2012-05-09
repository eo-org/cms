<?php
class Admin_ArticalController extends Zend_Controller_Action
{
	protected $_controls;
	
	public function init()
	{
		$this->_helper->template->portal(array(
			array('label' => '内容', 'controllerName' => 'artical', 'href' => '/admin/artical/index'),
			array('label' => '内容分类', 'controllerName' => 'group', 'href' => '/admin/group/list/type/article'),
		));
	}
	
    public function indexAction()
    {
    	$this->_helper->template->head('内容列表');
    	
    	$groupId = $this->getRequest()->getParam('groupId');
    	
    	$tb = Class_Base::_('GroupV2');
    	$select = $tb->select()->where('type = ?', 'article');
    	$rowset = $tb->fetchAll($select);
        $groupArr = Class_Func::buildArr($rowset, 'id', 'label', array(0 => '未分类内容'));
        
        $hashParam = $this->getRequest()->getParam('hashParam');
        $labels = array(
			'id' => 'ID',
			'~selectedIds' => '#',
			'title' => '内容标题',
        	'alias' => '内容连接',
			'groupId' => '内容分类',
			'featured' => '精选',
			'~contextMenu' => ''
		);
		$partialHTML = $this->view->partial('select-search-header-front.phtml', array(
			'labels' => $labels,
			'selectFields' => array(
				'id' => null,
				'groupId' => $groupArr,
				'featured' => array(1 => '精选', 0 => '否')
			),
			'presetFields' => array('groupId' => $groupId),
			'url' => '/admin/artical/get-artical-json/',
			'actionId' => 'id',
			'click' => array(
				'action' => 'contextMenu',
				'menuItems' => array(
					array('编辑', '/admin/artical/edit/id/'),
					array('删除', '/admin/artical/delete/id/')
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
        $this->_forward('edit');
        $this->_helper->template->head('编辑新内容')
        	->actionMenu(array('save'));
    }
    
    public function editAction()
    {
        require APP_PATH.'/admin/forms/Artical/Edit.php';
        $form = new Form_Article_Edit();
        
        $id = $this->getRequest()->getParam('id');
        
        $table = Class_Base::_('Artical');
        $row = null;
        if(empty($id)) {
            $row = $table->createRow();
        } else {
        	$row = $table->fetchRow($table->select()->where('id = ?', $id));
        }
        if(is_null($row)) {
            throw new Class_Exception_AccessDeny('没有权限访问此内容，或者内容id不存在');
        }
        
        $form->populate($row->toArray());
        //preset groupId from url
    	$groupId = $this->getRequest()->getParam('groupId');
        if(!empty($groupId)) {
        	$form->groupId->setAttrib('disabled', 'disabled');
        	$form->groupId->setValue($groupId);
        }
        //end preset
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
            $row->setFromArray($form->getValues());
    		
            if(is_null($id)) {
            	$csa = Class_Session_Admin::getInstance();
	            $row->created = date('Y-m-d H:i:s');
	            $row->createdBy = $csa->getRoleId();
	            $row->createdByAlias = $csa->getUserData('loginName');
            }
            $row->save();
            
            $attachmentArr = $this->getRequest()->getParam('attachment');
            if(!is_null($attachmentArr)) {
	            $attachmentTb = new Zend_Db_Table('artical_attachment');
	            foreach($attachmentArr as $attachmentName) {
	            	$attachmentRow = $attachmentTb->createRow();
	            	$attachmentRow->articalId = $row->id;
	            	$attachmentRow->filename = $attachmentName;
	            	$attachmentRow->filepath = '/file/'.$siteId.'/attachment/'.$attachmentName;
	            	$attachmentRow->save();
	            }
            }
			$this->_helper->switchContent->gotoSimple('index', null, null, array(), true);
        }
    	
        if(!is_null($id)) {
            $tb = new Zend_Db_Table('artical_attachment');
            $rowset = $tb->fetchAll($tb->select()->where('articalId = ?', $id));
        } else {
        	$rowset = array();
        }
        
        $this->view->row = $row;
        $this->view->attachmentRowset = $rowset;
        $this->view->id = $id;
        $this->view->form = $form;
        
        $this->view->controls = array('save', 'delete');
        $this->_helper->template->head('编辑内容:<em>'.$row->title.'</em>')
        	->actionMenu(array('save', 'delete'));
    }
    
    public function deleteAction()
    {
    	$id = $this->getRequest()->getParam('id');
    	$table = Class_Base::_('Artical');
    	$row = $table->fetchRow(array($table->getAdapter()->quoteInto('id = ?', $id)));
    	if(is_null($row)) {
            throw new Class_Exception_AccessDeny('没有权限访问此内容，或者内容id不存在');
        }
        
        /**
         * @todo delete attachment as well!!
         */
        $row->delete();
//		if($this->getRequest()->getParam('format') == 'html') {
//			if($this->getRequest()->getParam('callback') == 'refresh') {
//				echo 'success';
//				$this->_helper->viewRenderer->setNoRender(true);
//				exit(0);
//			} else {
//				$this->_helper->switchContext->goto('index');
//			}
//		} else {
		$this->_helper->switchContent->gotoSimple('index');
//		}
    }
    
	public function deleteAttachmentJsonAction()
    {
    	$id = $this->getRequest()->getParam('id');
    	$tb = new Zend_Db_Table('artical_attachment');
    	$row = $tb->find($id)->current();
    	$row->delete();
    	
    	$this->_helper->json(array('result' => 'success'));
    }
    
    public function setFeaturedJsonAction()
    {
    	$id = $this->getRequest()->getParam('id');
    	$tb = Class_Base::_('Artical');
    	$row = $tb->find($id)->current();
    	
    	$result = array(
    		'result' => 'success',
    		'msg' => ''
    	);
    	if($row == null) {
    		$result = array(
	    		'result' => 'fail',
	    		'msg' => 'can not find artical with given id'
	    	);
    	} else {
    		if($row->featured == 0) {
    			$row->featured = 1;
    		} else {
    			$row->featured = 0;
    		}
    		$row->save();
    	}
    	if($this->getRequest()->getParam('format') == 'html') {
			if($this->getRequest()->getParam('callback') == 'refresh') {
				echo 'success';
				$this->_helper->viewRenderer->setNoRender(true);
				exit(0);
			} else {
				$this->_helper->redirector->gotoSimple('index', null, null, array('format' => 'html'));
			}
		} else {
			$this->_helper->json($result);
		}
    }
    
    public function setGroupJsonAction()
    {
    	
    }
    
    public function getIframeAction()
    {
        $labels = array(
            'id' => 'ID',
            'title' => '内容标题',
        );
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header.phtml', array(
            'labels' => $labels,
        	'selectFields' => array(
                'id' => null
            ),
            'url' => '/admin/artical/get-artical-json/',
            'actionId' => 'id',
        	'doubleClickAction' => 'updateParent',
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));

        $this->view->partialHTML = $partialHTML;
    }
    
    public function getArticalJsonAction()
    {
        $pageSize = 20;
        
	    $tb = Class_Base::_('Artical');
	    $selector = $tb->select(false)
	    	->from($tb, array('id', 'title', 'groupId', 'alias', 'featured'))
	    	->order('id DESC')
	        ->limitPage(1, $pageSize);
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                    case 'title':
                        $selector->where('title like ?', '%'.$value.'%');
                        break;
                    case 'groupId':
                        $selector->where('groupId = ?', $value);
                        break;
                    case 'alias':
                    	$selector->where('alias like ?', '%'.$value.'%');
                    case 'featured':
                    	$selector->where('featured = ?', $value);
                        break;
            		case 'page':
            			if(intval($value) == 0) {
            				$value = 1;
            			}
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