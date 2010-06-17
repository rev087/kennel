<?php
	class assets
	{
		static function img($uri)
		{
			# Absolute paths
			if (substr($uri, 0, 7) === 'http://')
				return $uri;
			
			# Cascading resource
			$path = Kennel::cascade($uri, 'img', true);
			if($path) return $path;
			else Debug::error("assets helper: <b>{$uri}</b> not found.", 1);
		}
		
		static function css($uri)
		{
			# Absolute paths
			if (substr($uri, 0, 7) === 'http://')
				return $uri;
			
			# Cascading resource
			$path = Kennel::cascade($uri, 'css', true);
			if($path) return $path;
			else Debug::error("assets helper: <b>{$uri}</b> not found.", 1);
		}
		
		static function js($uri)
		{
			# Absolute paths
			if (substr($uri, 0, 7) === 'http://')
				return $uri;
			
			# Cascading resource
			$path = Kennel::cascade($uri, 'js', true);
			if($path) return $path;
			else Debug::error("assets helper: <b>{$uri}</b> not found.", 1);
		}
		
		static function flash($uri)
		{
			# Absolute paths
			if (substr($uri, 0, 7) === 'http://')
				return $uri;
			
			# Cascading Resource
			$path = Kennel::cascade($uri, 'flash', true);
			if($path) return $path;
			else Debug::error("assets helper: <b>{$uri}</b> not found.", 1);
		}
		
	}
?>
