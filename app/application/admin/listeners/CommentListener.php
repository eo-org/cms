<?php
class CommentListener
{
	public function getMainNavi($controllerName)
	{
		$naviItem = array();
		$naviItem['artical'] = array(
			'label' => '文章',
			'url' => '/admin/artical/index',
			'controllerName' => 'artical'
		);
		$naviItem['group'] = array(
			'label' => '文章',
			'url' => '/admin/artical/index',
			'controllerName' => 'artical'
		);
		if(array_key_exists($controllerName, $naviItem)) {
			return $naviItem[$controllerName];
		} else {
			return null;
		}
	}
}