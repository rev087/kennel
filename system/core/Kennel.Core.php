<?php

	/*
	* __autoload(string $resource)
	* Automatically requires Controllers and Kennel System files
	*/
	function __autoload($resource)
	{
		// Controllers
		if (substr($resource, -11) == '_controller')
		{
			// Clear the controller name
			$controller_name = strtolower(substr($resource, 0, (strlen($resource) - 11)));
			
			// User Controller
			if (is_file(Kennel::getPath('controllers') . "/{$controller_name}.php"))
			{
				require_once Kennel::getPath('controllers') . "/{$controller_name}.php"; return;
			}
			
			// Module Controller
			if(!Kennel::$modules) Kennel::fetchModules();
			foreach(Kennel::$modules as $module=>$info)
			{
				if(is_file(Kennel::getPath('modules') . "/{$module}/controllers/{$controller_name}.php"))
				{
					require_once Kennel::getPath('modules') . "/{$module}/controllers/{$controller_name}.php"; return;
				}
			}
			
			// System Controller
			if(is_file(Kennel::getPath('system') . "/controllers/{$controller_name}.php"))
			{
				require_once Kennel::getPath('system') . "/controllers/{$controller_name}.php"; return;
			}
		}
		
		// Helpers
		if ($resource == strtolower($resource))
		{
			// User Helper
			if (is_file(Kennel::getPath('helpers') . "/{$resource}.php"))
			{
				require_once Kennel::getPath('helpers') . "/{$resource}.php"; return;
			}
			
			// Module Helper
			if(!Kennel::$modules) Kennel::fetchModules();
			foreach(Kennel::$modules as $module=>$info)
			{
				if(is_file(Kennel::getPath('modules') . "/{$module}/helpers/{$resource}.php"))
				{
					require_once Kennel::getPath('modules') . "/{$module}/helpers/{$resource}.php"; return;
				}
			}
			
			// System Helper
			if(is_file(Kennel::getPath('system') . "/helpers/{$resource}.php"))
			{
				require_once Kennel::getPath('system') . "/helpers/{$resource}.php"; return;
			}
		}
		
		// Models
		if (substr($resource, -6) == '_model')
		{
			// Clear the model name
			$model_name = strtolower(substr($resource, 0, (strlen($resource) - 6)));
			
			// User Model
			if (is_file(Kennel::getPath('models') . "/{$model_name}.php"))
			{
				require_once Kennel::getPath('models') . "/{$model_name}.php"; return;
			}
			
			// Module Model
			if(!Kennel::$modules) Kennel::fetchModules();
			foreach(Kennel::$modules as $module=>$info)
			{
				if(is_file(Kennel::getPath('modules') . "/{$module}/models/{$model_name}.php"))
				{
					require_once Kennel::getPath('modules') . "/{$module}/models/{$model_name}.php"; return;
				}
			}
			
			// System Model
			if(is_file(Kennel::getPath('system') . "/models/{$model_name}.php"))
			{
				require_once Kennel::getPath('system') . "/models/{$model_name}.php"; return;
			}
		}
		
		// System Core Resources
		if (is_file(Kennel::getPath('system') . "/core/Kennel.".ucfirst($resource).".php"))
		{
			require_once Kennel::getPath('system') . "/core/Kennel.".ucfirst($resource).".php";
		}
	}
	
	/*
	* url(string $action)
	* Returns a Kennel formated url.
	* @action - the controller and actions. An example could be "blog/post".
	*/
	function url($action=null) {
		if(isset($action))
		{
			if(Kennel::getSetting('application', 'use_mod_rewrite'))
				$url= Kennel::$app_root_uri . "/{$action}";
			else
				$url= Kennel::$app_root_uri . '/index.php/' . action;
		}
		else 
		{
			$url = Kennel::$app_root_uri;
		}
		
		return $url;
	}
	
	/*
	 * The magic starts here.
	 */
	class Kennel {
		
		static $app_settings;
		static $app_root_path;
		static $app_root_uri;
		
		static $modules;
		
		static $request_query_string;
		static $request_uri;
		
		static $app_main_controller;
		static $controller_instance;
		
		static $time_init;
		static $time_final;
		
		/*
		* Kennel::init()
		*/
		static function init() {
			//begin the benchmark
			self::$time_init = microtime(true);
			register_shutdown_function(array("Kennel","onShutdown"));
			
			//get the application path and root uri
			self::$app_root_path = dirname($_SERVER["SCRIPT_FILENAME"]);
			self::$app_root_uri = trim("http://{$_SERVER['HTTP_HOST']}", '\\/') . '/' . trim(substr(self::$app_root_path, strlen($_SERVER['DOCUMENT_ROOT'])), '\\/');
			
			//get the application settings
			require_once('settings.php');
			self::$app_settings = $settings;
			
			//process the request
			self::processRequest();
		}
		
		/*
		* Kennel::onShutdown()
		*/
		static function onShutdown() {
			if(self::getSetting('application', 'show_benchmark')) self::printBenchmark();
		}
		
		/*
		* Kennel::printBenchmark();
		*/
		static function printBenchmark() {
			//finish the benchmark
			self::$time_final = microtime(true);
			print '<br /><small style="color: #666"><p>Finished in <strong>' . (self::$time_final - self::$time_init) . '</strong> seconds</p>';
			print '<p>Using <strong>' . (memory_get_usage() / 1024) . '</strong> kbs</p></small>';
		}
		
		/*
		* Kennel::getSetting(str $category, str $setting)
		*/
		static function getSetting($category, $setting) {
			return self::$app_settings[$category][$setting];
		}
		
		/*
		* Kennel::getPath(str $directory)
		*/
		static function getPath($directory='') {
			return self::$app_root_path . self::getSetting('path', $directory);
		}
		
		/*
		* Kennel::controllerAction(misc $controller, string $action, array $args);
		*/
		static function controllerAction($controller, $action='index', $args=null)
		{
			// Accepts both strings and objects
			if(is_string($controller))
			{
				// Format the controller class name
				$controller_class = ucfirst($controller) . "_controller";
				
				// Make the controller name available to the API
				Request::$controller = strtolower($controller);
				
				// Makes sure only one instace of the main controller is instantiated
				if($controller == 'main')
					self::$controller_instance = self::$app_main_controller;
				else
					self::$controller_instance = Controller::getInstance($controller_class);
			} else {
				// Set the controller as the current controller instance
				self::$controller_instance = $controller;
				
				// Make the controller name available to the API
				$controller_class = get_class($controller);
				Request::$controller = strtolower(substr($controller_class, -11));
			}
			
			// Make sure $action is defined
			if(!$action) $action = 'index';
			
			// Makes the action name available to the API
			Request::$action = strtolower($action);
			
			// Check for the existance of the action as a method in the controller class
			if(method_exists(self::$controller_instance, Request::$action))
			{
				// Call the method
				if(is_array($args))
					call_user_func_array(array(self::$controller_instance, Request::$action), $args);
				else 
					call_user_func(array(self::$controller_instance, Request::$action));
			}
			// Workaround in case the action does not exist
			else
			{
				if(is_array($args))
					$args = array_merge(array(Request::$action), $args);
				
				// Select the 'notfound' method if present, or the 'index' method if not
				if(method_exists(self::$controller_instance, 'notfound'))
					Request::$action = 'notfound';
				else
					Request::$action = 'index';
				
				// Call the workaround method
				if(is_array($args))
					call_user_func_array(array(self::$controller_instance, Request::$action), $args);
				else
					call_user_func(array(self::$controller_instance, Request::$action));
			}
		}
		
		/*
		* Kennel::isModuleController(string $controller)
		*/
		static function fetchModules()
		{
			// Initialize the variable
			self::$modules = array();
			
			// Get through the file list in the modules directory
			$files = scandir(self::getPath('modules'));
			foreach ($files as $file)
			{
				// Get only valid directories
				if (is_dir(Kennel::getPath('modules') . '/' . $file) &&
					$file != '.' && $file != '..' && $file != '.svn')
					{
						include Kennel::getPath('modules') . "/{$file}/info.php";
						self::$modules[$file] = $info[$file];
					}
			}
		}
		
		/*
		* Kennel::processRequest()
		*/
		static function processRequest()
		{
			// Instantialize the main controller
			self::$app_main_controller = Controller::getInstance('Main_controller');
			
			// Get the request args
			if(self::getSetting('application', 'use_mod_rewrite'))
			{
				$app_root_uri = substr(self::$app_root_path, strlen($_SERVER['DOCUMENT_ROOT']));
				$request_string = substr(trim($_SERVER['REQUEST_URI'], '/'), strlen($app_root_uri));
				$action_string = str_replace(strstr($request_string, '?'), '', $request_string);
				
				$action_array = array_filter(explode('/', $action_string));
			}
			else
			{
				//todo: check this
				self::$request_uri = $_SERVER['QUERY_STRING'];
				$action_array = array_filter(explode('/', self::$request_uri));
			}

			// Reasign action keys (to avoid empty entries due to double slashes) and convert to lowercase
			$action_args = array();
			foreach ($action_array as $key=>$value)
			{
				if($value) $action_args[] = strtolower($value);
			}
			
			// Display the Home page if no action_args are suplied
			if (count($action_args) == 0)
			{
				self::controllerAction('main', 'index'); return;
				return;
			}
			
			// 1. First check: method in the main controller
			if (method_exists(self::$app_main_controller, $action_args[0]))
			{
				self::controllerAction('main', array_shift($action_args), $action_args); return;
			}
				
			// 2. Second check: user defined controller...
			if (is_file(self::getPath('controllers')."/{$action_args[0]}.php"))
			{
				$action = isset($action_args[1])?$action_args[1]:null;
				self::controllerAction($action_args[0], $action, array_slice($action_args, 2)); return;
			}
			
			// 3. Third check: module controller
			if (!self::$modules) self::fetchModules();
			$controller_filename = strtolower($action_args[0]) . '.php';
			foreach (self::$modules as $module=>$info)
			{
				if(is_file(self::getPath('modules') . "/{$module}/controllers/{$controller_filename}"))
				{
					if (count($action_args)==1)
						self::controllerAction($action_args[0]);
					else
					{
						if (method_exists($action_args[0], $action_args[1]))
							self::controllerAction($action_args[0], $action_args[1], array_slice($action_args, 2));
						else
							self::controllerAction($action_args[0], 'index', array_slice($action_args, 1));
					}
					return;
				}
			}
			
			// 4. Forth check: system controller
			if(is_file(self::getPath('system')."/controllers/{$action_args[0]}.php"))
			{
				if (count($action_args)==1) self::controllerAction($action_args[0]);
				else self::controllerAction($action_args[0], $action_args[1], array_slice($action_args, 2));
				return;
			}
				
			// If the first request argument is not a method of the main controller nor a controller, send 404
			if(count($action_args) > 0) self::controllerAction(self::$app_main_controller, 'notfound', array_slice($action_args, 0));
			else self::controllerAction(self::$app_main_controller, 'notfound');
		}
		
	}
?>
