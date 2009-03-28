<?php
	class Input {
		var $_get;
		var $_post;
		
		function __construct() {
			$this->_get = $_GET;
			$this->_post = $_POST;
		}
		
		function get($var) {
			return $this->_get[$var];
		}
		
		function post($var) {
			return $this->_post[$var];
		}
		
		function __toString() {
			return $this->dump(true);
		}
		
		function dump($return) {
			return Vault::dump(array('GET'=>$this->_get, 'POST'=>$this->_post), $return);
		}
	}
?>