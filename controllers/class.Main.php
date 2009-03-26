<?php
	class Main extends Controller {
		
		var $default_template = "template";
		var $auto_render = TRUE;
		
		function __construct() {
			$this->template = new View('template');
		}
		
		function index($page="") {
			$this->template->content = new View('home');
			$this->template->render();
			
		}
		
		function about() {
		}
		
		function notfound() {
			$this->template->content = "404 - Not found!";
			$this->template->render();
		}
		
	}
?>