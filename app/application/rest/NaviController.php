<?php
class Rest_NaviController extends Zend_Rest_Controller 
{
	public function init()
	{
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
	}
	
	public function indexAction()
	{
		$pageSize = 8;
		$currentPage = 1;
		
		$co = new Class_Mongo_File_Co();
		$co->setFields(array('filename', 'size', 'uploadTime', 'urlname'));
		$queryArray = array();
		
        $result = array();
        
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                	case 'groupId':
                		if($value != 'ungrouped') {
                			$co->addFilter('groupId', $value);
                		} else {
                			$co->addFilter('groupId', NULL);
                		}
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
		$co->setPage($currentPage)->setPageSize($pageSize)
			->sort('_id', -1);
		$data = $co->fetchAll(true);
		$dataSize = $co->count();
		
		$result['data'] = $data;
        $result['dataSize'] = $dataSize;
        $result['pageSize'] = $pageSize;
        $result['currentPage'] = $currentPage;
        
        $this->getResponse()->setHeader('Access-Control-Allow-Origin', '*');
        $this->_helper->json($result);
	}
	
	public function getAction()
	{
		
	}
	
	public function postAction()
	{
		$csu = Class_Session_User::getInstance();
		
		
		$service = Class_Api_Oss_Instance::getInstance();
		
		if($this->getRequest()->isPost()) {
			$miscFolder = Class_Server::getMiscFolder();
			$groupId = $this->getRequest()->getParam('groupId');
			$uploadedFile = $_FILES['uploadedfile'];
			
			$filename = $uploadedFile['name'];
			$tmpName = $uploadedFile['tmp_name'];
			$size = $uploadedFile['size'];
			if($size > 8192000) {
	            $result = 'fail';
	            $msg[] = 'file size exceeding 8000KB!';
	        }
			$fileContent = file_get_contents($tmpName);
			$uploadUnixTime = time();
			
			$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			
			$urlname = md5($uploadUnixTime.$size).'.'.$ext;
			//internal use, used as thumb in file controller system
			$thumbname = TMP_PATH.'/'.$urlname;
			
			$ci = new Class_Image();
			$ci->readImage($tmpName, $ext)
				->resize(120, 120, Class_Image::FIT_TO_FRAME)
				->writeImage($thumbname, 60);
			$thumbContent = file_get_contents($thumbname);
			
			
//			
			
//			
			$imgResult = $service->createObject('public-misc', $miscFolder.'/'.$urlname, $fileContent, $size);
			$thumbResult = $service->createObject('public-misc', $miscFolder.'/_thumb/'.$urlname, $thumbContent);
//			
//			if($result) {
				
				$fileDoc = App_Factory::_m('File')->create(array(
					'userId' => $csu->getUserId(),
					'groupId' => $groupId,
					'filename' => $filename,
					'urlname' => $urlname,
					'size' => $size,
					'storage' => $service->getProvider(),
					'uploadUnixTime' => $uploadUnixTime,
					'uploadTime' => date('Y-m-d H:i:s', $uploadUnixTime)
				));
//				
				$fileDoc->save();
//				

				$groupDoc = App_Factory::_m('Group')->find($groupId);
				$groupDoc->fileCount++;
				$groupDoc->save();
				
//			}
			
		}
		$this->getResponse()->setHeader('result', 'sucess');
		$this->_helper->json($fileDoc->toArray(true));
	}
	
	public function putAction()
	{
		
	}
	
	public function deleteAction()
	{
		$csu = Class_Session_User::getInstance();
		$fileId = $this->getRequest()->getParam('id');
		$fileDoc = App_Factory::_m('File')->find($fileId);
		
		if($fileDoc != null) {
			$objectUrl = $fileDoc->urlname;
			$groupId = $fileDoc->groupId;
			$miscFolder = Class_Server::getMiscFolder();
			
			$service = Class_Api_Oss_Instance::getInstance();
			$service->removeObject('public-misc', $miscFolder.'/'.$objectUrl);
			$service->removeObject('public-misc', $miscFolder.'/_thumb/'.$objectUrl);
			
			$fileDoc->delete();
			if(!empty($groupId)) {
				$groupDoc = App_Factory::_m('Group')->find($groupId);
				$groupDoc->fileCount--;
				$groupDoc->save();
			}
		}
		$this->getResponse()->setHeader('result', 'sucess');
	}
}