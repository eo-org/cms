<?php
class Front_Forum extends Class_Brick_Solid_Abstract
{
	public function prepare()
	{
		$this->view->orgCode = Class_Server::getOrgCode();
	}
}