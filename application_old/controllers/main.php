<?php
class Main_controller extends Controller
{
	public function index()
	{
		$view = new Template('home');
		$view->render();
	}
	
	public function about()
	{
		$view = new Template('about');
		$view->intro= new View('home');
		$view->render();
	}
}
?>