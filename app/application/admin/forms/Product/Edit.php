<?php
class Form_Product_Edit extends App_Form_Edit
{
    public function init()
    {
    	$this->addElement('text', 'label', array(
            'label' => '产品名',
            'required' => true,
            'filters' => array('StringTrim')
        ));
    	
        $this->addElement('text', 'name', array(
            'label' => '产品型号',
            'required' => false,
            'filters' => array('StringTrim')
        ));
        
        $groupDoc = App_Factory::_m('Group')->addFilter('type', 'product')
    		->fetchOne();
    	$items = $groupDoc->toMultioptions('label');
		$this->addElement('select', 'groupId', array(
            'label' => '目录',
            'filters' => array('StringTrim'),
            'multiOptions' => $items
        ));
        
        $this->addElement('text', 'sku', array(
            'label' => '产品库存代码',
            'required' => false,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('stringLength', true, array(4, 20))
            )
        ));
        $this->addElement('textarea', 'fulltext', array(
            'label' => '产品内容',
            'required' => false,
            'id' => 'ck_text_editor'
        ));
        $this->addElement('button', 'appendImage', array(
            'filters' => array('StringTrim'),
            'label' => '插入图片',
            'required' => false,
        	'class' => 'icon-selector',
        	'callback' => 'appendToEditor'
        ));
        $this->addElement('text', 'price', array(
            'label' => '零售价',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('float')
            )
        ));
        
        $this->addElement('text', 'introicon', array(
            'label' => '产品图片',
            'required' => false,
            'filters' => array('StringTrim'),
        	'class' => 'icon-selector',
        	'callback' => 'appendToInput'
        ));
        $this->addElement('textarea', 'introtext', array(
            'label' => '产品摘要',
            'required' => false,
            'filters' => array('StringTrim'),
        	'style' => 'width: 280px; height: 80px;'
        ));
        $this->addElement('text', 'origPrice', array(
            'label' => '原价(不填或0表示没有)',
            'filters' => array('StringTrim'),
            'validators' => array(
                array('float')
            )
        ));
        
        $this->addElement('text', 'weight', array(
            'label' => '权重',
            'filters' => array('StringTrim'),
        	'validators' => array(
        		array('int'),
        		array('between', false, array('min' => -10000, 'max' => 10000))
        	),
        	'description' => '数字越小排序越靠前',
        	'value' => 1,
        	'required' => false
        ));
        
		$this->addElement('select', 'showWhere', array(
            'label' => '显示',
            'filters' => array('StringTrim'),
            'multiOptions' => array(0=>'不显示',1 => '只显示在产品列表', 2 => '只显示在搜索页', 3 => '同时显示')
        ));
        
        $this->_main = array('label', 'name', 'groupId', 'sku', 'fulltext', 'appendImage', 'price');
        $this->_optional = array('introicon', 'introtext', 'origPrice');
        $this->_param = array('weight', 'showWhere');
    }
}