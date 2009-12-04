<?php
	class Foo_controller extends Controller
	{
		function index()
		{
			$poster = ORM::retrieveByPrimaryKey('image', 38);
			$poster->thumb(137, 225);
			$poster->thumb(137, 225);
			$poster->thumb(137, 225);
			$poster->thumb(137, 225);
			$poster->thumb(137, 225);
			$poster->thumb(540, 1000);
			$poster->thumb(540, 1000);
			$poster->thumb(540, 1000);
			$poster->thumb(540, 1000);
			$poster->thumb(540, 1000);
			$poster->thumb(100, 125);
			$poster->thumb(100, 125);
			$poster->thumb(100, 125);
			$poster->thumb(100, 125);
			$poster->thumb(100, 125);
			$poster->dump();
		}
		
		function bar()
		{
			print '<h1>Foo Bar</h1>';
		}
	}
?>