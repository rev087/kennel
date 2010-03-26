<?php
	
	class html
	{
		
		static function anchor($url, $text, $properties=null)
		{
			if(!$properties) $properties = array();
			$properties['href'] = $url;
			
			return $a = XML::element('a', null, $properties, $text);
		}
		
		static function css()
		{
			$arguments = func_get_args();
			if (sizeOf($arguments) == 0) return null;
			
			$link = XML::element('style');
			$link->type = 'text/css';
			foreach ($arguments as $filename) {
				$path = assets::css($filename);
				$text = new XMLText("\n\t@import url('{$path}');", $link);
			}
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
		
		function img($filename, $alt=NULL)
		{
			if ($alt === NULL) $alt = $filename;
			$img = XML::element('img');
			$img->src = assets::img($filename);
			$img->alt = $alt;
			return $img;
		}
		
	}
	
?>
