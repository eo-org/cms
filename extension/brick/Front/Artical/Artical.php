<?php
class Front_Artical extends Class_Brick_Solid_Abstract
{
	protected $_id = null;
	
	protected function _prepareGearLinks()
	{
		if($this->_id == null) {
			return parent::_prepareGearLinks();
		} else {
			$this->_addGearLink('编辑文章内容', '/admin/artical/edit/id/'.$this->_id);
		}
	}
	
    public function prepare()
    {
    	$clf = Class_Layout_Front::getInstance();
    	$layoutType = $clf->getType();
    	$article = $clf->getResource();
    	$attachmentRowset = null;
    	
        if($layoutType == 'article' && $article != 'none') {
	        $title = $article->title;
	        if($this->getParam('showHits') == 'y') {
	        	$article->hits++;
	        	$article->save();
	        }
	        $aTb = new Zend_Db_Table('artical_attachment');
	        $attachmentRowset = $aTb->fetchAll($aTb->select()->where('articalId = ?', $article->id));
	        
	        $this->_id = $article->id;
        } else {
        	$title = '文章找不到';
        }
        if($this->getParam('pageUrl') == '') {
        	$view = new Zend_View();
        	$view->headTitle()->prepend($title);
        }
        $this->view->row = $article;
        $this->view->attachmentRowset = $attachmentRowset;
    }
    
    public function configParam(Class_Form_Edit $form)
    {
    	$showTitle = new Zend_Form_Element_Select('param_showTitle', array(
    		'filters' => array('StringTrim'),
    		'label' => '显示标题',
    		'multiOptions' => array('n' => '不显示', 'y' => '显示'),
    		'required' => false
    	));
        $form->addElement($showTitle);
    	
        $inDbValidator = new Zend_Validate_Db_RecordExists(array('table' => 'artical', 'field' => 'alias'));
        $pageUrl = new Zend_Form_Element_Text('param_pageUrl', array(
    		'filters' => array('StringTrim'),
    		'label' => '静态页面url',
    		'required' => false
    	));
    	$pageUrl->addValidator($inDbValidator);
        $form->addElement($pageUrl);
    	
        $paramArr = array('param_showTitle', 'param_pageUrl');
        $form->setParam($paramArr);
        return $form;
    }
}