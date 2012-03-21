<?php
class Admin_CustomerController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$labels = array(
            'id' => 'ID',
            'name' => '用户名',
		    'email' => '注册邮箱',
		    'registerDate' => '注册日期',
		    'lastVisitDate' => '最后登录日期',
        );
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header.phtml', array(
            'labels' => $labels,
        	'selectFields' => array(
                'id' => null
            ),
            'url' => '/admin/customer/get-customer-json/',
            'actionId' => 'id',
        	'doubleClickAction' => 'goto',
            'doubleClickHref' => '/admin/customer/edit/id/',
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));

        $this->view->assign('partialHTML', $partialHTML);
	}
	
	public function getCustomerJsonAction()
	{
	    $pageSize = 30;
        
	    $table = Class_Base::_('Customer');
	    $selector = $table->select()->order('id DESC')
	        ->limitPage(1, $pageSize);
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                    case 'email':
                        $selector->where('email like ?', '%'.$value.'%');
                        break;
                    case 'sendEmail':
                        $selector->where('email = '.$value);
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
        $siteRowset = $table->fetchAll($selector)->toArray();
        $result['data'] = $siteRowset;
        $result['pageSize'] = $pageSize;
        
        return $this->_helper->json($result);
	}
}