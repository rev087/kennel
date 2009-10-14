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
			$model_name = Vault::getSetting('auth', 'model_name');
			$username_field = Vault::getSetting('auth', 'username_field');
			$password_field = Vault::getSetting('auth', 'password_field');
			
			$user = Model::getOne(
				$model_name,
				array($username_field=>$username, $password_field=>$password)
			);
			
			if ($user)
			{
				self::$user = $user;
				if(!session_id()) session_start();
				$_SESSION['auth'] = $user->toArray();
				
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
			unset($_SESSION['auth']);
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
			if(!array_key_exists('auth', $_SESSION)) return false;
			
			$args = func_get_args();
			if(count($args) > 0)
			{
				$user = self::getUser();
				$userlevel_field = Vault::getSetting('auth', 'userlevel_field');
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
			if (self::$user)
			{
				return self::$user;
			}
			elseif ($_SESSION['auth'])
			{
				self::$user = new Model(Vault::getSetting('auth', 'model_name'));
				self::$user->feed($_SESSION['auth']);
				return self::$user;
			}
			else
				return false;
		}
		
		function updateUser($user)
		{
			if(!session_id()) session_start();
			$_SESSION['auth'] = $user->toArray();
			self::$user = $user;
		}
		
	}
?>
