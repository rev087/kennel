<?php
	
	/**
		*  TODO i18n
		*/
	class Template extends View
	{
		// Static variables
		static $DOCTYPE_DECLARATIONS = array(
			'XHTML 1.0 Transitional' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
			'XHTML 1.0 Strict' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
			'HTML 5' => '<!doctype html>'
		);
		const MODERNIZR = 'modermozr-1.5.min.js';
		const PNGFIX = 'dd_belatedpng-0.0.8a.min.js';
		const MOOTOOLS = 'mootools-core-1.3.1-core-yc.js';
		const MOOTOOLS_MORE = 'mootools-1.3-full-nocompat-yc.js';
		const RAPHAEL = 'raphael-min.js';
		const JQUERY = 'jquery-1.5.2.min.js';
		
		static $INSTANCE = null;
		
		// Variables
		var $doctype = 'HTML 5';
		var $lang = '';
		var $dir = 'ltr';
		var $favicon = null;
		var $title = null;
		var $titleSeparator = '-';
		var $bodyClass = null;
		
		// Structure
		private $_html;
		private $_head;
		private $_body;
		
		// Meta
		private $_meta = array();
		
		// Resources
		private $_stylesheets = array();
		private $_scripts = array();
		
		// Template View
		private $_template;
		
		function __construct($template)
		{
			$this->_template = new View($template, $this);
			self::$INSTANCE = $this;
		}
		
		function getInstance()
		{
			if (self::$INSTANCE) return self::$INSTANCE;
			else return debug::error('Template::getInstance() error: there is no instance created yet');
		}
		
		// Ovewriting normal View behavior
		//////////////////////////////////
		
		private function _getOutput()
		{
			// Make $template and $title accessible in the descendant views
			$this->__set('template', $this);
			$this->__set('title', $this->title);
			
			// Template View (must run first since ancestor views might call functions from $template)
			$templateView = XML::text($this->_template->__toString());
			
			// <html>
			$this->_html = XML::element('html');
			$this->_html->dir =$this->dir;
			if (Kennel::getSetting('i18n', 'enabled'))
				$this->_html->lang = i18n::getLang();
			
			// <head>
			$this->_head = XML::element('head', $this->_html);
			
			// <title>
			$title = XML::element('title', $this->_head);
			$title->setValue($this->getTitle());
			
			// favicon
			if ($this->favicon)
				$this->_head->adopt(html::favicon($this->favicon));
			
			// content type
			XML::element('meta', $this->_head, array(
				'charset'=>'utf-8'
			));
			
			// <meta>
			foreach ($this->_meta as $meta)
				$this->_head->adopt(html::meta($meta['name'], $meta['content']));
			
			// <style>
			$this->_head->adopt(html::css($this->_stylesheets));
			
			// <script>
			$this->_head->adopt(html::js($this->_scripts));
			
			// <body>
			$this->_body = XML::element('body', $this->_html);
			$this->_body->class = browser::css();
			if (Kennel::getSetting('i18n', 'enabled'))
				$this->_body->class .= ' ' . i18n::getLang();
			if ($this->bodyClass) $this->_body->class .= ' ' . $this->bodyClass;
			
			// Inject the Template View
			$this->_body->adopt($templateView);
			
			// Return the whole shebang
			return self::$DOCTYPE_DECLARATIONS[$this->doctype] . $this->_html->output(true);
		}
		
		function render()
		{
			header('content-type: text/html; charset:UTF-8');
			print $this->_getOutput();
		}
		
		// Aditional utility methods
		////////////////////////////
		
		function getTitle()
		{
			if ($this->title && Kennel::getSetting('application', 'title'))
				return $this->title . " {$this->titleSeparator} " . Kennel::getSetting('application', 'title');
			elseif (!$this->title && Kennel::getSetting('application', 'title'))
				return Kennel::getSetting('application', 'title');
			elseif ($this->title && !Kennel::getSetting('application', 'title'))
				return $this->title;
			else
				return 'Untitled';
		}
		
		function meta($name, $content)
		{
			$this->_meta[] = array('name'=>$name, 'content'=>$content);
		}
		
		function css()
		{
			$arguments = func_get_args();
			foreach ($arguments as $stylesheet)
				if (!in_array($stylesheet, $this->_stylesheets))
					$this->_stylesheets[] = $stylesheet;
		}
		
		function js()
		{
			$arguments = func_get_args();
			foreach ($arguments as $script)
				if (!in_array($script, $this->_scripts))
					$this->_scripts[] = $script;
		}
	}
?>
