<?php
	
	class Controller
	{
		var $input;
		var $request;
		static $_instances = array();
		
		function __construct()
		{
			$this->input = new Input();
			$this->request = new Request();
		}
		
		static function getInstance($descendent)
		{
			if(!array_key_exists($descendent, self::$_instances))
				self::$_instances[$descendent] = new $descendent;
			return self::$_instances[$descendent];
		}
		
	}
	
?>
