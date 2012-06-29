<?php
class Admin_ArticleController extends Zend_Controller_Action
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
			'label' => '内容标题',
        	'link' => '内容连接',
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
			'url' => '/admin/article/get-article-json/',
			'actionId' => 'id',
			'click' => array(
				'action' => 'contextMenu',
				'menuItems' => array(
					array('编辑', '/admin/article/edit/id/')
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
        require APP_PATH.'/admin/forms/Article/Edit.php';
        $form = new Form_Article_Edit();
	
        $co = App_Factory::_m('Group');
        $multioptions = $co->getByType('article', true);
        $form->groupId->setMultioptions($multioptions);
        
        $id = $this->getRequest()->getParam('id');
        
        $co = App_Factory::_m('Article');
        $doc = null;
        if(empty($id)) {
            $doc = $co->create();
        } else {
        	$doc = $co->find($id);
        }
        if(is_null($doc)) {
            throw new Class_Exception_AccessDeny('没有权限访问此内容，或者内容id不存在');
        }
        
        $form->populate($doc->toArray());
        $attachmentArr = $doc->attachment;
        //preset groupId from url //why preset groupId ??
//    	$groupId = $this->getRequest()->getParam('groupId');
//        if(!empty($groupId)) {
//        	$form->groupId->setAttrib('disabled', 'disabled');
//        	$form->groupId->setValue($groupId);
//        }
        //end preset
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
        	$attachmentStr = $this->getRequest()->getParam('attachmentJson');
			$attachmentArr = Zend_Json::decode($attachmentStr);
			$doc->attachment = $attachmentArr;
            $doc->setFromArray($form->getValues());
    		
            if(is_null($id)) {
            	$csa = Class_Session_Admin::getInstance();
	            $doc->created = date('Y-m-d H:i:s');
	            $doc->createdBy = $csa->getRoleId();
	            $doc->createdByAlias = $csa->getUserData('loginName');
            }
            $doc->save();
			$this->_helper->switchContent->gotoSimple('index', null, null, array(), true);
        }
    	
        if(!is_null($id)) {
//            $tb = new Zend_Db_Table('artical_attachment');
//            $rowset = $tb->fetchAll($tb->select()->where('articalId = ?', $id));
			$rowset = array();
        } else {
        	$rowset = array();
        }
        
        $this->view->doc = $doc;
        $this->view->id = $id;
        
        $this->view->form = $form;
        $this->view->attachmentArr = $attachmentArr;
        
        $this->_helper->template->head('编辑内容:<em>'.$doc->title.'</em>')
        	->actionMenu(array(
        		array('label' => '保存文章', 'callback' => '', 'method' => 'saveWithAttachment'),
        		'delete'
        	));
    }
    
    public function deleteAction()
    {
    	$id = $this->getRequest()->getParam('id');
    	$co = App_Factory::_m('Article');
    	$doc = $co->find($id);
    	if(is_null($doc)) {
            throw new Class_Exception_AccessDeny('没有权限访问此内容，或者内容id不存在');
        }
        
        /**
         * @todo delete attachment as well!!
         */
        $doc->delete();
		
        $this->_helper->flashMessenger->addMessage('文章:'.$doc->label.'已删除！');
		$this->_helper->switchContent->gotoSimple('index');
    }
    
//	public function deleteAttachmentJsonAction()
//    {
//    	$id = $this->getRequest()->getParam('id');
//    	$co = App_Factory::_m('Article');
//    	$doc = $co->find($id);
//    	$doc->delete();
//    	
//    	$this->_helper->json(array('result' => 'success'));
//    }
    
    public function setFeaturedJsonAction()
    {
    	$id = $this->getRequest()->getParam('id');
    	$tb = App_Factory::_m('Article');
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
    
    public function getArticleJsonAction()
    {
        $pageSize = 20;
		$currentPage = 1;
		
		$co = App_Factory::_m('Article');
		$co->setFields(array('id', 'label', 'groupId', 'link', 'featured'));
		$queryArray = array();
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                	case 'label':
                		$co->addFilter('label', new MongoRegex("/^".$value."/"));
                		break;
                	case 'groupId':
                		$co->addFilter('groupId', $value);
                		break;
                	case 'link':
                		$co->addFilter('link', $value);
                		break;
                	case 'featured':
                		$co->addFilter('featured', $value);
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