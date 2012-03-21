<?php
class Admin_AdminController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$labels = array(
			'loginName' => '登陆ID',
			'lastLogin' => '最近登陆',
			'roleId' => '管理员权限',
			'~contextMenu' => ''
        );
        $tb = new Zend_Db_Table('admin_group');
        $rowset = $tb->fetchAll();
        
        $rowsetArr = Class_Func::buildArr($rowset, 'id', 'name');
        
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header-front.phtml', array(
            'labels' => $labels,
        	'selectFields' => array('roleId' => $rowsetArr),
            'url' => '/admin/admin/get-admin-list-json/',
            'actionId' => 'id',
        	'click' => array(
            	'action' => 'contextMenu',
            	'menuItems' => array(
            		array('编辑', '/admin/admin/edit/id/')
            	)
            ),
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));

        $this->view->assign('partialHTML', $partialHTML);
        $this->_helper->template->actionMenu(array('create'));
	}
	
	public function createAction()
	{
		require_once(APP_PATH.'/admin/forms/Admin/Edit.php');
		$form = new Form_Admin_Edit();
		$roleRowset = Class_Base::_('Admin_Group')->fetchAll();
		$roleRowsetArr = Class_Func::buildArr($roleRowset, 'id', 'name');
		$form->addElement('select', 'roleId', array(
		    'label' => '分配权限',
		    'required' => true,
		    'multiOptions' => $roleRowsetArr
		));
		if($this->getRequest()->isPost()){
			$posts = $this->getRequest()->getPost();
			if($form->isValid($posts)){
				$admin = Class_Base::_('Admin')->createRow()
					->setFromArray($posts);
				$admin->password = md5('000000'.MD5_SALT);
				$admin->save();
				$this->_helper->flashMessenger->addMessage('新管理员: <em>'.$admin->loginName.'</em> 已经保存！');
				$this->_helper->switchContent->gotoSimple('index');
			}
		}
		$this->view->form = $form;
		$this->_helper->template->actionMenu(array('save'))
			->head('新增管理员');
	}
	
	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$adminTb = Class_Base::_('Admin');
		$adminRow = $adminTb->find($id)->current();
		
		require_once(APP_PATH.'/admin/forms/Admin/Edit.php');
		$form = new Form_Admin_Edit();
		$roleRowset = Class_Base::_('Admin_Group')->fetchAll();
		$roleRowsetArr = Class_Func::buildArr($roleRowset, 'id', 'name');
		$form->addElement('select', 'roleId', array(
		    'label' => '分配权限',
		    'required' => true,
		    'multiOptions' => $roleRowsetArr
		));
		$form->populate($adminRow->toArray());
		if($this->getRequest()->isPost()){
			$posts = $this->getRequest()->getPost();
			if($form->isValid($posts)){
				$adminRow->setFromArray($posts);
				$adminRow->save();
				$this->_helper->flashMessenger->addMessage('管理员: <em>'.$adminRow->loginName.'</em> 已保存修改！');
				$this->_helper->switchContent->gotoSimple('index');
			}
		}
		$this->view->form = $form;
		$this->_helper->template->actionMenu(array('save', 'delete'))
			->head('修改管理员');
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$adminTb = Class_Base::_('Admin');
		$adminRow = $adminTb->find($id)->current();
		
		$adminRow->delete();
		
		$this->_helper->flashMessenger->addMessage('管理员: <em>'.$adminRow->loginName.'</em> 已删除！');
		$this->_helper->switchContent->gotoSimple('index');
	}
	
	public function changePasswordAction()
	{
		$id = $this->getRequest()->getParam('id');
		
		require_once(APP_PATH.'/admin/forms/Password.php');
		$form = new Form_Password();
		
		$admin = Class_Core::_('Admin')
			->setData('id',$id)
			->load();
		if($this->getRequest()->isPost()){
			$posts = $this->getRequest()->getPost();
			if($form->isValid($posts)){
				if( md5($posts['password_old'].MD5_SALT) == $admin->getData('password')){
					$admin->setData('password', md5($posts['password'].MD5_SALT))
						->save();
					if(Class_Admin::getData('nickname') == $admin->getData('nickname')){
						Class_Admin::logout();
					}
					$this->_redirector->gotoSimple('index','admin','admin');
				}
			}
		}
		$this->view->user = $admin;
		$this->view->form = $form;
	}
	
	public function resetPasswordToDefaultAction()
	{
		$id = $this->getRequest()->getParam('id');
		
		$admin = Class_Core::_('Admin')
			->setData('id',$id)
			->load()
			->setData('password', md5("000000".MD5_SALT))
			->save();
		if(Class_Admin::getData('nickname') == $admin->getData('nickname')){
			Class_Admin::logout();
		}
		$this->_redirector->gotoSimple('index','admin','admin');
	}
	
	public function getAdminListJsonAction()
	{
		$pageSize = 20;
        
	    $tb = Class_Base::_('Admin');
	    $selector = $tb->select()
	    	->limitPage(1, $pageSize);
        $result = array();
        
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
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