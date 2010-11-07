<?php
	error_reporting(E_ALL);
	require_once('system/core/Core.php');
	require_once('system/core/Debug.php');
	require_once('system/core/Request.php');
	
	Request::hook(function($args) {
		if (count($args) > 0 && in_array($args[0], array('ksetup', 'cms'))) return $args;
		
		$cinema = array_shift($args);
		
		if (!cine::set($cinema))
		{
			Kennel::controllerAction('main', 'pick');
			exit();
		}
			
		return $args;
	});
	
	Kennel::init();
?>
