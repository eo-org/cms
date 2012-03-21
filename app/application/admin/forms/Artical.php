<?php
class Form_Artical extends Zend_Form
{
    
    public function init()
    {
        $this->setMethod('post');
            
        $this->addElement('select', 'type', array(
            'label' => '类型：',
            'required' => true,
            'filters' => array('StringTrim'),
            'multiOptions' => array(
                ''=>'',
            	'news'=>'新闻',
        		'blog'=>'博客',
            )
        ));
        $this->addElement('select', 'category', array(
            'label' => '分类：',
            'required' => true,
            'filters' => array('StringTrim'),
            'multiOptions' => array(
        		''=>'',
                '活动'=>'活动',
        		'媒体'=>'媒体',
        		'网站'=>'网站',
            )
        ));
        $this->addElement('textarea', 'title', array(
            'label' => '标题：',
            'required' => true,
            'filters' => array('StringTrim'),
        	
        ));
        $stringlengthValidator = new Zend_Validate_StringLength(1,100,'utf-8');
        $stringlengthValidator->setMessages(array(
        	Zend_Validate_StringLength::INVALID => '标题必须是字符串！',
	        Zend_Validate_StringLength::TOO_SHORT => '标题太短了！',
	        Zend_Validate_StringLength::TOO_LONG => '标题太长了，不能超过100个字！',
        ));
       	$this->title->addValidator($stringlengthValidator);
       	
        $this->addElement('textarea', 'content', array(
            'label' => '内容：',
            'required' => true,
            'filters' => array('StringTrim'),
        ));
       
    }
}