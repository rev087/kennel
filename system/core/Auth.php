<?php
	class Auth
	{
		static $user;
		static $error;
		static $message;
		
		/*
		* Auth::login(string $username, string $password, boolean $remember_me);
		* Attempt to login the user, with th given 
		* 
		* $username - the username
		* $password - the password, not cryptographed
		* $remember_me - if true, will set a cookie with the user authentication
		*/
		function login($username, $password, $remember_me=false)
		{
			$model_name = Kennel::getSetting('auth', 'model_name');
			$username_field = Kennel::getSetting('auth', 'username_field');
			$password_field = Kennel::getSetting('auth', 'password_field');
			
			$c = new Criteria($model_name);
			$c->add($username_field, $username);
			$c->add($password_field, $password);
			
			$users = ORM::retrieve($c);
			
			if (isset($users[0]))
			{
				self::$user = $users[0];
				if(!session_id()) session_start();
				$app_id = Kennel::getSetting('application', 'app_id');
				$_SESSION["{$app_id}_auth"] = self::$user->toArray();
				
				return true;
			}
			else {
				return false;
			}
		}
		
		/*
		* Auth::logout()
		*/
		function logout()
		{
			if(!session_id()) session_start();
			
			$app_id = Kennel::getSetting('application', 'app_id');
			unset($_SESSION["{$app_id}_auth"]);
			
			return true;
		}
		
		function gtfo()
		{
			Auth::$error = "Você não tem acesso à esta área ou sua sessão expirou";
			die(Auth::$error);
			exit;
		}
		
		function check()
		{
			if(!session_id()) session_start();
			$app_id = Kennel::getSetting('application', 'app_id');
			if(!array_key_exists("{$app_id}_auth", $_SESSION)) return false;
			
			$args = func_get_args();
			if(count($args) > 0)
			{
				$user = self::getUser();
				$app_id = Kennel::getSetting('application', 'app_id');
				$userlevel_field = Kennel::getSetting("{$app_id}_auth", 'userlevel_field');
				foreach($args as $arg) {
					if($arg == $user->$userlevel_field) return true;
				}
			} else {
				return true;
			}
			
			return false;
		}
		
		function getUser() 
		{
			$app_id = Kennel::getSetting('application', 'app_id');
			
			if (self::$user)
			{
				return self::$user;
			}
			elseif ($_SESSION["{$app_id}_auth"])
			{
				self::$user = new Model(Kennel::getSetting('auth', 'model_name'));
				self::$user->fromArray($_SESSION["{$app_id}_auth"]);
				return self::$user;
			}
			else
				return false;
		}
		
		function updateUser($user)
		{
			if(!session_id()) session_start();
			$app_id = Kennel::getSetting('application', 'app_id');
			$_SESSION["{$app_id}_auth"] = $user->toArray();
			self::$user = $user;
		}
		
	}
?>
