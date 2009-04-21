<?php
	
	class Controller {
		var $input;
		var $request;
		
		function __construct() {
			$this->input = new Input();
			$this->request = new Request();
		}
		
	}
	
?>