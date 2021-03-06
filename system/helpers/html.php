<?php
	
	class html
	{
		
		static function anchor($url, $text, $properties=null)
		{
			if(!$properties) $properties = array();
			$properties['href'] = $url;
			
			return $a = XML::element('a', null, $properties, $text);
		}
		
		static function meta($properties)
		{
			$meta = XML::element('meta', null, $properties);
			$meta->self_closing = XML::SELF_CLOSING_HTML;
			return $meta;
		}
		
		static function link($rel, $href, $type, $title=null)
		{
			$link = XML::element('link');
			$link->rel = $rel;
			$link->href = $href;
			$link->self_closing = XML::SELF_CLOSING_HTML;
			if ($link) $link->type = $type;
			if ($title) $link->title = $title;
			return $link;
		}
		
		static function favicon($img)
		{
			return self::link('shortcut icon', assets::img($img), 'image/png');
		}
		
		static function css()
		{
			$arguments = func_get_args();
			$arguments = a::flatten($arguments);
			if (sizeOf($arguments) == 0) return null;
			
			$link = XML::element('style');
			$link->type = 'text/css';
			foreach ($arguments as $filename) {
				$path = assets::css($filename);
				$text = new XMLText("\n\t@import url('{$path}');", $link);
			}
			return $link->output();
		}
		
		static function js()
		{
			$arguments = func_get_args();
			$arguments = a::flatten($arguments);
			if (sizeOf($arguments) == 0) return null;
			
			$return = '';
			foreach ($arguments as $filename) {
				$script = XML::element('script');
				$script->type = 'text/javascript';
				$script->src = assets::js($filename);
				$script->adopt(XML::text(''));
				$return .= "\n\t".$script->output();
			}
			return $return;
		}
		
		static function img($filename, $alt=null, $title=null)
		{
			if ($alt === NULL) $alt = $filename;
			$img = XML::element('img');
			$img->src = assets::img($filename);
			$img->alt = $alt;
			if ($title) $img->title = $title;
			return $img;
		}
		
	}
	
?>
