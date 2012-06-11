<?php
class Front_News extends Class_Brick_Solid_Abstract
{
	protected $_id = null;
	
	protected function _prepareGearLinks()
	{
		if($this->_id == null) {
			return parent::_prepareGearLinks();
		} else {
			$this->_addGearLink('添加新文章', '/admin/artical/edit/groupId/'.$this->_id);
		}
	}
	
	public function getCacheId()
	{
		$groupId = $this->getParam('groupId');
		return "news_".$this->_brick->brickId;
	}
	
    public function prepare()
    {
        $groupId = $this->getParam('groupId');
    	if($groupId == 'auto') {
    		$groupId = $this->_request->getParam('action');
    	}
    	if(is_null($groupId)) {
    		$groupId = 0;
    	} else {
    		$this->_id = $groupId;
    	}
    	
		$groupTb = Class_Base::_('Group');
		$groupRow = $groupTb->find($groupId)->current();
		
		$co = App_Factory::_m('Article');
//        $selector = $articalTable->select(false)->setIntegrityCheck(false)
//        	->from($articalTable, array('id', 'groupId', 'title', 'introtext', 'introicon', 'created', 'modified', 'featured'))
//        	->limit($this->getParam('limit'), 0);
		$co->setFields(array('id', 'groupId', 'label', 'introtext', 'introicon', 'created', 'modified', 'featured'))
			->setPagesize($this->getParam('limit'))
			->setPage(1)
			->sort('_id', -1);
//		$featuredOnly = $this->getParam('featuredOnly');
//		if($featuredOnly == 'y') {
//			$selector->where('featured = 1');
//		} else if($featuredOnly == 'top') {
//			$selector->order('featured DESC');
//		}
//		$selector->order('id DESC');
//		echo $groupId.'<br />';
    	if(!is_null($groupRow)) {
			if($groupRow->hasChildren == 1) {
				$subgroupRowset = $groupTb->fetchAll($groupTb->select(false)
					->from($groupTb, array('id'))
					->where('parentId = ?', $groupRow->id)
				);
				$subgroupIdArr = Class_Func::buildArr($subgroupRowset, 'id', 'id');
				$subgroupIdArr[] = $groupId;
				$co->addFilter('groupId', array('$in' => $subgroupIdArr));
			} else {
				$co->addFilter('groupId', $groupRow->id);
			}
    	} else {
    		$this->setParam('header', 'none');
    	}
    	
//    	if($this->getParam('titlePrefix') == 'group') {
//    		$selector->joinLeft(
//    			array('g' => 'group'),
//    			'artical.groupId = g.id',
//    			array('g.label')
//    		);
//    	} else if($this->getParam('titlePrefix') == 'subdomain') {
//    		$selector->joinLeft(
//    			array('s' => 'subdomain'),
//    			'artical.subdomainId = s.id',
//    			array('s.label', 'subdomainName' => 's.name')
//    		);
//    	}
    	$articalRowset = $co->fetchDoc();
		
    	$this->view->groupId = $groupId;
		$this->view->groupRow = $groupRow;
		$this->view->articalRowset = $articalRowset;
    }
    
