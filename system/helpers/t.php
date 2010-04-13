<?php
	/*
	* TODO: i18n
	*/
	class t
	{
		
		static $DOCTYPE = 'XHTML 1.0 Transitional';
		static $LANG = 'pt-br';
		static $DIR = 'ltr';
		static $CHARSET = 'UTF-8';
		
		static $HTML;
		static $HEAD;
		static $BODY;
		
		static $TITLE = 'Untitled';
		static $FAVICON;
		static $CONTENT;
		
		static $META = array();
		static $CSS = array();
		static $JS = array();
		
		private function getDocType()
		{
			switch (self::$DOCTYPE)
			{
				case 'XHTML 1.0 Transitional':
					return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
				case 'HTML 5':
					return '<!doctype html>';
			}
		}
		
		private function getContentType()
		{
			switch (self::$CHARSET)
			{
				case 'UTF-8':
					return 'text/html; charset=UTF-8';
			}
		}
		
		static function render()
		{
			// <html>
			self::$HTML = XML::element('html');
			self::$HTML->dir = self::$DIR;
			self::$HTML->lang = self::$LANG;
			
			// <head>
			self::$HEAD = XML::element('head', self::$HTML);
			
			// <title>
			$title = XML::element('title', self::$HEAD);
			$title->setValue(self::$TITLE);
			
			// favicon
			if (self::$FAVICON)
				self::$HEAD->adopt(html::favicon(self::$FAVICON));
			
			// content type
			self::$HEAD->adopt(html::meta('Content-Type', self::getContentType()));
			
			// <meta>
			foreach (self::$META as $meta)
				self::$HEAD->adopt(html::meta($meta['name'], $meta['content']));
			
			// <style>
			$styles = html::css(self::$CSS);
			self::$HEAD->adopt($styles);
			
			// <script>
			$scripts = html::js(self::$JS);
			self::$HEAD->adopt($scripts);
			
			// <body>
			self::$BODY = XML::element('body', self::$HTML);
			
			// Content
			XML::text(self::$CONTENT, self::$BODY);
			
			print self::getDocType() . self::$HTML->output(true);
		}
		
		static function __toString()
		{
			self::render();
		}
		
		function meta($name, $content)
		{
			self::$META[] = array('name'=>$name, 'content'=>$content);
		}
		
		function css()
		{
			$arguments = func_get_args();
			self::$CSS = array_merge(self::$CSS, $arguments);
		}
		
		function js()
		{
			$arguments = func_get_args();
			self::$JS = array_merge(self::$JS, $arguments);
		}
	}
?>
