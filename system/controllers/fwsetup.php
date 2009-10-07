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
			$this->template->content = Debug::dump(Vault::$modules, true);
			$this->template->render();
		}
		
		function database()
		{
			$this->template = new View('fwsetup_login');
			$this->template->action = 'database';
			$this->template->content = '';
			$this->template->render();
		}
		
		function settings()
		{
			$this->template = new View('fwsetup_login');
			$this->template->action = 'settings';
			$this->template->content = '';
			$this->template->render();
		}
	}
?>
