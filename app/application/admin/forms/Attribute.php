<?php
class Form_Attribute extends Class_Form
{
    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('text', 'code', array(
            'label' => '属性编码（内部使用 [a-z][0-9] only）',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('regex', true, array('/^([a-z0-9]_?)+\z/i')),
                array('stringLength', true, array(5, 20)),
                array('db_norecordexists', true, array('eav_attribute', 'code'))
            )
        ));
        
        $this->addElement('select', 'inputType', array(
            'label' => '后台输入方式（内部使用）',
            'multiOptions' => array(
                'text' => '字符串',
                'textarea' => '文本',
                'select' => '下拉框',
                'multiSelect' => '多选下拉框',
                'datetime' => '日期时间'
            ),
            'value' => 'text'
        ));
        
//        $this->addElement('select', 'validator', array(
//            'label' => '后台输入效验（内部使用）',
//            'multiOptions' => array(
//                'NULL' => '无',
//                'Digits' => '全数字'
//            ),
//            'value' => 'text'
//        ));
        
        $this->addElement('select', 'isRequired', array(
            'label' => '是否必须（内部使用）',
            'multiOptions' => array(
                0 => '否',
                1 => '是'
            ),
            'value' => 'text'
        ));
        
        $this->addElement('text', 'name', array(
            'label' => '属性名（前台显示）',
            'required' => true,
            'filters' => array('StringTrim')
        ));
//        $this->addElement('checkbox', 'hasIcon', array(
//            'label' =>"选项图标"
//        ));
    
        $this->addElement('text', 'index', array(
            'label' => '排序',
            'required' => false,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('digits'),
                array('between', true, array(1, 100))
                
            )
        ));
        
//        $this->_setDisableArray(
//            array('code', 'inputType', 'isMultiple', 'isConfigurable')
//        );
        
        $this->_setDisableArray(
            array('code', 'inputType')
        );
    }
}