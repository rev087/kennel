<?php
	
	class Controller
	{
		static $_instances = array();
		
		static function getInstance($descendent)
		{
			if(!array_key_exists($descendent, self::$_instances))
				self::$_instances[$descendent] = new $descendent;
			return self::$_instances[$descendent];
		}
		
	}
	
?>
