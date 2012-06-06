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
			 
			 $view = new View('404');
			 $view->render();
		}
		
		private function redirect($location, $status=303)
		{
		  switch ($status)
		  {
		    case 301: header("Status: 301 Moved Permanently");
		    case 303: header("Status: 303 See Other");
		  }
		  
		  $url = parse_url($location);
		  if ( !isset($scheme) )
  		  header("Location: " . url($location));
      else
  		  header("Location: " . $location);
  		
  		exit(0);
		}
		
	}
	
?>
