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
		
		function css($file)
		{
			header("Content-type: text/css");
			$target = Vault::$app_root_path . self::_cascade($file, 'css');
			include($target);
		}
		
		function notfound($type) {
			$args = func_get_args();
			$file = array_slice($args, 1);
			$file = join('/', $file);
			
			$target = Vault::$app_root_uri . self::_cascade($file,$type);
			var_dump($target);
			header("location: $target");
		}
		
		private function _cascade($filename, $type)
		{
			//1. User asset
			if (is_file(Vault::$app_root_path . Vault::getSetting('path', 'assets') . "/{$type}/{$filename}"))
			{
				return Vault::getSetting('path', 'assets') . "/{$type}/{$filename}";
			}
			
			//2. Model asset
			if (!Vault::$modules) Vault::fetchModules();
			foreach (Vault::$modules as $module)
			{
				if (is_file(Vault::$app_root_path . Vault::getSetting('path', 'modules') . "/{$module}/assets/{$type}/{$filename}"))
					return Vault::getSetting('path', 'modules') . "/{$module}/assets/{$type}/{$filename}";
			}
			
			//3. System asset
			if (is_file(Vault::$app_root_path . Vault::getSetting('path', 'system') . "/assets/{$type}/{$filename}"))
			{
					
				return Vault::getSetting('path', 'system') . "/assets/{$type}/{$filename}";
			}
		}
		
	}
?>
