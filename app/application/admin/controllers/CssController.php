<?php
class Admin_CssController extends Zend_Controller_Action
{
	public function indexAction()
	{

	}

	public function editAction()
	{
		$this->view->headLink()->appendStylesheet(Class_Server::extUrl().'/codemirror/cm.css');
		$this->view->headScript()->appendFile(Class_Server::extUrl().'/codemirror/cm.js');
		$this->view->headScript()->appendFile(Class_Server::extUrl().'/codemirror/mode/css.js');
		
		require APP_PATH.'/admin/forms/Css/Edit.php';
		$form = new Form_Css_Edit();
		if($this->getRequest()->isPost()) {
			
		}
		
		$this->view->form = $form;
		
		$this->_helper->template->head('编辑CSS')
        	->actionMenu(array('save', 'delete'));
	}

	public function deleteAction()
	{
		 
	}
}