<?php
class Admin_LayoutPositionController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$labels = array(
            'position' => '区域',
            'label' => '标题',
			'sort' => '排序',
			'~contextMenu' => ''
        );
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header-front.phtml', array(
            'labels' => $labels,
            'url' => '/admin/layout-position/get-position-json/',
            'actionId' => 'id',
        	'click' => array(
            	'action' => 'contextMenu',
            	'menuItems' => array(
            		array('编辑', '/admin/layout-position/edit/id/')
            	)
            ),
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));

        $this->view->assign('partialHTML', $partialHTML);
        $this->_helper->template->actionMenu(array('create'))
        	->head('页面区域调整');
	}
	
	public function createAction()
	{
		require_once APP_PATH.'/admin/forms/Layout/Position/Edit.php';
		$form = new Form_Layout_Position_Edit();
		
		$tb = Class_Base::_('Layout_Position');
		$row = $tb->createRow();
		
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();
			try {
				$positionRowset = $tb->fetchAll(
					$tb->select(false)->from($tb, array('id', 'sort'))
						->order('sort')
				);
				$placeToBe = $form->getValue('sort');
				$placeToBe++;
				
				foreach($positionRowset as $positionRow) {
					if($positionRow->sort >= $placeToBe) {
						$positionRow->sort +=  1;
						$positionRow->save();
					}
				}
				
				$row->setFromArray($form->getValues());
				$row->sort = $placeToBe;
				$row->save();
				$db->commit();
				$this->_helper->flashMessenger->addMessage('新的页面区域已保存！');
				$this->_helper->redirector->gotoSimple('index');
			} catch (Exception $e) {
				$db->rollback();
				throw $e;
			}
		}
		
		$this->view->form = $form;
		$this->_helper->template->actionMenu(array('save'))->head('新增页面区域');
	}
	
	public function editAction()
	{
		require_once APP_PATH.'/admin/forms/Layout/Position/Edit.php';
		$form = new Form_Layout_Position_Edit();
		
		$id = $this->getRequest()->getParam('id');
		$tb = Class_Base::_('Layout_Position');
		$row = $tb->find($id)->current();
		
		$form->populate($row->toArray());
		if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();
			try {
				if($form->getValue('sort') !== $row->sort) {
					$positionRowset = $tb->fetchAll(
						$tb->select(false)->from($tb, array('id', 'sort'))
							->order('sort')
					);
					$placeToBe = $form->getValue('sort');
					if($placeToBe < $row->sort) {
						$placeToBe++;
						//item moved up
						foreach($positionRowset as $positionRow) {
							if($positionRow->sort >= $placeToBe && $positionRow->sort < $row->sort) {
								$positionRow->sort +=  1;
								$positionRow->save();
							}
						}
					} else {
						//item moved down
						foreach($positionRowset as $positionRow) {
							if($positionRow->sort <= $placeToBe && $positionRow->sort > $row->sort) {
								$positionRow->sort -=  1;
								$positionRow->save();
							}
						}
					}
				}
				$row->setFromArray($form->getValues());
//				$row->sort = $placeToBe;
				$row->save();
				$this->_helper->flashMessenger->addMessage('新的区域已保存成功！');
				$this->_helper->redirector->gotoSimple('index');
			} catch (Exception $e) {
				$db->rollback();
				throw $e;
			}
		}
		
		$this->view->form = $form;
		
		$this->_helper->template->head('页面区域修改');
		$this->_helper->template->actionMenu(array('save'));
	}
	
	public function saveStageJsonAction()
	{
		$jsonString = $this->getRequest()->getParam('jsonString');
		$jsonObj = Zend_Json::decode($jsonString);
		$layoutId = $jsonObj['layoutId'];
		$stagesObj = $jsonObj['stages'];
		
		$table = new Zend_Db_Table('layout_stage');
		$rowset = $table->fetchAll($table->select()->where('layoutId = ?', $layoutId));
		$currentStageIds = array();
		
		foreach($stagesObj as $obj) {
			if($obj['stageId'] == 0) {
				$row = $table->createRow();
				$row->layoutId = $layoutId;
				$row->type = $obj['type'];
				$row->sort = $obj['sort'];
				$row->save();
			} else {
				$currentStageIds[] = $obj['stageId'];
				foreach($rowset as $key => $row) {
					if($row->id == $obj['stageId'] && $row->sort != $obj['sort']) {
						$row->sort = $obj['sort'];
						$row->save();
					}
				}
			}
		}
		foreach($rowset as $row) {
			if(!in_array($row->id, $currentStageIds)) {
				$row->delete();
			}
		}
		return $this->_helper->json(array('result' => 'success'));
	}
	
	public function getPositionJsonAction()
    {
    	$pageSize = 20;
		$currentPage = 1;
		
		$articleCo = App_Factory::_m('Article');
		$articleCo->setFields(array('id', 'label', 'groupId', 'link', 'featured'));
		$queryArray = array();
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                	case 'label':
                		$articleCo->addFilter('label', new MongoRegex("/^".$value."/"));
                		break;
                	case 'groupId':
                		$articleCo->addFilter('groupId', $value);
                		break;
                	case 'link':
                		$articleCo->addFilter('link', $value);
                		break;
                	case 'featured':
                		$articleCo->addFilter('featured', $value);
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
        $articleCo->sort('_id', -1);
        
		$articleCo->setPage($currentPage)->setPageSize($pageSize);
		$data = $articleCo->fetchAll(true);
		$dataSize = $articleCo->count();
		
		$result['data'] = $data;
        $result['dataSize'] = $dataSize;
        $result['pageSize'] = $pageSize;
        $result['currentPage'] = $currentPage;
        
        return $this->_helper->json($result);
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
        $pageSize = 30;
        
	    $tb = Class_Base::_('Layout_Position');
	    $selector = $tb->select()->limitPage(1, $pageSize)
	    	->order('sort');
		
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
        $result['pageSize'] = $pageSize;
        
        return $this->_helper->json($result);
    }
}