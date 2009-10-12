<?php
	class fwsetup_controller extends Controller
	{
		var $msg;
		
		function index()
		{
			$this->modules();
		}
		
		function modules()
		{
			$this->template = new View('fwsetup_layout');
			$this->template->action = 'modules';
			$this->template->content = new View('fwsetup_modules');
			$this->template->content->modules = Vault::$modules;
			$this->template->render();
		}
		
		function createmodels()
		{
			$models = array();
			
			// User Models
			$dir = Vault::getPath('models') . "/sql/";
			if (is_dir($dir))
				foreach (scandir($dir) as $filename)
				{
					$path_info = pathinfo($filename);
					if ($path_info['extension'] == 'sql')
						$models[] = array('dir'=>$dir, 'info'=> $path_info, 'source'=>'application');
				}
			
			// Module Models
			foreach (Vault::$modules as $module)
			{
				$dir = Vault::getPath('modules') . "/{$module['id']}/models/sql/";
				if (is_dir($dir))
					foreach (scandir($dir) as $filename)
					{
						$path_info = pathinfo($filename);
						if ($path_info['extension'] == 'sql')
							$models[] = array('dir'=>$dir, 'info'=> $path_info, 'source'=> $module['id']);
					}
			}
			
			// System Models
			$dir = Vault::getPath('system') . "/models/sql/";
			if (is_dir($dir))
				foreach (scandir($dir) as $filename)
				{
					$path_info = pathinfo($filename);
					if ($path_info['extension'] == 'sql')
						$models[] = array('dir'=>$dir, 'info'=> $path_info, 'source'=>'system');
				}
			
			$db = new MySQL();
			$rs = $db->query("SHOW TABLES");
			$tables = array();
			while ($row = $db->fetch_array($rs))
			{
				$tables[] = $row[0];
			}
			
			$created = 0;
			foreach ($models as $id=>$model)
			{
				$result = array_search($model['info']['filename'], $tables);
				if ($result === FALSE)
				{
					$sql = file_get_contents("{$model['dir']}{$model['info']['basename']}");
					if($db->query($sql)) $created++;
				}
			}
			
			$this->msg = "<strong>{$created} models</strong> created successfuly.";
			$this->database();
		}
		
		function database()
		{
			$models = array();
			
			// User Models
			$dir = Vault::getPath('models') . "/sql/";
			if (is_dir($dir))
				foreach (scandir($dir) as $filename)
				{
					$path_info = pathinfo($filename);
					if ($path_info['extension'] == 'sql')
						$models[] = array('dir'=>$dir, 'info'=> $path_info, 'source'=>'application');
				}
			
			// Module Models
			foreach (Vault::$modules as $module)
			{
				$dir = Vault::getPath('modules') . "/{$module['id']}/models/sql/";
				if (is_dir($dir))
					foreach (scandir($dir) as $filename)
					{
						$path_info = pathinfo($filename);
						if ($path_info['extension'] == 'sql')
							$models[] = array('dir'=>$dir, 'info'=> $path_info, 'source'=> $module['id']);
					}
			}
			
			// System Models
			$dir = Vault::getPath('system') . "/models/sql/";
			if (is_dir($dir))
				foreach (scandir($dir) as $filename)
				{
					$path_info = pathinfo($filename);
					if ($path_info['extension'] == 'sql')
						$models[] = array('dir'=>$dir, 'info'=> $path_info, 'source'=>'system');
				}
			
			$db = new MySQL();
			$rs = $db->query("SHOW TABLES");
			$tables = array();
			while ($row = $db->fetch_array($rs))
			{
				$tables[] = $row[0];
			}
			
			foreach ($models as $id=>$model)
			{
				$result = array_search($model['info']['filename'], $tables);
				if ($result !== FALSE) $models[$id]['status'] = 'ok';
				else $models[$id]['status'] = '';
			}
			
			$this->template = new View('fwsetup_layout');
			$this->template->action = 'database';
			$this->template->content = new View('fwsetup_database');
			$this->template->content->models = $models;
			if($this->msg) $this->template->content->msg = $this->msg;
			$this->template->render();
		}
		
		function settings()
		{
			require Vault::$app_root_path . '/settings.php';
			$this->template = new View('fwsetup_layout');
			$this->template->action = 'settings';
			$this->template->content = new View('fwsetup_settings');
			$this->template->content->settings = $settings;
			$this->template->render();
		}
	}
?>
