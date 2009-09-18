<?php
	class Main_controller extends 	Controller
	{
		function __construct()
		{
			parent::__construct();
		}
		
		function index()
		{
			$this->template = new View('template');
			$this->template->render();
		}
		
	}
?>