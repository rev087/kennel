<?php
	class fwsetup_controller extends Controller
	{
		function index()
		{
			$this->modules();
		}
			
		function modules()
		{
			$this->template = new View('fwsetup_login');
			$this->template->action = 'modules';
			$this->template->render();
		}
		
		function database()
		{
			$this->template = new View('fwsetup_login');
			$this->template->action = 'database';
			$this->template->render();
		}
		
		function settings()
		{
			$this->template = new View('fwsetup_login');
			$this->template->action = 'settings';
			$this->template->render();
		}
	}
?>
