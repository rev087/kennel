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
			else debug::error("assets helper: Image <b>{$uri}</b> not found.", 1);
		}
		
		static function css($uri)
		{
			# Absolute paths
			if (substr($uri, 0, 7) === 'http://')
				return $uri;
			
			# Cascading resource
			$path = Kennel::cascade($uri, 'css', true);
			if($path) return $path;
			else debug::error("assets helper: CSS <b>{$uri}</b> not found.", 1);
		}
		
		static function js($uri)
		{
			# Absolute paths
			if (substr($uri, 0, 7) === 'http://')
				return $uri;
			
			# Cascading resource
			$path = Kennel::cascade($uri, 'js', true);
			if($path) return $path;
			else debug::error("assets helper: JS <b>{$uri}</b> not found.", 1);
		}
		
		static function flash($uri)
		{
			# Absolute paths
			if (substr($uri, 0, 7) === 'http://')
				return $uri;
			
			# Cascading Resource
			$path = Kennel::cascade($uri, 'flash', true);
			if($path) return $path;
			else debug::error("assets helper: Flash <b>{$uri}</b> not found.", 1);
		}
		
		static function file($uri)
		{
			# Absolute paths
			if (substr($uri, 0, 7) === 'http://')
				return $uri;
			
			# Cascading Resource
			$path = Kennel::cascade($uri, 'file', true);
			if($path) return $path;
			else debug::error("assets helper: File <b>{$uri}</b> not found.", 1);
		}
		
	}
?>
