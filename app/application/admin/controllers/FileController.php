<?php
class Admin_FileController extends Zend_Controller_Action 
{
    public function init()
    {
        $this->view->headLink()->appendStylesheet(Class_HTML::server('lib').'/style/system.css');
        
//        $this->_helper->getHelper('AjaxContext')->addActionContext('get-file-selector', 'html')
//            ->initContext();
    }
    
    
    
    public function selectorAction()
    {
    	
    }
    
    
    
    
    
    
    
    
	public function indexAction()
    {
		
    }
    
    public function listAction()
    {
//    	$tb = Class_Base::_('GroupV2');
//    	$rowset = $tb->fetchAll($tb->select()->where('type = ?', 'file'));
//    	
		
//        $linkControl = new Class_Link_Controller($rowset);
        
        
        $clc = Class_Link_Controller::factory('file');
        Class_Link_Controller::setRenderer(new Class_Link_Renderer_Default());
        $this->view->linkHead = $clc->getLinkHead();
    }
    
    public function getFileJsonAction()
    {
	    $table = Class_Base::_('File');
	    $selector = $table->select();
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                    case 'group':
                        $selector->where('group = ?', $value);
                        break;
                    case 'groupId':
                        $selector->where('groupId = ?', $value);
                        break;
                }
            }
        }
        
        $rowset = $table->fetchAll($selector)->toArray();
        $result['data'] = $rowset;
        return $this->_helper->json($result);
    }
    
    public function getFileSelectorAction()
    {
    	$groupId = $this->getRequest()->getParam('groupId');
    	$table = Class_Base::_('File');
    	if(empty($groupId)) {
    		$selector = $table->select()->where('`groupId` is null');
    	} else {
    		$selector = $table->select()->where('`groupId` = ?', $groupId);
    	}
    	
		/**
		 * @todo paging
		 * Enter description here ...
		 * @var unknown_type
		 */
    	$selector->where('archived = 0');
    	$rowset = $table->fetchAll($selector);
    	
    	$siteInfo = Zend_Registry::get('siteInfo');
    	
    	$this->view->siteId = $siteInfo['id'];
    	$this->view->fileServer = $siteInfo['fileServer'];
    	$this->view->groupId = $groupId;
    	$this->view->rowset = $rowset;
    	$this->_helper->layout->disableLayout();
    }
    
    public function uploadJsonAction()
    {
    	set_time_limit(90);
    	$groupId = $this->getRequest()->getParam('groupId');
    	
        $result = 'success';
        $msg = array();
        
        $filename = $_FILES['fileId']['name'];
        $size = $_FILES['fileId']['size'];
//        if (!preg_match("/(swf|gif|jpg|jpeg|png|css|js|doc|docx|pdf|zip|rar|xls|xlsx)$/", $filename)) {
//            $result = 'fail';
//            $msg[] = 'file type is not correct: '.$filename.'!';
//        }
        if($size > 2048000) {
            $result = 'fail';
            $msg[] = 'file size exceeding 2000KB!';
        }
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if($groupId == 0) {
			$urlName = $filename;
        } else {
        	$urlName = md5_file($_FILES['fileId']['tmp_name']).'.'.$ext;
        }
        
        if($result == 'success') {
        	$tb = Class_Base::_('File');
        	$row = $tb->fetchRow($tb->select()->where('urlName = ?', $urlName));
        	
        	if(is_null($row)) {
        		$row = $tb->createRow();
        		$row->urlName = $urlName;
        	}
        	
        	$row->name = $filename;
        	$row->groupId = $groupId;
        	switch($ext) {
        		case 'js':
        			$row->type = 'script';
        			break;
        		case 'css':
        			$row->type = 'style';
        			break;
        		case 'doc':
        		case 'docx':
        		case 'pdf':
        			$row->type = 'file';
        			break;
        		case 'gif':
        		case 'jpg':
        		case 'jpeg':
        		case 'png':
        			$row->type = 'image';
        			break;
        		default:
        			$row->type = 'other';
        			break;
        	}
        	$fileService = new Class_Service_Rackspace_File_Instance();
        	$siteInfo = Zend_Registry::get('siteInfo');
        	$siteId = $siteInfo['id'];
			$container = $fileService->getContainer($siteInfo['fileServer']);
			
        	$object = @$container->create_object($siteId.'/'.$urlName);
        	if($ext == 'css') {
        		$object->content_type = 'text/css';
        	} else if($ext == 'js') {
        		$object->content_type = 'application/x-javascript';
        	}
        	$callback = @$object->load_from_filename($_FILES['fileId']['tmp_name']);
        	if($callback) {
        		$result = 'success';
        	}
        	if($result == 'success') {
        		$row->path = $urlName;
        		$row->size = $size;
        		$row->upload = new Zend_Db_Expr('NOW()');
        		$row->storage = $fileService->getProvider();
	            $row->save();
        	}
        }
        $this->_helper->json(array(
        	'result' => $result,
        	'msg' => $msg
        ));
    }
    
    public function deleteJsonAction()
    {
    	$id = $this->getRequest()->getParam('id');
    	$result = 'success';
        $msg = array();
        
    	if(is_null($id)) {
    		$this->_helper->json(array('result' => 'fail', 'msg' => $msg));
    	}
        
		$tb = Class_Base::_('File');
		$row = $tb->fetchRow($tb->select()->where('id = ?', $id));
		
		if(is_null($row)) {
			$result = 'fail';
			$msg = array("can't find file");
		}
		
//		$s3 = new Zend_Service_Amazon_S3('AKIAJOWQP4KCBIBS2R5A', 'EKg9N1oinbIr+HBPpdBKldvK+I5PotrlNuPdF8gT');
//		$amazonFilePath = 'misc.enorange.com/'.Class_HTML::server('folder').'/'.$row->type.'/'.$row->name;
		
		$fileService = new Class_Service_Rackspace_File_Instance();
		$siteInfo = Zend_Registry::get('siteInfo');
		$siteId = $siteInfo['id'];
		
		$container = $fileService->getContainer($siteInfo['fileServer']);
//		$container->delete_object($urlName);
		if($container->delete_object($siteId.'/'.$row->urlName)) {
			$row->delete();
		} else {
			$data['result'] = 'fail';
		}
		
		$this->_helper->json(array(
			'result' => $result,
			'msg' => $msg
		));
    }
    
    public function archiveJsonAction()
    {
    	$filename = $this->getRequest()->getParam('filename');
    	$result = 'success';
        $msg = array();
        
    	if(is_null($filename)) {
    		$this->_helper->json(array('result' => 'fail', 'msg' => $msg));
    	}
        
		$tb = Class_Base::_('File');
		$row = $tb->fetchRow($tb->select()->where('name = ?', $filename));
		
		if(is_null($row)) {
			$result = 'fail';
			$msg = array("can't find file");
		}
		$row->archived = 1;
		$row->save();
		
		$this->_helper->json(array(
			'result' => $result,
			'msg' => $msg
		));
    }
    
    public function moveFileJsonAction()
    {
    	$filename = $this->getRequest()->getParam('filename');
    	$groupId = $this->getRequest()->getParam('groupId');
    	if(is_null($filename)) {
    		$this->_helper->json(array(
    			'result' => 'fail',
    			'msg' => 'file name empty!'
    		));
    	}
    	
    	$tb = Class_Base::_('File');
		$row = $tb->fetchRow($tb->select()->where('name = ?', $filename));
		
		if(is_null($row)) {
			$this->_helper->json(array(
				'result' => 'fail',
    			'msg' => 'file '.$filename.' not found!'
			));
		}
		$row->groupId = $groupId;
		$row->save();
		
		$this->_helper->json(array('result' => 'success'));
    }
}