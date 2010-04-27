<?php
	class router
	{
		public static $PREFIX;
		public static $SUFIX;
		
		private static $PREFIX_PATTERN;
		private static $SUFIX_PATTERN;
		
		function requestHook($parts)
		{
			if (!isset($parts[0]) || !self::$PREFIX_PATTERN) return $parts;
			
			$prefix = self::$PREFIX_PATTERN;
			
			if (preg_match("/{$prefix}/", $parts[0]))
			{
				self::$PREFIX = array_shift($parts);
				return $parts;
			}
			
			return $parts;
		}
		
		function prefix($prefix)
		{
			self::$PREFIX_PATTERN = $prefix;
		}
		
	}
	
	// Set the router hook for the Request processing
	Request::hook(array('router', 'requestHook'));
?>
