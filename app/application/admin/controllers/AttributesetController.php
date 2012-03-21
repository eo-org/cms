<?php
class Admin_AttributesetController extends Zend_Controller_Action
{
    public function indexAction()
    {
    	$this->_helper->template->head('属性组管理');
    	
    	$type = $this->getRequest()->getParam('type');
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
			'selectFields' => array(),
			'presetFields' => array('groupId' => $groupId),
			'url' => '/admin/attributeset/get-attributeset-json/',
			'actionId' => 'id',
			'click' => array(
				'action' => 'contextMenu',
				'menuItems' => array(
					array('编辑', '/admin/attributeset/edit/id/'),
					array('删除', '/admin/attributeset/delete/id/')
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
        $this->view->headScript()->appendFile(Class_HTML::server('lib').'/script/ckeditor/ckeditor.js');
        
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
	            $row->createdByAlias = $csa->getLoginName();
//	            $row->subdomainId = $this->_siteInfo['subdomain']['id'];
            }
            $row->save();
            $siteInfo = Zend_Registry::get('siteInfo');
            $siteId = $siteInfo['id'];
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
        
        $row->delete();
		$this->_helper->switchContent->gotoSimple('index');
    }
    
	public function getAttributesetJsonAction()
    {
    	$pageSize = 20;
		$currentPage = 1;
		
		$attributesetCo = App_Factory::_am('Attributeset');
		$attributesetCo->setFields(array('name', 'label', 'sku', 'price'));
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