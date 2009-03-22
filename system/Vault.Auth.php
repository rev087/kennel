<?php
	require_once('controllers/controller.Model.php');
	
	class Auth {
		static $user;
		static $error;
		static $message;
		
		function login($login, $pass) {
			$auth = Model::get('usuario', array('login'=>$login, 'senha'=>$pass));
			if($auth) {
				self::$user = $auth[0];
				if(!session_id()) session_start();
				$_SESSION['user_id'] = self::$user->id;
				return true;
			} else {
				self::$error = "Usuário ou senha inválidos";
				return false;
			}
		}
		
		function logout() {
			if(!session_id()) session_start();
			unset($_SESSION['user_id']);
			Auth::$message = "Logout efetuado";
		}
		
		function gtfo() {
			Auth::$error = "Você não tem acesso à esta área ou sua sessão expirou";
			die(Auth::$error);
			exit;
		}
		
		function check() {
			if(!session_id()) session_start();
			if(!$_SESSION['user_id']) return false;
			
			$args = func_get_args();
			$user = self::getUser();
			foreach($args as $arg) {
				if($arg == $user->nivel) return true;
			}
			
			return false;
		}
		
		function getUser() {
			if(self::$user) {
				return self::$user;
			} elseif($_SESSION['user_id']) {
				$user = Model::get('usuario', array('id'=>$_SESSION['user_id']));
				return self::$user = $user[0];
			} else {
				return false;
			}
		}
		
	}
?>