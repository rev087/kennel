<?php
	class Settings {
		
		private static $variables = array();
		
		function fetch() {
			require_once("settings.php");
			foreach($settings as $key=>$value) {
				self::set($key, $value);
			}
		}
		
		function get($key) {
			if(!self::$variables) self::fetch();
			return self::$variables[$key];
		}
		
		function set($key, $val) {
			self::$variables[$key] = $val;
		}
		
	}
?>
