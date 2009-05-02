<?php
	class Assets_controller extends Controller{
		
		function __construct()
		{
			parent::__construct();
		}
		
		function index($type)
		{
		}
		
		function css($file)
		{
			header("Content-type: text/css");
			$target = Vault::$app_root_path . Vault::getSetting('path', 'assets') . "/css/{$file}";
			include($target);
		}
		
		function notfound($type) {
			$args = func_get_args();
			$file = array_slice($args, 1);
			$file = join('/', $file);
			
			$target = Vault::$app_root_uri . Vault::getSetting('path', 'assets') . "/{$type}/{$file}";
			header("location: $target");
		}
		
	}
?>
