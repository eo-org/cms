<?php
class Front_Form extends Class_Brick_Solid_Abstract
{
    public function prepare()
    {
    	$formName = $this->getParam('formName');
    	if(empty($formName) || $formName == 'auto') {
    		$formName = Class_Layout_Front::getInstance()->getPageAlias();
    	}
    	
    	$entity = Class_Base::_('Message')->createRow();
    	$attributesetId = $entity->setAttributesetName($formName);
    	$entity->setOwnerLabel($this->getParam('ownerLabel'));
    	$entity->setOwnerValidator($this->getParam('ownerValidator'));
    	
    	$form = $entity->getForm();
    	$msgArr = array();
    	if($this->_request->isPost()) {
    		if($form->isValid($this->_request->getParams())) {
    			$msgArr[] = $this->getParam('successMsg', '保存成功');
    			$entity->setFromArray($form->getValues())
    				->setAttributeValue($form->getValues());
    			$entity->save();
    			$form->reset();
    		} else {
    			$msgArr[] = '您填写的内容有误';
    		}
    	}
    	$this->view->msgArr = $msgArr;
		$this->view->form = $form;
		$this->view->title = $this->_brick->brickName;
    }
    
    public function configParam(Class_Form_Edit $form)
    {
    	$tb = new Zend_Db_Table('eav_attribute_set');
    	$rowset = $tb->fetchAll($tb->select(false)->from($tb, array('name', 'label')));
    	$optArr = array('auto' => '根据URL自动选择');
    	foreach($rowset as $row) {
    		$optArr[$row->name] = $row->label; 
    	}
    	$form->addElement('select', 'param_formName', array(
            'label' => '表单选择：',
    		'multiOptions' => $optArr,
            'required' => false
        ));
    	$form->addElement('text', 'param_ownerLabel', array(
            'label' => '发布人称呼：',
            'required' => false
        ));
        $form->addElement('select', 'param_ownerValidator', array(
            'label' => '发布人校验：',
        	'multiOptions' => array(
        		'none' => '无校验',
        		'email' => '邮箱',
        		'phone' => '手机号'
        	),
            'required' => false
        ));
    	$form->addElement('text', 'param_successMsg', array(
            'label' => '保存成功消息：',
            'required' => false
        ));
        $form->addElement('select', 'param_afterSave', array(
            'label' => '消息保存成功后：',
        	'multiOptions' => array(
        		'newForm' => '显示空表单',
        		'none' => '仅显示成功消息'
        	),
            'required' => false
        ));
        
        $paramArr = array('param_formName', 'param_ownerLabel', 'param_ownerValidator', 'param_successMsg', 'param_afterSave');
        $form->setParam($paramArr);
        return $form;
    }
}