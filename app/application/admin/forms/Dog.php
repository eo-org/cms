<?php
class Form_Dog extends Zend_Form
{
    
    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('text', 'name', array(
            'label' => '名称',
            'required' => true,
            'filters' => array('StringTrim')
        ));
                
        $this->addElement('select', 'type', array(
            'label' => '类型',
            'required' => true,
            'filters' => array('StringTrim'),
            'multiOptions' => array(
                'working'=>'工作狗',
        		'non-sporting'=>'不运动',
        		'sporting'=>'运动',
        		'toy'=>'玩具',
        		'herding'=>'群居',
        		'hounds'=>'猎犬',
            )
        ));
        $this->addElement('text', 'weight', array(
            'label' => '体重',
            'required' => true,
            'filters' => array('StringTrim'),
        	'validators'=>array(array('stringlength',true,array(1,50))),
        	'attribs'=>array('id'=>'dog-form-weight-input')
        ));
        $this->addElement('text', 'height', array(
            'label' => '身高',
            'required' => true,
            'filters' => array('StringTrim'),
        	'validators'=>array(array('stringlength',true,array(1,50))),
        	'attribs'=>array('id'=>'dog-form-height-input')
        ));
        $this->addElement('text', 'colors', array(
            'label' => '颜色',
            'required' => true,
            'filters' => array('StringTrim'),
        	'validators'=>array(array('stringlength',true,array(1,100))),
        	'attribs'=>array('id'=>'dog-form-colors-input')
        ));
        $this->addElement('select','size',array(
        	'label'=>'大小规格参数（数字0-7）',
        	'required'=>true,
        	'filters'=>array('StringTrim','Digits'),
        	'multiOptions' => array(0 => 0, 1 => 1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7),
        	'validators'=>array(array('Between',true,array(0,7)))
        ));
        $this->addElement('select','groomingRequirements',array(
        	'label'=>'洗刷需要（数字0-7）',
        	'required'=>true,
        	'filters'=>array('StringTrim','Digits'),
        	'multiOptions' => array(0 => 0, 1 => 1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7),
        	'validators'=>array(array('Between',true,array(0,7)))
        ));
        $this->addElement('select','exerciseRequirements',array(
        	'label'=>'运动需要（数字0-7）',
        	'required'=>true,
        	'filters'=>array('StringTrim','Digits'),
        	'multiOptions' => array(0 => 0, 1 => 1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7),
        	'validators'=>array(array('Between',true,array(0,7)))
        ));
        $this->addElement('select','friendly',array(
        	'label'=>'友好度（数字0-7）',
        	'required'=>true,
        	'filters'=>array('StringTrim','Digits'),
        	'multiOptions' => array(0 => 0, 1 => 1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7),
        	'validators'=>array(array('Between',true,array(0,7)))
        ));
        $this->addElement('select','watchdogAbility',array(
        	'label'=>'看家能力（数字0-7）',
        	'required'=>true,
        	'filters'=>array('StringTrim','Digits'),
        	'multiOptions' => array(0 => 0, 1 => 1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7),
        	'validators'=>array(array('Between',true,array(0,7)))
        ));
        $this->addElement('textarea', 'description', array(
            'label' => '详细介绍',
            'required' => true,
            'filters' => array('StringTrim'),

        ));
        
        $this->addElement('select', 'active', array(
            'label' => '显示',
            'filters' => array('StringTrim'),
            'multiOptions' => array(1 => 'Y', 0 => 'N')
        ));
        $this->addElement('submit', 'submit', array(
            'label' => '保存 ',
        ));
    }
}