<?php
	class Main_controller extends Controller
	{
		var $msg;
		
		function index()
		{
			Kennel::controllerAction('fwsetup');
		}
	}
?>
