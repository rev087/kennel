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
			//begin intercepting the output buffer the buffer
			ob_start();
			
			//set all template variables
			foreach ($this->vars as $var =>$val)
				$$var = $val;
			if($this->parent_view)
				foreach ($this->parent_view->vars as $var =>$val)
					$$var = $val;
			
			$path = Kennel::cascade("{$this->view}.php", 'views');
			if($path) require_once($path);
			
			//View cascading
			
			//1. User view
			if (is_file(Kennel::$ROOT_PATH . "/application/views/{$this->view}.php"))
				require_once (Kennel::$ROOT_PATH . "/application/views/{$this->view}.php");
			
			//2. Module view
			if (!Kennel::$MODULES) Kennel::fetchModules();
			foreach (Kennel::$MODULES as $module=>$info)
			{
				if (is_file(Kennel::$ROOT_PATH . "/modules/{$module}/views/{$this->view}.php"))
				{
					require_once (Kennel::$ROOT_PATH . "/modules/{$module}/views/{$this->view}.php");
				}
			}
			
			//3. System view
			if (is_file(Kennel::$ROOT_PATH . "/system/views/{$this->view}.php"))
				require_once (Kennel::$ROOT_PATH . "/system/views/{$this->view}.php");
			
			
			//unset all template variables
			foreach ($this->vars as $var =>$val) {
				unset($$var);
			}
			
			//return the output and close the buffer
			return ob_get_clean();
		}
		
		function render()
		{
			print $this->_getOutput();
		}
	}
?>
