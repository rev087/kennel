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
		
		function __construct($tagname, $parent=null, $properties=null, $text=null) {
			$this->tagname = $tagname;
			$this->properties = $properties;
			
			if($parent) {
				$this->parent = $parent;
				$parent->adopt($this);
			}
			
			if($text) $txtEl = new XMLText($text, $this);
		}
		
		function adopt($element) {
			if($element->parent) $element->parent->abandon($element);
			$element->parent = $this;
			$this->children[] = $element;
		}
		
		function abandon($element) {
			foreach($this->children as $child) {
				//if($element = $child) print "<p></p>{$this} abandoning {$element}</p>";
			}
		}
		
		function __tostring() {
			return $this->output();
		}
		
		function output() {
			$properties = $this->__propertiesToString();
			if(count($this->children) >= 1) {
				$ret = "<{$this->tagname}{$properties}>";
				foreach($this->children as $child) {
					$ret .= $child->__tostring();
				}
				$ret .= "</{$this->tagname}>";
				return $ret;
			} else {
				return "<{$this->tagname}{$properties} />";
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
		
		private function __propertiesToString() {
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
		
		function __construct($text, $parent=null) {
			$this->text = $text;
			if($parent) {
				$this->parent = $parent;
				$parent->adopt($this);
			}
		}
		
		function __tostring() {
			return $this->text;
		}
		
		function toString() {
			return $this->__tostring();
		}
		
	}
	
?>
