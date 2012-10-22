<?php
class Form_AttributeSet extends Class_Form
{
    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('text', 'name', array(
            'label' => '属性组名字',
            'required' => true,
            'filters' => array('StringTrim')
        ));
        
        $this->addElement('text', 'autoSku', array(
            'label' => '属性组 AUTO-SKU',
            'allowEmpty' => true,
        	'filters' => array('StringTrim')
        ));
        
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'subform'))
        ));   
    }
}