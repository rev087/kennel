<?php
	
	class XML {
		static function element($tagname, $parent=null, $properties=null, $text=null) {
			return new XMLElement($tagname, $parent, $properties, $text);
		}
		
		static function text($text, $parent=null) {
			return new XMLText($text, $parent);
		}
	}
	
	class XMLElement {
		
		var $parent;
		var $children = array();
		var $tagname;
		var $properties = array();
		
		function __construct($tagname, XMLElement $parent=null, $properties=null, $text=null) {
			$this->tagname = $tagname;
			$this->properties = $properties;
			
			if($parent) {
				$this->parent = $parent;
				$parent->adopt($this);
			}
			
			if($text) $txtEl = new XMLText($text, $this);
		}
		
		function adopt() {
			$elements = func_get_args();
			foreach ($elements as $element)
			{
				if ($element instanceof XMLElement || $element instanceof XMLText)
				{
					if ($element->parent) $element->parent->abandon($element);
					$element->parent = $this;
				}
				else
				{
					$element = XML::text($element);
				}
				$this->children[] = $element;
			}
		}
		
		function abandon($element) {
			foreach($this->children as $child) {
				//if($element == $child) print "<p></p>{$this} abandoning {$element}</p>";
			}
		}
		
		function __tostring() {
			return $this->output();
		}
		
		function setText($text)
		{
			$txtEl = new XMLText($text, $this);
			return $this;
		}
		
		function setValue($text)
		{
			if ($text instanceof XMLText)
				$this->adopt($text);
			else
				XML::text($text, $this);
		}
		
		function output($formatOutput=false, $indent=null) {
			
			$indent = $formatOutput ? $indent : '';
			$nl = $formatOutput ? "\n" : '';
			
			$properties = $this->_propertiesToString();
			
			if (count($this->children) >= 1)
			{
				$ret = "{$nl}{$indent}<{$this->tagname}{$properties}>";
				foreach ($this->children as $child)
					$ret .= $child->output($formatOutput, $indent . "\t");
				$ret .= "{$nl}{$indent}</{$this->tagname}>";
				return $ret;
			}
			else
			{
				return "{$nl}{$indent}<{$this->tagname}{$properties} />";
			}
		}
		
		function __get($property) {
			foreach($this->properties as $key=>$value) {
				if($property==$key) return $value;
			}
			return null;
		}
		
		function __set($property, $value) {
			$this->properties[$property] = $value;
			return $this;
		}
		
		function set($property, $value) {
			return $this->__set($property, $value);
		}
		
		private function _propertiesToString() {
			if(count($this->properties) > 0) {
				$props = array();
				foreach($this->properties as $key=>$value) {
					$props[] = "{$key}=\"{$value}\"";
				}
				return " ".join(" ", $props);
			} else {
				return "";
			}
		}
		
	}
	
	class XMLText {
		var $text;
		var $parent;
		
		function __construct($text, XMLElement $parent=null) {
			$this->text = $text;
			if($parent) {
				$this->parent = $parent;
				$parent->adopt($this);
			}
		}
		
		private function indentText($formatOutput=null, $indent=null)
		{
			if (!$formatOutput)
			{
				return $this->text;
			}
			else
			{
				// Avoid messing with HTML content
				if (preg_match('/\<[a-z]+[ \/]?\>/', $this->text)) return $this->text;
				
				$str = $this->text;
				$str = trim($str, "\n");
				$str = "\n{$str}";
				$str = str_replace("\t", '', $str);
				$str = str_replace("\n", "\n{$indent}", $str);
				return $str;
			}
		}
		
		function output($formatOutput=false, $indent=null)
		{
			$indent = $formatOutput ? $indent : '';
			$nl = $formatOutput ? "\n" : '';
			return $this->indentText($formatOutput, $indent);
		}
		
		function __toString() {
			return $this->text;
		}
		
		function toString() {
			return $this->__tostring();
		}
		
	}
	
?>
