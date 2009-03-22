<?php
	require_once('controllers/controller.Model.php');
	
	class Usuario extends Model {
		
		private static $db;
		
		var $fieldDefs = array(
			'id'=>array('input'=>'hidden'),
			'login'=>array('caption'=>'Login'),
			'senha'=>array('caption'=>'Senha'),
			'nivel'=>array('caption'=>'Nivel', 'input'=>'select', 'values'=>array('Usuário'=>1, 'Administrador'=>2)),
			'nome'=>array('caption'=>'Nome'),
			'email'=>array('caption'=>'E-mail'),
			'allowmail'=>array('caption'=>'Permite envio de E-mails', 'input'=>'radio', 'values'=>array('Sim'=>1, 'Não'=>0)),
			'ultimoacesso'=>array('caption'=>'Último Acesso')
		);
		
		function __construct() {
			parent::__construct('usuario');
		}
		
	}
?>
