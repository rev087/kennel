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
		
		private function _cascade($filename, $type)
		{
			//1. User asset
			if (is_file(Vault::$app_root_path . Vault::getSetting('path', 'assets') . "/{$type}/{$filename}"))
				return Vault::$app_root_uri . Vault::getSetting('path', 'assets') . "/{$type}/{$filename}";
			
			//2. Module asset
			if (!Vault::$modules) Vault::fetchModules();
			foreach (Vault::$modules as $module)
			{
				if (is_file(Vault::$app_root_path . Vault::getSetting('path', 'modules') . "/{$module}/assets/{$type}/{$filename}"))
					return Vault::$app_root_uri . Vault::getSetting('path', 'modules') . "/{$module}/assets/{$type}/{$filename}";
			}
			
			//3. System asset
			if (is_file(Vault::$app_root_path . Vault::getSetting('path', 'system') . "/assets/{$type}/{$filename}"))
				return Vault::$app_root_uri . Vault::getSetting('path', 'system') . "/assets/{$type}/{$filename}";
		}
		
	}
?>
