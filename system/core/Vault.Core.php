<?php

	/*
	* __autoload(string $resource)
	* Automatically requires Controllers and Vault System files
	*/
	function __autoload($resource) {
		// Controllers
		if (substr($resource, -11) == '_controller')
		{
			$controller_name = strtolower(substr($resource, 0, (strlen($resource) - 11)));
			//User Controller
			if (is_file(Vault::getPath('controllers') . "/{$controller_name}.php"))
				require_once Vault::getPath('controllers') . "/{$controller_name}.php";
			elseif(is_file(Vault::getPath('system') . "/controllers/{$controller_name}.php"))
			{
				require_once Vault::getPath('system') . "/controllers/{$controller_name}.php";
			}
		}
		// System Core Resource
		elseif (is_file(Vault::getPath('system') . "/core/Vault.".ucfirst($resource).".php"))
		{
			require_once Vault::getPath('system') . "/core/Vault.".ucfirst($resource).".php";
		}
	}
	
	/*
	* url(string $action)
	* Returns a Vault formated url.
	* @action - the controller and actions. An example could be "blog/post".
	*/
	function url($action) {
		if(Vault::getSetting('application', 'use_mod_rewrite')) {
			return Vault::$app_root_uri . "/{$action}";
		} else {
			return Vault::$app_root_uri . '/index.php/' . action;
		}
	}
	
	class Vault {
		
		static $app_settings;
		static $app_root_path;
		static $app_root_uri;
		static $app_main_controller;
		
		static $request_query_string;
		static $request_uri;
		
		static $controller;
		static $action;
		
		static $time_init;
		static $time_final;
		
		/*
		* Vault::init()
		*/
		static function init() {
			//begin the benchmark
			self::$time_init = microtime(true);
			register_shutdown_function(array("Vault","onShutdown"));
			
			//get the application path and root uri
			self::$app_root_path = dirname($_SERVER["SCRIPT_FILENAME"]);
			self::$app_root_uri = "http://{$_SERVER['HTTP_HOST']}/" . substr(self::$app_root_path, strlen($_SERVER['DOCUMENT_ROOT']));
			
			//get the application settings
			require_once('settings.php');
			self::$app_settings = $settings;
			
			//process the request
			self::processRequest();
		}
		
		/*
		* Vault::onShutdown()
		*/
		static function onShutdown() {
			if(self::getSetting('application', 'show_benchmark')) self::printBenchmark();
		}
		
		/*
		* Vault::printBenchmark();
		*/
		static function printBenchmark() {
			//finish the benchmark
			self::$time_final = microtime(true);
			print '<br /><small style="color: #666"><p>Finished in <strong>' . (self::$time_final - self::$time_init) . '</strong> seconds</p>';
			print '<p>Using <strong>' . (memory_get_usage() / 1024) . '</strong> kbs</p></small>';
		}
		
		/*
		* Vault::getSetting(str $category, str $setting)
		*/
		static function getSetting($category, $setting) {
			return self::$app_settings[$category][$setting];
		}
		
		/*
		* Vault::getPath(str $directory)
		*/
		static function getPath($directory='') {
			return self::$app_root_path . self::getSetting('path', $directory);
		}
		
		/*
		* Vault::requireSystemFile(str $file)
		*/
		static function requireSystemFile($file) {
			$file = ucfirst($file);
			require_once(self::getPath('system') . "/core/Vault.$file.php");
		}
		
		/*
		* Vault::requireResource(str $file)
		*/
		static function requireResource($resource_type, $resource_name) {
			switch($resource_type) {
				case 'controller':
					$resource_name = strtolower($resource_name);
					require_once(self::getPath('controllers') . "/$resource_name.php");
					break;
				case 'view':
					require_once(self::getPath('views') . "/$resource_name.php");
					break;
				case 'template':
					break;
			}
		}
		
		/*
		* Vault::getResourcePath(string $resource_type, string $resource_name);
		*/
		static function getResourcePath($resource_type, $resource_name) {
			switch($resource_type) {
				case 'controller':
					$resource_name = strtolower($resource_name);
					return self::getPath('controllers') . "/$resource_name.php";
				case 'view':
					return self::getPath('views') . "/$resource_name.php";
				case 'template':
					break;
			}
		}
		
		/*
		* Vault::processRequest()
		*/
		static function processRequest() {
			// Instantialize the main controller
			self::requireSystemFile('controller');
			self::requireResource('controller', 'main');
			self::$app_main_controller = new Main_controller();
			
			// Get the request args
			if(self::getSetting('application', 'use_mod_rewrite'))
			{
				$app_root_uri = substr(self::$app_root_path, strlen($_SERVER['DOCUMENT_ROOT']));
				$request_string = substr(trim($_SERVER['REQUEST_URI'], '/'), strlen($app_root_uri));
				$action_string = str_replace(strstr($request_string, '?'), '', $request_string);
				
				$action_array = array_filter(explode('/', $action_string));
				
			} else
			{
				self::$request_uri = $_SERVER['QUERY_STRING'];
				$action_array = array_filter(explode('/', self::$request_uri));
			}

			// Reasign action keys and convert to lowercase
			foreach($action_array as $key=>$value) {
				if($value)
				$action_args[] = strtolower($value);
			}
			
			// Display the Home page if no action_args are suplied
			if(count($action_args) == 0)
			{
				self::$app_main_controller->index();
			}
			// 1. First check: method in the main controller
			elseif(method_exists(self::$app_main_controller, $action_args[0]))
			{
				call_user_func_array(array(&self::$app_main_controller, array_shift($action_args)), $action_args);
				
			}
			//2. Second check: user defined controller...
			elseif(is_file(self::getPath('controllers')."/{$action_args[0]}.php"))
			{
				self::requireResource('controller', $action_args[0]);
				$controller_name = "{$action_args[0]}_controller";
				$controller = new $controller_name();
				
				//...index...
				if(count($action_args) == 1 || !method_exists($controller, $action_args[1]))
					call_user_func_array(array($controller, 'index'), array_slice($action_args, 2));
				
				//...or specified method (a second request arg is present and exists as method)
				elseif(count($action_args) > 1 && method_exists($controller, $action_args[1]))
					call_user_func_array(array($controller, $action_args[1]), array_slice($action_args, 2));
			}
			//3. Third check: system controller
			elseif(is_file(self::getPath('system')."/controllers/{$action_args[0]}.php"))
			{
				require self::getPath('system')."/controllers/{$action_args[0]}.php";
				$controller_name = "{$action_args[0]}_controller";
				$controller = new $controller_name();
				
				//...index... (no second request arg or doesn't match any methods)
				if(count($action_args) == 1 || !method_exists($controller, $action_args[1]))
					call_user_func_array(array($controller, 'index'), array_slice($action_args, 2));
				
				//...or specified method (a second request arg is present and matches a method)
				elseif(count($action_args) > 1 && method_exists($controller, $action_args[1]))
					call_user_func_array(array($controller, $action_args[1]), array_slice($action_args, 2));
			}
			//if the first request argument is not a method of the main controller nor a controller, send 404
			else
			{
				if(method_exists(self::$app_main_controller, 'notfound'))
					call_user_func(array(self::$app_main_controller, 'notfound'));
				else
					call_user_func(array(self::$app_main_controller, 'index'));
			}
		}
		
		/*
		* Vault::debugRequest()
		*/
		static function debugRequest() {
			print '<table><tr><td>APP_ROOT_PATH:</td><td>';
			var_dump(self::$app_root_path);
			print '</td></tr>';
			print '<tr><td>REQUEST_URI:</td><td>';
			var_dump(self::$request_uri);
			print '</td></tr>';
			print '<tr><td>HTTP_HOST:</td><td>';
			var_dump($_SERVER['HTTP_HOST']);
			print '</td></tr>';
			print '<tr><td>DOCUMENT_ROOT:</td><td>';
			var_dump($_SERVER['DOCUMENT_ROOT']);
			print '</td></tr></table>';
		}
		
		/*
		* Vault::dump($variable)
		*/
		static function dump($variable, $return=false) {
			$dump = '<pre>'.var_export($variable, true).'</pre>';
			if($return) return $dump;
			else print $dump;
		}
		
	}
?>
