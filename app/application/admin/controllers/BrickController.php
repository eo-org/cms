<?php
class Admin_BrickController extends Zend_Controller_Action
{
    protected $_pageSize = 20;
    
    public function indexAction()
    {
        $labels = array(
        	'label' => '页面',
            'extName' => '模块类型',
            'brickName' => '模块名',
            'cssSuffix' => 'CSS后缀',
        	'tplName' => 'TPL',
        	'~contextMenu' => ''
        );
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header-front.phtml', array(
            'labels' => $labels,
        	'selectFields' => array(
        		'useTpl' => array(0 => '否', 1 => '是')
	        ),
            'url' => '/admin/brick/get-brick-json/',
            'actionId' => 'brickId',
        	'click' => array(
            	'action' => 'contextMenu',
            	'menuItems' => array(
            		array('编辑', '/admin/brick/edit/brick-id/')
            	)
            ),
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));

        $this->view->assign('partialHTML', $partialHTML);
    }
    
//    public function listCreatedAction()
//    {
//    	$spriteId = $this->getRequest()->getParam('spriteId');
//    	
//    	$tb = Class_Base::_('Brick');
//    	$selector = $tb->select(false)->setIntegrityCheck(false)
//	    	->from($tb, array('brickId', 'extName', 'brickName', 'description', 'cssSuffix', 'order'))
//	    	->order('brick.order')
//	    	->limitPage(1, 10);
//	    $rowset = $tb->fetchAll($selector);
//	    
//	    $this->view->stageId = $spriteId;
//	    $this->view->rowset = $rowset;
//    }
    
    public function createAction()
    {
    	$layoutId = $this->getRequest()->getParam('layoutId');
    	$stageId = $this->getRequest()->getParam('stageId');
    	$spriteName = $this->getRequest()->getParam('spriteName');
    	
    	$siteDb = Zend_Registry::get('siteDb');
    	$tb = new Zend_Db_Table(array(
    		Zend_Db_Table_Abstract::ADAPTER => $siteDb,
    		Zend_Db_Table_Abstract::NAME => 'extension'
    	));
    	$rowset = $tb->fetchAll($tb->select()->where('deprecated = 0')->order('sort'));
    	
    	$this->view->layoutId = $layoutId;
    	$this->view->stageId = $stageId;
    	$this->view->spriteName = $spriteName;
    	$this->view->rowset = $rowset;
    }
    
    public function editAction()
    {
    	require_once APP_PATH.'/admin/forms/Brick/Edit.php';
    	$form = new Form_Edit();
    	$brickId = $this->getRequest()->getParam('brick-id');
    	$status = 'edit';
    	if(empty($brickId)) {
    		$extName = $this->getRequest()->getParam('extName');
    		$status = 'create';
	    	if(empty($extName)) {
	    		$this->_helper->redirector()->gotoSimple('index');
	    	}
    	}
//    	$brickTb = Class_Base::_('Brick');
    	$co = App_Factory::_m('Brick');
    	if($status == 'edit') {
    		$brick = $co->find($brickId);
    	} else {
    		$brick = $co->create();
    		$brick->extName = $extName;
    		$form->addElement('hidden', 'extName', array(
    			'value' => $extName
    		));
    	}
    	$solidBrick = $brick->createSolidBrick($this->getRequest());
    	$tplArr = $solidBrick->getTplArray();
    	$form->tplName->setMultiOptions($tplArr);
    	$form = $solidBrick->configParam($form);
    	
    	if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
    		
    		
//    		Zend_Debug::dump($this->getRequest()->getParams());
//    		die();
    		
    		
    		$brick->setFromArray($form->getValues());
    		if($form->getValue('stageId') == '0') {
    			$brick->layoutId = 0;
    		}
    		
    		$paramArr = array();
    		foreach($form->getValues() as $key => $value) {
    			if(strpos($key, 'param_') !== false) {
    				$jsonKey = substr($key, 6);
    				$paramArr[$jsonKey] = $value;
    			}
    		}
    		
    		$brick->params = $paramArr;
    		$brick->active = 1;
//    		$db = Zend_Registry::get('db');
//    		$db->beginTransaction();
//		    try {
//		    	if($brick->sort == "") {
//		    		$brick->sort = NULL;
//		    	}
				$brick->save();
		    	$brickId = $brick->brickId;
//                $db->commit();
//            } catch(Exception $e) {
//		        $db->rollBack();
//		        throw $e;
//            }

            $this->_helper->switchContent->gotoSimple('index', null, null, array(), true);
    	}
    	
    	$form->populate($brick->toArray());
    	
    	if(!is_null($brick->params)) {
	    	$params = $brick->params;
	    	foreach($params as $key => $value) {
	    		$el = $form->getElement('param_'.$key);
	    		if(!is_null($el)) {
	    			$el->setValue($value);
	    		}
	    	}
    	}
    	
        $selectedIds = array();
    	$this->view->form = $form;
    	$this->view->solidBrick = $solidBrick;
        $this->_helper->template->actionMenu(array('save', 'delete', 'edit-tpl' => array(
        	'callback' => '/admin/brick/edit-tpl/brick-id/'.$brickId,
        	'label' => '新建TPL文件'
        )))->head('编辑模块：<em>'.$brick->extName.'</em>');
    }
    
    public function deleteAction()
    {
    	$brickId = $this->getRequest()->getParam('brick-id');
    	$brick = App_Factory::_m('Brick')->find($brickId);
    	if(is_null($brick)) {
    		throw new Exception('brick not found');
    	}
		$brick->delete();
    	$this->_helper->switchContent->gotoSimple('index', null, null, array(), true);
    }
    
    public function editTplAction()
    {
    	$brickId = $this->getRequest()->getParam('brick-id');
    	$tplName = $this->getRequest()->getParam('tpl-name');
    	
    	$brick = App_Factory::_m('Brick')->find($brickId);
    	if(is_null($brick)) {
    		throw new Exception('Brick not found by id :'.$brickId);
    	}
    	$extName = substr($brick->extName, 6);
    	$solidBrick = $brick->createSolidBrick($this->getRequest());
    	
    	require(APP_PATH.'/admin/forms/Brick/EditTpl.php');
    	$form = new Form_Brick_EditTpl();
    	$fileFolder = TEMPLATE_PATH.'/Front_'.$extName;
    	
    	if(!empty($tplName)) {
    		$filePath = $fileFolder.'/'.$tplName;
			$fh = fopen($filePath, 'r');
			$tplFileData = fread($fh, filesize($filePath));
			fclose($fh);
			$form->tplFileName->setValue($tplName);
			$form->tplFileContent->setValue($tplFileData);
    	}
    	if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
    		$siteInfo = Zend_Registry::get('siteInfo');
    		$siteId = $siteInfo['id'];
    		
    		if(!is_dir($fileFolder)) {
    			mkdir($fileFolder);
    		}
    		$filePath = $fileFolder.'/'.$form->getValue('tplFileName');
    		$fh = fopen($filePath, 'w');
    		fwrite($fh, $form->getValue('tplFileContent'));
    		fclose($fh);
    		$this->_helper->switchContent->gotoSimple('edit', null, null, array('brick-id' => $brickId), true);
    	}
    	
    	$tplFile = CONTAINER_PATH.'/extension/brick/Front/'.$extName.'/view.tpl';
		$fh = fopen($tplFile, 'r');
		$viewFileData = fread($fh, filesize($tplFile));
		fclose($fh);
    	
		$this->view->brickId = $brickId;
    	$this->view->extName = $extName;
		$this->view->tplArray = $solidBrick->getTplArray();
    	$this->view->viewFileData = $viewFileData;
    	$this->view->form = $form;
    	$this->_helper->template->head('编辑<em>'.$extName.'</em>')
    		->actionMenu(array('save'));
    }
    
    public function getTplContentAjaxAction()
    {
    	$extName = $this->getRequest()->getParam('extName');
    	$tplName = $this->getRequest()->getParam('tplName');
    	
    	$tplFile = CONTAINER_PATH.'/extension/brick/Front/'.$extName.'/'.$tplName;
		$fh = fopen($tplFile, 'r');
		$tplFileData = fread($fh, filesize($tplFile));
		fclose($fh);
    	$this->view->tplFileData = $tplFileData;
    	$this->_helper->layout->disableLayout(); 
    }
    
    public function editCssAction()
    {
    	$brickId = $this->getRequest()->getParam('brick-id');
    	$db = Zend_Registry::get('db');
    	try {
    		$db->describeTable('css');
    	} catch(Exception $e) {
    		echo "<div style='padding: 25px;'>table not implemented!</div>";
    		exit(0);
    	}
    	$brick = Class_Base::_('Brick')->find($brickId)->current();
    	if(is_null($brick)) {
    		throw new Exception('Brick not found');
    	}
    	$extName = substr($brick->extName, 6);
    	$cssSuffix = empty($brick->cssSuffix) ? '' : '-'.$brick->cssSuffix;
    	$cssName = 'brick-'.strtolower($extName).$cssSuffix;
    	
    	$tb = new Zend_Db_Table('css');
    	$row = $tb->fetchRow($tb->select()->where('id = ?', $cssName));
    	
    	require_once APP_PATH.'/admin/forms/Brick/EditCss.php';
    	$form = new Form_Brick_EditCss();
    	if(is_null($row)) {
	    	$idField = $form->getElement('id');
	    	$idField->setValue($cssName);
	    	$row = $tb->createRow();
	    	$row->type = 'brick';
    	} else {
    		$form->populate($row->toArray());
    	}
    	if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
    		$row->setFromArray($form->getValues());
    		$row->inFile = false;
    		$row->save();
    	}
    	$this->view->form = $form;
    	$this->view->controls = array(
			'ajax-save' => array('callback' => '/admin/brick/edit-css/brick-id/'.$brickId)
        );
    }
    
    public function saveLocationJsonAction()
    {
    	$layoutId = $this->getRequest()->getParam('layoutId');
    	$stageId = $this->getRequest()->getParam('stageId');
    	$brickId = $this->getRequest()->getParam('brickId');
    	$spriteName = $this->getRequest()->getParam('spriteName');
    	
    	$tb = new Zend_Db_Table('layout_stage_brick');
    	$row = $tb->createRow();
    	$row->layoutId = $layoutId;
    	$row->stageId = $stageId;
    	$row->brickId = $brickId;
    	$row->spriteName = $spriteName;
    	
    	$row->save();
    	
    	$this->_helper->json(array('result' => 'success'));
    }
    
    public function getBrickJsonAction()
    {
        $pageSize = 30;
        $type = $this->getRequest()->getParam('type');
        
	    $tb = Class_Base::_('Brick');
	    $selector = $tb->select(false)->setIntegrityCheck(false)
	    	->from($tb, array('brickId', 'extName', 'brickName', 'cssSuffix', 'tplName'))
	    	->joinLeft(array('l' => 'layout'), 'l.id = brick.layoutId', array('l.label'))
	    	->order('l.id')
	    	->limitPage(1, $pageSize);
		if(!is_null($type)) {
		    $selector->where('type = ?', $type);
		}
		
        $result = array();
        
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                	case 'label':
                		$selector->where('label like ?', $value.'%');
                		break;
                	case 'extName':
                        $selector->where('extName like ?', '%'.$value.'%');
                        break;
                    case 'className':
                        $selector->where('className like ?', '%'.$value.'%');
                        break;
            		case 'brickName':
            		    $selector->where('brickName like ?', '%'.$value.'%');
            		    break;
            		case 'type':
            		    $selector->where('type = ?', $value);
            		    break;
            		case 'tplName':
            			$selector->where('tplName like ?', $value.'%');
            		    break;
            		case 'page':
            		    $selector->limitPage(intval($value), $pageSize);
                        $result['currentPage'] = intval($value);
            		    break;
            		case 'selectedIds':
					    if($value != 'all') {
					        $selector->where('brickId in (?)', explode(',', $value));
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