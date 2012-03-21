<?php
class Admin_CommentController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $labels = array(
            'id' => 'ID',
            'type'=>'类型',
        	'email' => '邮箱',
            'visible' => '显示'
        );
        $hashParam = $this->getRequest()->getParam('hashParam');
        $partialHTML = $this->view->partial('select-search-header.phtml', array(
            'labels' => $labels,
            'selectFields' => array(
                'id' => NULL,
				'type' => array('comment'=>'评论',
								'consult'=>'咨询'),
                'visible' => array(1=>'Y', 0=>'N'),
				'replied' => array(1=>'Y',0=>'N')
            ),
            'url' => '/admin/comment/get-comment-grid-json/',
            'actionId' => 'id',
        	'doubleClickAction' => 'goto',
            'doubleClickHref' => '/admin/comment/edit/id/',
            'initSelectRun' => 'true',
            'hashParam' => $hashParam
        ));

        $this->view->assign('partialHTML', $partialHTML);
	}
	
	public function editAction()
	{
		$commentId = $this->getRequest()->getParam('id');
		$tb = new Zend_Db_Table('comment');
		$row = $tb->find($commentId)->current();
		
		$this->view->row = $row;
	}
	
	public function deleteAction()
	{
		$commentId = $this->getRequest()->getParam('id');
		
		$tb = new Zend_Db_Table('comment');
		$row = $tb->find($commentId)->current();
		
		if($row == null){
			throw new Class_Exception_Pagemissing();
		}
//		$comment = Class_Core::_('comment')->setData('id',$commentId)
//			->load();
//
//		$type = $comment->getData('type');
//
//		if($type == 'consult'){
//			$replyList = Class_Core::_list('comment')->addFilter('parentId',$commentId)
//				->addFilter('type','reply')
//				->load()
//				->getListData();
//			$replyCount = count($replyList);
//			
//			if($replyCount >0){
//				foreach($replyList as $reply){
//					$reply->delete();
//				}
//			}
//		}
		$row->delete();
		
		$this->_helper->redirector->gotoSimple('index');
	}
	
	public function getCommentGridJsonAction()
    {
        $pageSize = 30;
        
        $tb = Class_Base::_('Comment');
	    $selector = $tb->select()->order('id DESC')
	        ->limitPage(1, $pageSize);
		
        $result = array();
        foreach($this->getRequest()->getParams() as $key => $value) {
            if(substr($key, 0 , 7) == 'filter_') {
                $field = substr($key, 7);
                switch($field) {
                    case 'page':
            			if(intval($value) == 0) {
            				$value = 1;
            			}
            		    $selector->limitPage(intval($value), $pageSize);
                        $result['currentPage'] = intval($value);
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