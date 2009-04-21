<?php
	
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
		
		function anchor($text, $properties=array()) {
			$a = HTML::anchor($this, $text, array('href'=>$this));
			foreach($properties as $property=>$value) {
				$a->set($property, $value);
			}
			return $a;
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
				if($value) $ret[] = "{$key}={$value}";
				else $ret[] = $key;
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