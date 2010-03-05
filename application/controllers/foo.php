<?php
	class Foo_controller extends Controller
	{
		function index()
		{
			$c = new Criteria('page');
			$c->add('id', 0, Criteria::NOT_EQUAL);
			$test = ORM::retrieveFirst($c);
			$test->dump();
		}
		
		function bar()
		{
			print '<h1>Foo Bar</h1>';
		}
	}
?>
