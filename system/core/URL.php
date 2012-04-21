<?php
	
	class URL
	{
		var $host;
		var $resource;
		var $params = array();
		var $fragment;
		
		function __construct($base_url=null, $vars=array())
		{
			if (!$base_url) $this->fetchCurrent();
			else {
  			$parsed = parse_url($base_url);
  			$this->host = $parsed['host'];
  			$this->resource = $parsed['path'];
  			$this->params = $this->parseVars($parsed['query']);
			}
			if ($vars) $this->setVars($vars);
		}
		
		private function setBaseURL($url)
		{
			return $this;
		}
		
		function fetchCurrent()
		{
			$this->host = $_SERVER['HTTP_HOST'];
			$this->resource = str_replace("?{$_SERVER['QUERY_STRING']}", '', $_SERVER['REQUEST_URI']);
			$this->params = $this->parseVars($_SERVER['QUERY_STRING']);
		}
		
		private function parseVars($query_string)
		{
		  if ($query_string === '') return NULL;
		  
			$ret = array();
			$vars = explode('&', $query_string);
			foreach ($vars as $var)
			{
				$key_value = explode('=', $var);
				if (array_key_exists(1, $key_value))
  				$ret[$key_value[0]] = $key_value[1];
  			else
  			  $ret[$key_value[0]] = null;
			}
			return $ret;
		}
		
		function setVars($vars)
		{
			foreach ($vars as $key=>$value)
			{
				$this->set($key, $value);
			}
		}
		
		function set($key, $value)
		{
			$this->params[$key] = $value;
		}
		
		function anchor($text, $properties=array())
		{
			$a = HTML::anchor($this, $text, array('href'=>$this));
			foreach ($properties as $property=>$value) {
				$a->set($property, $value);
			}
			return $a;
		}
		
		function output()
		{
			$url = "";
			if($this->host) $url = "http://{$this->host}";
			if($this->resource) $url .= $this->resource;
			if(count($this->params) > 0) $url .= "?".$this->queryString();
			
			return $url;
		}
		
		private function queryString()
		{
			$ret = array();
			foreach($this->params as $key=>$value) {
				if ($value) $ret[] = "{$key}={$value}";
				else $ret[] = $key;
			}
			return join('&', $ret);
		}
		
		function __set($prop, $value) {
      $this->set($prop, $value);
		}
		
		function __get($prop) {
			if (array_search(strtolower($prop), array('query_string', 'querystring', 'query')) !== false)
				return $this->queryString();
			else if (array_search($prop, $this->params) !== false)
			  return $this->params[$prop];
			return null;
		}
		
		function __toString() {
			return $this->output();
		}
	}
	
?>