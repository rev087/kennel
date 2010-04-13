<?php
	
	class View
	{
		private $view;
		private $parent_view;
		private $vars = array();
		
		function __construct($view)
		{
			$this->view = $view;
		}
		
		function __toString()
		{
			return strval($this->_getOutput());
		}
		
		function __get($var)
		{
			if(isset($this->vars[$var])) return $this->vars[$var];
			else return null;
		}
		
		function __set($var, $value)
		{
			if (is_object($value) && get_class($value) == 'View')
				$value->parent_view = $this;
				
			$this->vars[$var] = $value;
		}
		
		function getTemplateVars()
		{
			return $this->vars;
		}
		
		private function _getOutput() {
			
			//set all template variables
			foreach ($this->vars as $var =>$val)
				$$var = $val;
			if($this->parent_view)
				foreach ($this->parent_view->vars as $var =>$val)
					$$var = $val;
			
			$path = Kennel::cascade("{$this->view}", 'views');
			if (!$path) return Debug::error("View <strong>{$this->view}</strong> not found.");
			
			//begin intercepting the output buffer the buffer
			ob_start();
			
			if($path) require($path);
			
			//return the output and close the buffer
			return ob_get_clean();
			
			//unset all template variables
			foreach ($this->vars as $var =>$val) {
				unset($$var);
			}
		}
		
		function render()
		{
			print $this->_getOutput();
		}
	}
?>
