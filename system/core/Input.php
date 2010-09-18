<?php
	class Input
	{
		private static $_get;
		private static $_post;
		
		function __construct()
		{
			self::prepare();
		}
		
		static function prepare()
		{
			self::$_get = $_GET;
			self::$_post = $_POST;
		}
		
		static function get($var)
		{
			if(!self::$_get) self::prepare();
			
			if(isset(Request::$NAMED_ARGS[$var]))
				return self::clean(Request::$NAMED_ARGS[$var]);
			elseif(isset(self::$_get[$var]))
				return self::clean(self::$_get[$var]);
			else
				return null;
		}
		
		static function post($var=null)
		{
			if(!self::$_post) self::prepare();
			
			if ($var)
			{
				if (isset(self::$_post[$var]))
					return self::clean(self::$_post[$var]);
				else
					return null;
			}
			else
			{
				if (isset(self::$_post)) return self::$_post;
				return self::$_post = self::clean($_POST);
			}
		}
		
		function __toString()
		{
			return self::dump(true);
		}
		
		static function dump($return=false)
		{
			$dump = '<h1>GET</h1>';
			$dump .= Debug::dump($_GET, true);
			$dump .= '<h1>POST</h1>';
			$dump .= Debug::dump($_POST, true);
			$dump .= '<h1>FILES</h1>';
			$dump .= Debug::dump($_FILES, true);
			
			if($return) return $dump;
			else print $dump;
		}
		
		static function clean($data)
		{
			if (is_array($data))
			{
				$clean = array();
				foreach($data as $key=>$item) {
					$clean[$key] = self::clean($item);
				}
				return $clean;
			}
			else
			{
				return self::cleanString($data);
			}
		}
		
		/*
		* string Input::cleanString(string $data)
		*
		* This entire method comes almost straight from the Kohana Input Library (http://docs.kohanaphp.com/libraries/input),
		* wich is a modified version of Christian Stocker's code. (http://svn.bitflux.ch/repos/public/popoon/trunk/classes/externalinput.php)
		* Information on Kohana's modifications of the original code can be found in the comments inside Kohana's Input Library.
		*/
		static function cleanString($data)
		{
			// Remove slashes added by the browser for \, " and '
			$data = str_replace(array('\\\\', '\"', "\'"), array('\\', '"', "'"), $data);
			
			// Fix &entity\n;
			$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
			$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
			$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
			$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
			
			// Remove any attribute starting with "on" or xmlns
			$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
			
			// Remove javascript: and vbscript: protocols
			$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
			$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
			$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
			
			// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
			$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
			$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
			$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
			
			// Remove namespaced elements (we do not need them)
			$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
			
			do
			{
				// Remove really unwanted tags
				$old_data = $data;
				$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
			}
			while ($old_data !== $data);
			
			return $data;
		}
	}
?>
