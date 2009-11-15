<?php
	class assets
	{
		static function img($filename)
		{
			$path = Kennel::cascade($filename, 'img', true);
			if($path) return $path;
			else Debug::error("assets helper: <b>{$filename}</b> not found.", 1);
		}
		
		static function css($filename)
		{
			$path = Kennel::cascade($filename, 'css', true);
			if($path) return $path;
			else Debug::error("assets helper: <b>{$filename}</b> not found.", 1);
		}
		
		static function js($filename)
		{
			$path = Kennel::cascade($filename, 'js', true);
			if($path) return $path;
			else Debug::error("assets helper: <b>{$filename}</b> not found.", 1);
		}
		
		static function flash($filename)
		{
			$path = Kennel::cascade($filename, 'flash', true);
			if($path) return $path;
			else Debug::error("assets helper: <b>{$filename}</b> not found.", 1);
		}
		
	}
?>
