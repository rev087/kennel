<?php
	require_once('controllers/controller.Model.php');
	
	class Funcionario extends Model {
		
		private static $db;
		
		var $fieldDefs = array(
			'codigofuncionario'=>array('caption'=>'Código do funcionário'),
			'bairro'=>array('caption'=>'Bairro'),
			'admissao'=>array('caption'=>'Admissão', 'input'=>'date'),
			'demissao'=>array('caption'=>'Demissão', 'input'=>'date')
		);
		
		function __construct() {
			parent::__construct('funcionario');
		}
		
		function delete() {
			switch($_GET['page']) {
				case 'adm/matriculas';
				case 'adm/funcionarios';
					self::$db->query("DELETE FROM {$this->model_name} WHERE " . join(" AND ", $this->sync_values));
					unset($this->fields);
					break;
				case 'adm/usuarios';
					break;
			}
		}
		
		static function login($login, $pass) {
			if(!self::$db) self::$db = new MySQL();
			
			$auth = self::get('user', array('login'=>$login, 'senha'=>$pass));
			return $auth[0];
		}
		
		
		
	}
?>
