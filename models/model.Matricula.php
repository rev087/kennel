<?php
	require_once('controllers/controller.Model.php');
	
	class User extends Model {
		
		private static $db;
		
		var $fieldDefs = array(
			'matricula'=>array('caption'=>'Matrícula'),
			'nascimento'=>array('caption'=>'Data de Nascimento'),
			'cpf'=>array('caption'=>'CPF'),
			'cep'=>array('caption'=>'CEP'),
			'endereco'=>array('caption'=>'Endereço'),
			'cidade'=>array('caption'=>'Cidade'),
			'estado'=>array('caption'=>'Estado', 'input'=>'select', 'values'=>array(
				'Acre'=>'AC',
				'Alagoas'=>'AL',
				'Amapá'=>'AP',
				'Amazonas'=>'AM',
				'Bahia'=>'BA',
				'Ceará'=>'CE',
				'Espírito Santo'=>'ES',
				'Goiás'=>'GO',
				'Maranhão'=>'MA',
				'Mato Grosso'=>'MT',
				'Mato Grosso do Sul'=>'MS',
				'Minas Gerais'=>'MG',
				'Pará'=>'PA',
				'Paraná'=>'PR',
				'Paraíba'=>'PB',
				'Pernambuco'=>'PE',
				'Piauí'=>'PI',
				'Rio de Janeiro'=>'RJ',
				'Rio Grande do Norte'=>'RN',
				'Rio Grande do Sul'=>'RS',
				'Rondônia'=>'RO',
				'Roraima'=>'RR',
				'Santa Catarina'=>'SC',
				'São Paulo'=>'SP',
				'Sergipe'=>'SE',
				'Tocantins'=>'TO',
				'Distrito Federal'=>'DF'
			)),
			'pai'=>array('caption'=>'Pai'),
			'mae'=>array('caption'=>'Mãe'),
			'telefone'=>array('caption'=>'Telefone'),
			'profissao'=>array('caption'=>'Profissão'),
			'divulgacao'=>array('caption'=>'Divulgação'),
			'indicador'=>array('caption'=>'Indicador'),
			
			'codigofuncionario'=>array('caption'=>'Código do funcionário'),
			'bairro'=>array('caption'=>'Bairro'),
			'admissao'=>array('caption'=>'Admissão', 'input'=>'date'),
			'demissao'=>array('caption'=>'Demissão', 'input'=>'date')
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
