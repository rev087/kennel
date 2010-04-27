<?php
	
	class Request {
		public static $PARTS = array();
		public static $ARGS = array();
		public static $NAMED_ARGS = array();
		
		public static $RESOURCE;
		public static $CONTROLLER;
		public static $ACTION;
		
		private static $HOOKS;
		
		static function isAjax()
		{
			if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
				return true;
			else
				return false;
		}
		
		static function isIPhone()
		{
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') > 0 || strpos($_SERVER['HTTP_USER_AGENT'], 'iPod') > 0)
				return true;
			else
				return false;
		}
		
		// Return the value of a Named Paramater
		static function param($param)
		{
			if (isset(self::$NAMED_ARGS[$param]))
				return self::$NAMED_ARGS[$param];
			else
				return null;
		}
		
		static function referer()
		{
			return $_SERVER['HTTP_REFERER'];
		}
		
		/*
		* Hooks
		*/
		static function hook($callback)
		{
			self::$HOOKS[] = $callback;
		}
		
		/*
		* Request::process()
		*/
		static function process()
		{
			// Get the request parts
			$request_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			if(Kennel::getSetting('application', 'use_mod_rewrite'))
				$action_string = substr(trim($request_url, '/'), strlen(Kennel::$ROOT_URL));
			else
				$action_string = substr(trim($request_url, '/'), strlen(Kennel::$ROOT_URL . '/index.php'));
			$action_string = str_replace(strstr($action_string, '?'), '', $action_string);
			$action_array = array_filter(explode('/', $action_string));
			
			// Reasign action keys (to avoid empty entries due to double slashes) and convert to lowercase
			foreach ($action_array as $key=>$part)
			{
				if ($part)
				{
					if (strpos($part, ':') === false)
						self::$PARTS[] = strtolower(Input::clean($part));
					else
					{
						$named_arg = explode(':', $part);
						self::$NAMED_ARGS[$named_arg[0]] = $named_arg[1];
					}
					
				}
			}
			
			// Process any hooks if present
			if (is_array(self::$HOOKS) && count(self::$HOOKS > 0))
			{
				foreach (self::$HOOKS as $hook)
					self::$PARTS = call_user_func($hook, self::$PARTS);
			}
			
			// Make the Resrouce String available to the API
			self::$RESOURCE = implode('/', self::$PARTS);
			
			// i18n URL redirection
			if (Kennel::getSetting('i18n', 'enabled') && Kennel::getSetting('i18n', 'redirect') && !router::$PREFIX)
				header('location: ' . url(Request::$RESOURCE, i18n::getLang()));
			
			// 0. Render the Home Page if no Request::PARTS are present
			if (count(self::$PARTS) == 0)
			{
				self::$CONTROLLER = 'Main';
				self::$ACTION = 'index';
			}
			
			// 1. First check: method in the main controller
			if (isset(self::$PARTS[0]) && method_exists('Main_controller', self::$PARTS[0]))
			{
				self::$CONTROLLER = 'main';
				self::$ACTION = self::$PARTS[0];
				self::$ARGS = array_slice(self::$PARTS, 1);
			}
			
			// 2. Second check: user defined controller...
			if (isset(self::$PARTS[0]) && is_file(Kennel::$ROOT_PATH . '/application/controllers/' . self::$PARTS[0] . '.php'))
			{
				self::$CONTROLLER = ucfirst(self::$PARTS[0]);
				if (isset(self::$PARTS[1]) && method_exists(self::$CONTROLLER . '_controller', self::$PARTS[1]))
				{
					self::$ACTION = self::$PARTS[1];
					self::$ARGS = array_slice(self::$PARTS, 2);
				}
				else
				{
					self::$ACTION = 'index';
					self::$ARGS = array_slice(self::$PARTS, 1);
				}
			}
			
			// 3. Third check: module controller
			if(isset(self::$PARTS[0]))
			{
				if (!Kennel::$MODULES) Kennel::fetchModules();
				foreach (Kennel::$MODULES as $module=>$info)
				{
					if(is_file(Kennel::$ROOT_PATH . "/modules/{$module}/controllers/" . self::$PARTS[0] . '.php'))
					{
						self::$CONTROLLER = ucfirst(self::$PARTS[0]);
						if (isset(self::$PARTS[1]) && method_exists(self::$CONTROLLER . '_controller', self::$PARTS[1]))
						{
							self::$ACTION = self::$PARTS[1];
							self::$ARGS = array_slice(self::$PARTS, 2);
						}
						else
						{
							self::$ACTION = 'index';
							self::$ARGS = array_slice(self::$PARTS, 1);
						}
					}
				}			
			}
			
			// 4. Forth check: system controller
			if(isset(self::$PARTS[0]) && is_file(Kennel::$ROOT_PATH .'/system/controllers/' . self::$PARTS[0] . '.php'))
			{
				self::$CONTROLLER = ucfirst(self::$PARTS[0]);
				if (isset(self::$PARTS[1]))
				{
					if (method_exists(self::$CONTROLLER . '_controller', self::$PARTS[1]))
					{
						self::$ACTION = self::$PARTS[1];
						self::$ARGS = array_slice(self::$PARTS, 2);
					}
				}
				else
				{
					self::$ACTION = 'index';
					self::$ARGS = array_slice(self::$PARTS, 1);
				}
			}
			
			// 5. Fifth check: nothing found
			if(!self::$CONTROLLER)
				self::$CONTROLLER = 'Main';
			if(!self::$ACTION)
				self::$ACTION = 'notfound';
			if(self::$CONTROLLER == 'Main' && self::$ACTION == 'notfound')
				self::$ARGS = self::$PARTS;
			
			return Kennel::controllerAction(self::$CONTROLLER, self::$ACTION, self::$ARGS);
		}
		
		static function dump($return=false)
		{
			$table = XML::element('table', null, array(
				'border'=>'1', 'style'=>'background-color: #FFF; color: #000;'
			));
			
			$tr = XML::element('tr', $table);
			$td = XML::element('td', $tr, array('style'=>'font-weight: bold;'), 'Controller');
			$td = XML::element('td', $tr, null, self::$CONTROLLER);
			
			$tr = XML::element('tr', $table);
			$td = XML::element('td', $tr, array('style'=>'font-weight: bold;'), 'Action');
			$td = XML::element('td', $tr, null, self::$ACTION);
			
			$tr = XML::element('tr', $table);
			$td = XML::element('td', $tr, array('style'=>'font-weight: bold;'), 'Arguments');
			$td = XML::element('td', $tr, null, Debug::dump(self::$ARGS, true));
			
			if($return) return $table;
			else print $table;
		}
		
	}
	
?>
