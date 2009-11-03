<?php
	class Assets_controller extends Controller{
		
		function __construct()
		{
			parent::__construct();
		}
		
		function index($type)
		{
				print $type;
		}
		
		function flash($file)
		{
			
		}
		
		function css($file)
		{
			header("Content-type: text/css");
			$target = Kennel::$app_root_path . self::_cascade($file, 'css');
			include($target);
		}
		
		function notfound($type) {
			$args = func_get_args();
			$file = array_slice($args, 1);
			$file = join('/', $file);
			
			$target = Kennel::$app_root_uri . self::_cascade($file,$type);
			header("location: $target");
		}
		
	}
?>
