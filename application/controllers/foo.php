<?php
	class Foo_controller extends Controller
	{
		function index()
		{
			$poster = ORM::retrieveByPrimaryKey('image', 35);
			$img = new Image($poster->path());
			$img->thumb(137, 225);
			$img->output();
		}
		
		function bar()
		{
			print '<h1>Foo Bar</h1>';
		}
	}
?>