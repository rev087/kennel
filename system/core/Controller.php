<?php
	
	class Controller
	{
		static $_instances = array();
		
		function __construct()
		{
		}
		
		static function getInstance($descendent)
		{
			if(!array_key_exists($descendent, self::$_instances))
				self::$_instances[$descendent] = new $descendent;
			return self::$_instances[$descendent];
		}
		
		function notfound()
		{
			 header("Status: 404 Not Found");
		}
		
	}
	
?>
