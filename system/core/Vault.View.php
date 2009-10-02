 <?php
	
	class View
	{
		private $view;
		private $vars = array();
		
		function __construct($view)
		{
			$this->view = $view;
		}
		
		function __tostring()
		{
			return $this->_getOutput();
		}
		
		function __set($var, $value)
		{
			$this->vars[$var] = $value;
		}
		
		private function _getOutput() {
			//begin intercepting the output buffer the buffer
			ob_start();
			
			//set all template variables
			foreach ($this->vars as $var =>$val) {
				$$var = $val;
			}
			
			//View cascading
			
			//1. User view
			if (is_file(Vault::getPath('views') . "/{$this->view}.php"))
				require_once(Vault::getPath('views') . "/{$this->view}.php");
			
			//2. Model view
			if (!Vault::$modules) Vault::fetchModules();
			foreach (Vault::$modules as $module)
			{
				if (is_file(Vault::getPath('modules') . "/{$module}/views/{$this->view}.php"))
				{
					require_once Vault::getPath('modules') . "/{$module}/views/{$this->view}.php"; return;
				}
			}
			
			//3. System view
			if (is_file(Vault::getPath('system') . "/views/{$this->view}.php"))
				require_once(Vault::getPath('system') . "/views/{$this->view}.php");
			
			
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