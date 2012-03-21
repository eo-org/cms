<?php
class Form_DogGraphic extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        
//        $this->addElement('select', 'type', array(
//            'label' => '选图片类型',
//            'required' => true
//        ));
        
        
        $this->addElement('text', 'filename', array(
            'label' => '文件名',
            'id' => 'xFilePath',
            'filters' => array('StringTrim'),
            'required' => true
        ));
        
        $this->filename->addValidator('File_Exists', false, array(HTML_PATH));
        
        $this->addElement('button', 'browseserver', array(
            'label' => '查看文件',
            'onClick' => "BrowseServer();"
        ));
        
        $this->addElement('text', 'alt', array(
            'label' => '图片描述',
            'filters' => array('StringTrim')
        ));
        
//        $this->addElement('submit', 'submit', array(
//            'label' => "确认"
//        ));
    }
}