    public function configParam($form)
    {
    	$table = new Class_Model_GroupV2_Tb();
    	$selector = $table->select()->where('type = ?', 'article');
        $items = $table->fetchSelectOption(
        	array('auto' => '[自动判断]',  '' => '全部文章', '--------------' => array()),
        	$selector
        );
        $form->addElement('select', 'param_groupId', array(
            'label' => '文章分类',
            'multiOptions' => $items
        ));
        $form->addElement('select', 'param_showGroupIcon', array(
            'filters' => array('StringTrim'),
            'label' => '显示分组摘要图片：',
        	'multiOptions' => array(
        		'n' => '不显示',
        		'y' => '显示'
       		),
            'required' => false
        ));
        $form->addElement('select', 'param_showContent', array(
            'filters' => array('StringTrim'),
            'label' => '显示文章摘要：',
        	'multiOptions' => array(
        		'none' => '不显示',
        		'one' => '显示首条信息摘要',
        		'all' => '显示全部信息摘要'
       		),
            'required' => false
        ));
        $form->addElement('select', 'param_showIcon', array(
            'filters' => array('StringTrim'),
            'label' => '显示摘要图片：',
        	'multiOptions' => array(
        		'none' => '不显示',
        		'one' => '显示首条摘要图片',
        		'all' => '显示全部摘要图片'
       		),
            'required' => false
        ));
        $form->addElement('select', 'param_featuredOnly', array(
            'filters' => array('StringTrim'),
            'label' => '显示精选：',
        	'multiOptions' => array('n' => '否', 'y' => '是', 'top' => '精选置顶'),
            'required' => false
        ));
        $form->addElement('select', 'param_titlePrefix', array(
            'filters' => array('StringTrim'),
            'label' => '文章名前缀：',
        	'multiOptions' => array('none' => '不显示', 'group' => '文章分组', 'subdomain' => '子域名'),
            'required' => false
        ));
        $form->addElement('select', 'param_limit', array(
            'filters' => array('StringTrim'),
            'label' => '显示新闻数量：',
        	'multiOptions' => array(
        		4 => 4,
        		5 => 5,
        		6 => 6,
        		7 => 7,
        		8 => 8,
        		9 => 9,
        		10 => 10,
        		11 => 11,
        		12 => 12,
        		13 => 13,
        		14 => 14,
        		15 => 15,
        		16 => 16),
            'required' => false
        ));
    	$form->addElement('select', 'param_longCharCount', array(
            'filters' => array('StringTrim'),
            'label' => '单条新闻字数：',
    		'multiOptions' => array(
    			11 => 11,
    			12 => 12,
    			13 => 13,
    			14 => 14,
    			15 => 15,
    			17 => 17,
    			19 => 19,
    			21 => 21,
    			23 => 23,
    			30 => 30,
    			35 => 35,
    			40 => 40,
    			45 => 45,
    			50 => 50),
            'required' => false
        ));
        $form->addElement('select', 'param_firstCharCount', array(
            'filters' => array('StringTrim'),
            'label' => '首条新闻字数：',
    		'multiOptions' => array(
    			11 => 11,
    			12 => 12,
    			13 => 13,
    			14 => 14,
    			15 => 15,
    			17 => 17,
    			19 => 19,
    			21 => 21,
    			23 => 23,
    			30 => 30,
    			35 => 35,
    			40 => 40,
    			45 => 45,
    			50 => 50),
            'required' => false
        ));
        $form->addElement('select', 'param_shortItems', array(
            'filters' => array('StringTrim'),
            'label' => '图片右侧新闻条数：',
        	'multiOptions' => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
            'required' => false
        ));
        $form->addElement('select', 'param_shortCharCount', array(
            'filters' => array('StringTrim'),
            'label' => '图片右侧新闻字数：',
        	'multiOptions' => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
            'required' => false
        ));
        $form->addElement('select', 'param_created', array(
            'filters' => array('StringTrim'),
            'label' => '显示日期：',
        	'multiOptions' => array('n' => '不显示', 'ymd' => '年月日', 'md' => '月日'),
            'required' => false
        ));
        $form->addElement('select', 'param_moreLink', array(
            'filters' => array('StringTrim'),
            'label' => '更多连接：',
        	'multiOptions' => array(1 => 'MORE', 2 => '更多', 3 => 'MORE++', 4 => '更多++', 5 => '仅显示DIV', 'none' => '不显示'),
            'required' => false
        ));
    	$paramArr = array('param_groupId',
    		'param_showGroupIcon',
    		'param_showContent',
    		'param_showIcon',
    		'param_featuredOnly',
    		'param_titlePrefix',
    		'param_limit',
    		'param_longCharCount',
    		'param_firstCharCount',
    		'param_shortItems',
    		'param_shortCharCount',
    		'param_created',
    		'param_moreLink'
    	);
    	
    	$form->setParam($paramArr);
    	return $form;
    }
}