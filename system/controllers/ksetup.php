<?php
	class Ksetup_controller extends Controller
	{
		private static $DB;
		var $msg;
		
		function __construct()
		{
			$this->template = new View('ksetup_layout');
		}
		
		public function index()
		{
			$this->modules();
		}
		
		public function login()
		{
			if (input::post('username') && input::post('password') &&
			auth::login(input::post('username'), input::post('password')))
			{
				$this->index();
			}
			else
			{
				return $this->access_denied();
			}
		}
		
		private function access_denied()
		{
			$this->template->content = new View('ksetup_access_denied');
			$this->template->render();
			exit();
		}
		
		public function firststeps()
		{
			$view = new View('ksetup_firststeps');
			$view->render();
			exit();
		}
		
		public function startpage()
		{
			$this->template->content = new View('ksetup_startpage');
			$this->template->render();
		}
		
		public function modules()
		{
			if (!auth::check()) $this->access_denied();
			
			$this->template->action = 'modules';
			$this->template->content = new View('ksetup_modules');
			$this->template->modules = Kennel::$MODULES;
			$this->template->render();
		}
		
		public function createmodels()
		{
			if (!auth::check()) $this->access_denied();
			
			$created = 0;
			$models = $this->getModels();
			
			foreach ($models as $id=>$model)
			{
				if (!$model['status'])
				{
					$created++;
					$filename = substr($model['info']['basename'], 0, strpos($model['info']['basename'], '.xml'));
					ORM::create($filename);
				}
			}
			
			$this->msg = "<strong>{$created} models</strong> created successfuly.";
			$this->database();
		}
		
		public function database()
		{
			if (!auth::check()) $this->access_denied();
			
			$models = self::getModels();
			
			$this->template->action = 'database';
			$this->template->content = new View('ksetup_database');
			$this->template->models = $models;
			if($this->msg) $this->template->msg = $this->msg;
			$this->template->render();
		}
		
		public function settings()
		{
			if (!auth::check()) $this->access_denied();
			
			require Kennel::$ROOT_PATH . '/settings.php';
			$this->template->action = 'settings';
			$this->template->content = new View('ksetup_settings');
			$this->template->settings = $settings;
			$this->template->render();
		}
		
		private function checkModel($model)
		{
		/*
			$schema = ORM::getSchema($model);
			$sql = "DESC `$schema->table`";
			
			$rs = self::$DB->query($sql);
			
			$schema = ORM::getSchema($model);
			print $schema->getCreateString();
			
			// compare existing database Table to Schema
			while ($row = self::$DB->fetch($rs))
			{
				print '<br />';
				print ' <strong>field:</strong>';
				print $row->Field;
				print ' <strong>type:</strong>';
				print $row->Type;
				print ' <strong>null:</strong>';
				print $row->Null;
				print '<br />';
			}
			
			print '<hr />';
		*/
		}
		
		private function getModels()
		{
			$models = array();
			
			// User Models
			$dir = Kennel::getPath() . '/application/models/';
			if (is_dir($dir))
				foreach (scandir($dir) as $filename)
				{
					$path_info = pathinfo($filename);
					if ($path_info['extension'] && $path_info['extension'] == 'xml')
						$models[] = array('dir'=>$dir, 'info'=> $path_info, 'source'=>'application');
				}
			
			// Module Models
			foreach (Kennel::$MODULES as $module)
			{
				$dir = Kennel::getPath() . "/modules/{$module['id']}/models/";
				if (is_dir($dir))
					foreach (scandir($dir) as $filename)
					{
						$path_info = pathinfo($filename);
						if (isset($path_info['extension']) && $path_info['extension'] == 'xml')
							$models[] = array('dir'=>$dir, 'info'=> $path_info, 'source'=> $module['id']);
					}
			}
			
			// System Models
			$dir = Kennel::getPath() . "/system/models/";
			if (is_dir($dir))
				foreach (scandir($dir) as $filename)
				{
					$path_info = pathinfo($filename);
					if ($path_info['extension'] && $path_info['extension'] == 'xml')
						$models[] = array('dir'=>$dir, 'info'=> $path_info, 'source'=>'system');
				}
			
			self::$DB = new MySQL();
			$rs = self::$DB->query("SHOW TABLES");
			$tables = array();
			while ($row = self::$DB->fetch_array($rs))
			{
				$tables[] = $row[0];
			}
			
			foreach ($models as $id=>$model)
			{
				//self::checkModel($model['info']['filename']);
				// FILENAME was only introduced in PHP 5.2 and Terra Empresas is gay
				$filename = substr($model['info']['basename'], 0, strpos($model['info']['basename'], '.xml'));
				$schema = ORM::getSchema($filename);
				$result = array_search($schema->table, $tables);
				if ($result !== FALSE) $models[$id]['status'] = 'ok';
				else $models[$id]['status'] = '';
			}
			
			return $models;
		}
		
		public function backup()
		{
			if (!auth::check()) $this->access_denied();
			
			$dump = "";
			
			$models = $this->getModels();
			foreach ($this->getModels() as $model)
			{
				$model_name = substr($model['info']['basename'], 0, strpos($model['info']['basename'], '.xml'));
				$schema = ORM::getSchema($model_name);
				
				// Drop the table
				$dump .= "DROP TABLE IF EXISTS `{$schema->table}`;\n";
				
				// Create the table
				$create_sql = $schema->getCreateString();
				$dump .= "{$create_sql}\n\n";
				
				// Dump the table data
				$model_instances = ORM::retrieveAll($model_name);
				foreach ($model_instances as $instance)
				{
					$insert_query = $instance->getSaveQuery(true);
					$dump .= "{$insert_query}\n";
				}
				$dump .= "\n";
			}
			
			$this->template->content = new View('ksetup_backup');
			$this->template->action = 'backup';
			$this->template->dump = $dump;
			$this->template->render();
		}
	}
?>
