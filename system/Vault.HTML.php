<?php
	//version 0.1
	
	require_once('controllers/controller.Model.php');
	require_once('controllers/controller.XML.php');
	
	class HTML {
		
		static function url($base_url=null, $vars=array()) {
			return new URL($base_url, $vars);
		}
		
	}
	
	class Pagination {
		
		private $shebang;
		private $pages;
		private $items_per_page;
		private $page_number;
		
		function __construct($shebang, $items_per_page) {
			$this->shebang = $shebang;
			$this->items_per_page = $items_per_page;
			$this->pages = array_chunk($shebang, $items_per_page);
		}
		
		function getPage($page_number=null) {
			if(!$page_number) $this->page_number = 1;
			else $this->page_number = $page_number;
			
			return $this->pages[$this->page_number-1];
		}
		
		function printLinks() {
			foreach($this->pages as $page => $item) {
				$page++; //user-readable page is aways +1 relative to the array index
				
				if($page == $this->page_number) print '<span>'.$page.'</span>';
				else print '<a href="?pg='.$page.'">'.$page.'</a>';
			}
		}
		
	}
	
	class URL {
		var $host;
		var $resource;
		var $vars = array();
		var $fragment;
		
		function __construct($base_url=null, $vars=array()) {
			if(!$base_url) $this->fetchCurrent();
			else $this->set($base_url);
			if($vars) $this->addVars($vars);
		}
		
		private function set($url) {
			$parsed = parse_url($url);
			$this->host = $parsed['host'];
			$this->resource = $parsed['path'];
			$this->vars = $this->parseVars($parsed['query']);
			return $this;
		}
		
		function fetchCurrent() {
			$this->host = $_SERVER['HTTP_HOST'];
			$this->resource = str_replace("?{$_SERVER['QUERY_STRING']}", '', $_SERVER['REQUEST_URI']);
			$this->vars = $this->parseVars($_SERVER['QUERY_STRING']);
		}
		
		private function parseVars($query_string) {
			$ret = array();
			$vars = explode('&', $query_string);
			foreach($vars as $var) {
				$key_value = explode('=', $var);
				$ret[$key_value[0]] = $key_value[1];
			}
			return $ret;
		}
		
		function addVars($vars) {
			foreach($vars as $key=>$value) {
				$this->addVar($key, $value);
			}
		}
		
		function addVar($key, $value) {
			$this->vars[$key] = $value;
		}
		
		function anchor($text, $properties=array(), $return=false) {
			require_once('controllers/controller.XML.php');
			$a = new XMLElement('a', null, array('href'=>$this), $text);
			foreach($properties as $property=>$value) {
				$a->set($property, $value);
			}
			if($return) return $a;
			else print $a;
		}
		
		function output() {
			$url = "";
			if($this->host) $url = "http://{$this->host}";
			if($this->resource) $url .= $this->resource;
			if(count($this->vars) > 0) $url .= "?".$this->getQueryString();
			
			return $url;
		}
		
		private function getQuerystring() {
			$ret = array();
			foreach($this->vars as $key=>$value) {
				$ret[] = "{$key}={$value}";
			}
			return join('&', $ret);
		}
		
		function __get($prop) {
			if(array_search(strtolower($prop), array('query_string', 'querystring', 'query')) !== false) {
				return $this->getQueryString();
			}
		}
		
		function __toString() {
			return $this->output();
		}
	}

?>
