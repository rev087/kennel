<?php
	class assets
	{
		static function img($filename)
		{
			return self::_cascade($filename, 'img');
		}
		
		static function css($filename)
		{
			return self::_cascade($filename, 'css');
		}
		
		static function js($filename)
		{
			return self::_cascade($filename, 'js');
		}
		
		static function flash($filename)
		{
			return self::_cascade($filename, 'flash');
		}
		
		private function _cascade($filename, $type)
		{
			//1. User asset
			if (is_file(Kennel::$app_root_path . Kennel::getSetting('path', 'assets') . "/{$type}/{$filename}"))
				return Kennel::$app_root_uri . Kennel::getSetting('path', 'assets') . "/{$type}/{$filename}";
			
			//2. Module asset
			if (!Kennel::$modules) Kennel::fetchModules();
			foreach (Kennel::$modules as $module=>$info)
			{
				if (is_file(Kennel::$app_root_path . Kennel::getSetting('path', 'modules') . "/{$module}/assets/{$type}/{$filename}"))
					return Kennel::$app_root_uri . Kennel::getSetting('path', 'modules') . "/{$module}/assets/{$type}/{$filename}";
			}
			
			//3. System asset
			if (is_file(Kennel::$app_root_path . Kennel::getSetting('path', 'system') . "/assets/{$type}/{$filename}"))
				return Kennel::$app_root_uri . Kennel::getSetting('path', 'system') . "/assets/{$type}/{$filename}";
				
			//Throw a error if not found
			Debug::error("assets helper: <b>{$filename}</b> not found.", 1);
		}
		
	}
?>
