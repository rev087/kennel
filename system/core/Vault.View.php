<?php
	
	class View {
		private $view;
		private $vars = array();
		
		function __construct($view) {
			$this->view = $view;
		}
		
		function __tostring() {
			return $this->_getOutput();
		}
		
		function __set($var, $value) {
			$this->vars[$var] = $value;
		}
		
		private function _getOutput() {
			//begin intercepting the output buffer the buffer
			ob_start();
			
			//set all template variables
			foreach($this->vars as $var =>$val) {
				$$var = $val;
			}
			
			//require the view
			require_once(Vault::getResourcePath('view', $this->view));
			
			//unset all template variables
			foreach($this->vars as $var =>$val) {
				unset($$var);
			}
			
			//return the output and close the buffer
			return ob_get_clean();
		}
		
		function render() {
			print $this->_getOutput();
		}
	}
?>
