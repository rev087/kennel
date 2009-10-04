<?php
	
	class html
	{
		
		static function anchor($url, $text, $properties=null)
		{
			if(!$properties) $properties = array();
			$properties['href'] = $url;
			
			return $a = XML::element('a', null, $properties, $text);
		}
		
		static function css($filename)
		{
			$link = XML::element('link');
			$link->rel = 'stylesheet';
			$link->type = 'text/css';
			$link->href = assets::css($filename);
			return $link;
		}
		
		static function js($filename)
		{
			//<script type="text/javascript" src=""></script>
			$script = XML::element('script');
			$script->type = 'text/javascript';
			$script->src = assets::js($filename);
			$script->adopt(XML::text(''));
			return $script;
		}
		
	}
	
?>
