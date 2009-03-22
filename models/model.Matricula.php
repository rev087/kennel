<?php
	require_once('controllers/controller.Model.php');
	
	class User extends Model {
		
		private static $db;
		
		var $fieldDefs = array(
			'matricula'=>array('caption'=>'Matr�cula'),
			'nascimento'=>array('caption'=>'Data de Nascimento'),
			'cpf'=>array('caption'=>'CPF'),
			'cep'=>array('caption'=>'CEP'),
			'endereco'=>array('caption'=>'Endere�o'),
			'cidade'=>array('caption'=>'Cidade'),
			'estado'=>array('caption'=>'Estado', 'input'=>'select', 'values'=>array(
				'Acre'=>'AC',
				'Alagoas'=>'AL',
				'Amap�'=>'AP',
				'Amazonas'=>'AM',
				'Bahia'=>'BA',
				'Cear�'=>'CE',
				'Esp�rito Santo'=>'ES',
				'Goi�s'=>'GO',
				'Maranh�o'=>'MA',
				'Mato Grosso'=>'MT',
				'Mato Grosso do Sul'=>'MS',
				'Minas Gerais'=>'MG',
				'Par�'=>'PA',
				'Paran�'=>'PR',
				'Para�ba'=>'PB',
				'Pernambuco'=>'PE',
				'Piau�'=>'PI',
				'Rio de Janeiro'=>'RJ',
				'Rio Grande do Norte'=>'RN',
				'Rio Grande do Sul'=>'RS',
				'Rond�nia'=>'RO',
				'Roraima'=>'RR',
				'Santa Catarina'=>'SC',
				'S�o Paulo'=>'SP',
				'Sergipe'=>'SE',
				'Tocantins'=>'TO',
				'Distrito Federal'=>'DF'
			)),
			'pai'=>array('caption'=>'Pai'),
			'mae'=>array('caption'=>'M�e'),
			'telefone'=>array('caption'=>'Telefone'),
			'profissao'=>array('caption'=>'Profiss�o'),
			'divulgacao'=>array('caption'=>'Divulga��o'),
			'indicador'=>array('caption'=>'Indicador'),
			
			'codigofuncionario'=>array('caption'=>'C�digo do funcion�rio'),
			'bairro'=>array('caption'=>'Bairro'),
			'admissao'=>array('caption'=>'Admiss�o', 'input'=>'date'),
			'demissao'=>array('caption'=>'Demiss�o', 'input'=>'date')
		);
		
		function __construct() {
			parent::__construct('user');
		}
		
		function getCRUD() {
			return 111;
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
