<?php
	class Ksetup_controller extends Controller
	{
		private static $DB;
		var $msg;
		
		function __construct()
		{
			$this->template = new View('ksetup_layout');
		}
		
		function index()
		{
			$this->modules();
		}
		
		function startpage()
		{
			$this->template->content = new View('ksetup_startpage');
			$this->template->modules = Kennel::$MODULES;
			$this->template->render();
		}
		
		function modules()
		{
			$this->template->action = 'modules';
			$this->template->content = new View('ksetup_modules');
			$this->template->modules = Kennel::$MODULES;
			$this->template->render();
		}
		
		function createmodels()
		{
			$created = 0;
			$models = $this->getModels();
			
			foreach ($models as $id=>$model)
			{
				if (!$model['status'])
				{
					$created++;
					ORM::create($model['info']['filename']);
				}
			}
			
			$this->msg = "<strong>{$created} models</strong> created successfuly.";
			$this->database();
		}
		
		function database()
		{
			$models = self::getModels();
			
			$this->template->action = 'database';
			$this->template->content = new View('ksetup_database');
			$this->template->models = $models;
			if($this->msg) $this->template->msg = $this->msg;
			$this->template->render();
		}
		
		function settings()
		{
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
			$dir = Kennel::getPath() . '/models/';
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
				self::checkModel($model['info']['filename']);
				$schema = ORM::getSchema($model['info']['filename']);
				$result = array_search($schema->table, $tables);
				if ($result !== FALSE) $models[$id]['status'] = 'ok';
				else $models[$id]['status'] = '';
			}
			
			return $models;
		}
	}
?>
