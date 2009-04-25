<?php
	class Main_controller extends Controller
	{
		
		var $autoRender = TRUE;
		
		function __construct()
		{
			$this->template = new View('template');
		}
		
		function __destruct()
		{
			if ($this->autoRender)
				$this->template->render();
		}
		
		function index($page="")
		{
			$this->template->content = new View('home');
		}
		
		function about()
		{
		}
		
		function notfound()
		{
			$this->template->content = "404 - Not found!";
			$this->template->render();
		}
		
	}
?>
