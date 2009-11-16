<?php
	
	/**
		*  TODO; Don't use this library yet.
		*/
	
	class Template
	{
		private static $view;
		private static $stylesheets = array();
		private static $scripts = array();
		
		function __construct($view=null)
		{
			if(!isset(self::$view)) self::setView($view);
		}
		
		function setView($view)
		{
			self::$view = new View($view);
		}
		
		function addStylesheet($filename)
		{
			if(array_search($filename, self::$stylesheets) === null)
				self::$stylesheets[] = $filename;
		}
		
		function addJavascript($filename)
		{
			if(array_search($filename, self::$scripts) === null)
				self::$scripts[] = $filename;
		}
		
		function header()
		{
			foreach (self::$stylesheets as $filename)
				print html::css($filename);
			foreach (self::$scripts as $filename)
				print html::js($filename);
		}
		
	}
?>
