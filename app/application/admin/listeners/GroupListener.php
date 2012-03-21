<?php
class GroupListener
{
	public function getMainNavi($controllerName)
	{
		$naviItem = array();
		$naviItem['artical'] = array(
			'label' => '文章分类',
			'url' => '/admin/group/index',
			'controllerName' => 'group'
		);
		$naviItem['group'] = array(
			'label' => '文章分类',
			'url' => '/admin/group/index',
			'controllerName' => 'group'
		);
		if(array_key_exists($controllerName, $naviItem)) {
			return $naviItem[$controllerName];
		} else {
			return null;
		}
	}
}