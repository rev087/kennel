<?php
	
	/**
	 * Helper class with shortcuts for creating XML elements and text nodes
	 */
	class XML {
		const SELF_CLOSING_XML = 'SELF_CLOSING_XML';
		const SELF_CLOSING_HTML = 'SELF_CLOSING_HTML';
	  
	  /**
	   * Create and return a new XML element
	   * 
	   * @return XMLElement
	   * 
	   * @param string $tagname
	   * @param XMLEmement $parent
	   * @param array $properties
	   * @param string $text
	   */
		static function element($tagname, $parent=null, $properties=null, $text=null) {
			return new XMLElement($tagname, $parent, $properties, $text);
		}
		
		/**
		 * Create and return a new XML text node
		 * 
		 * @return XMLText
		 * 
		 * @param string $text
		 * @param XMLElement $parent
		 */
		static function text($text, $parent=null) {
			return new XMLText($text, $parent);
		}
	}
	
	/**
	 * Library for creating XML element trees, used across the framework to
	 * programatically render HTML in an object oriented way
	 */
	class XMLElement {
		
		var $parent;
		var $children = array();
		var $tagname;
		var $properties = array();
		
		var $self_closing = null;
		
		/**
		 * @param string $tagname
		 * @param XMLElement $parent
		 * @param array $properties
		 * @param string $text
		 */
		function __construct($tagname, XMLElement $parent=null, $properties=null, $text=null) {
			$this->tagname = $tagname;
			$this->properties = $properties;
			
			if($parent) {
				$this->parent = $parent;
				$parent->adopt($this);
			}
			
			if($text) $txtEl = new XMLText($text, $this);
		}
		
		/**
		 * Adopted the provided XMLElement or XMLText argument as a child element
		 * in the XML tree. Strings can be passed as arguments, in which case
		 * this method creates a new XMLText node before adopting
		 * 
		 * @param XMLElement|XMLText|string $element,...
		 */
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
		
		/**
		 * Removes the provided XMLElement from the child elements in the XML
		 * tree
		 * 
		 * @param XMLElement|XMLText $element
		 */
		function abandon($element) {
			foreach($this->children as $child) {
				//if($element == $child) print "<p></p>{$this} abandoning {$element}</p>";
			}
		}
		
		/**
		 * Magic method that returns XML strings of the element's XML tree,
		 * allowing string concatenations
		 * 
		 * @return string
		 */
		function __tostring() {
			return $this->output();
		}
		
		/**
		 * Sets the element's text value.
		 * 
		 * @param string $text
		 * 
		 * @return XMLElement returns the element itself
		 */
		function setText($text)
		{
			$txtEl = new XMLText($text, $this);
			return $this;
		}
		
		/**
		 * Same as XMLElement::setText, but accepts XMLText nodes as well as
		 * strings
		 * 
		 * @param XMLText|string $text
		 * 
		 * @return XMLElement returns the element itself
		 */
		function setValue($text)
		{
			if ($text instanceof XMLText)
				$this->adopt($text);
			else
				XML::text($text, $this);
			return $this;
		}
		
		/**
		 * Generates the XML string representation of the element's XML tree
		 * 
		 * @param boolean $formatOutput whether or not to return indented
		 * output
		 * @param string $indent base indentation (or any other string to be
		 * prefixed to each line)
		 */
		function output($formatOutput=false, $indent=null) {
			
			$indent = $formatOutput ? $indent : '';
			$nl = $formatOutput ? "\n" : '';
			
			$properties = $this->_propertiesToString();
			
			if (count($this->children) > 0 || !$this->self_closing)
			{
  			$ret = "{$nl}{$indent}<{$this->tagname}{$properties}>";
  			foreach ($this->children as $child)
  				$ret .= $child->output($formatOutput, $indent . "\t");
  			$ret .= "{$nl}{$indent}</{$this->tagname}>";
			}
			else
			{
			  if ($this->self_closing == XML::SELF_CLOSING_HTML)
    			$ret = "{$nl}{$indent}<{$this->tagname}{$properties}>";
    		else
    		  $ret = "{$nl}{$indent}<{$this->tagname}{$properties} />";
			}
			return $ret;
		}
		
		/**
		 * Magic method that allows getting values for any property of the XML
		 * element
		 * 
		 * <code><?php echo $image->alt; ?></code>
		 * 
		 * @param string $property the property to return
		 */
		function __get($property) {
			foreach($this->properties as $key=>$value) {
				if($property==$key) return $value;
			}
			return null;
		}
		
		/**
		 * Magic method that allows setting values to any property of the XML
		 * element
		 * 
		 * <code><?php $image->alt = 'A duck in a lake'; ?></code>
		 * 
		 * @param string $property the property to set
		 * @param string $value the value to set
		 */
		function __set($property, $value) {
			if ($property === 'text')
				$this->setText($value);
			else
				$this->properties[$property] = $value;
			return $this;
		}
		
		/**
		 * Same as XMLElement::__set
		 * 
		 * <code>
		 * 	<?php
		 * 		// Writing...
		 * 		$image->alt = 'A duck in a lake';
		 * 
		 * 		// ...is the same as...
		 * 		$image->set('alt', 'A duck in a lake');
		 * 
		 * 		// ...or yet
		 * 		$property = 'alt';
		 * 		$image->$property = 'A duck in a lake';
		 * 	?>
		 * </code>
		 * 
		 * @param string $property the property to set
		 * @param string $value the value to set
		 */
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
	
	/**
	 * Class that represents XML text nodes
	 */
	class XMLText {
		var $text;
		var $parent;
		
		/**
		 * @param string $text
		 * @param XMLElement $parent
		 */
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
		
		/**
		 * Used internally, outputs the string representation of the text node
		 * including indentation
		 * 
		 * @param boolean $formatOutput
		 * @param string $indent
		 */
		function output($formatOutput=false, $indent=null)
		{
			$indent = $formatOutput ? $indent : '';
			$nl = $formatOutput ? "\n" : '';
			return $this->indentText($formatOutput, $indent);
		}
		
		/**
		 * Magic method to allow string concatenation
		 * 
		 * @return string
		 */
		function __toString() {
			return $this->text;
		}
		
	}
	
?>
