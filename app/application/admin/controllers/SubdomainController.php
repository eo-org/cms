<?php
class Admin_SubdomainController extends Zend_Controller_Action 
{
	public function indexAction()
    {
        $labels = array(
            'id' => 'ID',
            'name' => '子域名',
            'label' => '子站点名',
            'active' => '状态' 
        );
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header.phtml', array(
            'labels' => $labels,
        	'selectFields' => array(
                'id' => null,
                'active' => array(0 => '停用', 1 => '使用中')
            ),
            'url' => '/admin/subdomain/get-subdomain-json/',
            'actionId' => 'id',
        	'doubleClickAction' => 'goto',
            'doubleClickHref' => '/admin/subdomain/edit/id/',
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));

        $this->view->partialHTML = $partialHTML;
    }
    
    public function createAction()
    {
        $this->_forward('edit');
    }
    
    public function editAction()
    {
        require APP_PATH.'/admin/forms/Subdomain/Edit.php';
        $form = new Form_Edit();
                
        $id = $this->getRequest()->getParam('id');
        $table = Class_Base::_('Subdomain');
        $row = null;
        if(is_null($id)) {
            $row = $table->createRow();
        } else {
            $row = $table->fetchRow(array(
                $table->getAdapter()->quoteInto('id = ?', $id)
            ));
            $form->populate($row->toArray());
        }
        if(is_null($row)) {
            throw new Class_Exception_AccessDeny('子站点不存在');
        }
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
            $row->setFromArray($form->getValues());
            $row->save();
            $this->_helper->redirector->gotoSimple('index', null, null, array('id' => $row->id));
        }
        
        $this->view->form = $form;
    }
    
    public function getSubdomainJsonAction()
    {
        $pageSize = 30;
        
	    $table = Class_Base::_('Subdomain');
	    $selector = $table->select()->order('id DESC')
	        ->limitPage(1, $pageSize);;
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                    case 'name':
                        $selector->where('name = ?', $value);
                        break;
                    case 'label':
                        $selector->where('label = ?', $value);
                        break;
                    case 'active':
                    	$selector->where('active = ?', $value);
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
        $rowset = $table->fetchAll($selector)->toArray();
        $result['data'] = $rowset;
        $result['dataSize'] = Class_Func::count($selector);
        $result['pageSize'] = $pageSize;
        
        if(empty($result['currentPage'])) {
        	$result['currentPage'] = 1;
        }
        return $this->_helper->json($result);
    }
}