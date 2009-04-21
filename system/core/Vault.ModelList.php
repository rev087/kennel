<?php	
	class ModelList {
		
		private $model_name;
		private $fields = array();
		private $filter;
		private $actions = array();
		private static $db;
		
		function __construct($model_name, $fields=null) {
			self::$db = new MySQL();
			$this->model_name = $model_name;
			$this->fields = $fields;
		}
		
		function setFilter($where) {
			$this->filter = $where;
		}
		
		function setAction($link, $text, $img=null, $vars=array()) {
			if($img) {
				$a = new XMLElement('a', null, array('href'=>$link, 'class'=>'ModelList_action'));
				$img = new XMLElement('img', $a, array('src'=>$img));
			} else {
				$a = new XMLElement('a', null, array('href'=>$link, 'class'=>'ModelList_action'), $text);
			}
			$action['a'] = $a;
			$action['vars'] = is_array($vars)?$vars:array($vars);
			$this->actions[] = $action;
		}
		
		function output($return=false) {
			require_once('controllers/controller.Model.php');
			require_once('controllers/controller.MySQL.php');
			require_once('controllers/controller.HTML.php');
			$model = Model::getInstance($this->model_name);
			$struc = self::$db->getTableStructure($this->model_name);
			
			//upper pagination (todo)
			
			//table
			$table = new XMLElement('table', null, array('class'=>'ModelList'));
			$tr = new XMLElement('tr');
			
			//table headers
			foreach($struc as $field) {
				$fieldDefs = $model->fieldDefs[$field['name']];
				if(!$this->fields || array_search($field['name'], $this->fields) !== false) {
					if($fieldDefs['caption']) $th = new XMLElement('th', $tr, null, $fieldDefs['caption']);
					else $th = new XMLElement('th', $tr, null, $field['name']);
				}
			}
			if(count($this->actions) > 0) $th = new XMLElement('th', $tr, null);
			$table->adopt($tr);
			
			//table rows
			
			$rows = Model::get($this->model_name, $this->filter);
			
			foreach($rows as $row) {
				$row_data = array();
				$tr = new XMLElement('tr', $table);
				foreach($row->getFields() as $field) {
					$row_data[$field['name']] = $field['value'];
					if(!$this->fields || array_search($field['name'], $this->fields) !== false) {
						$td = new XMLElement('td', $tr, null, $field['value']);
					}
				}
				
				if(count($this->actions) > 0) {
					$td = new XMLElement('td', $tr);
					foreach($this->actions as $action) {
						if(count($action['vars']) > 0) {
							foreach($action['vars'] as $var) {
								$action['a']->href = HTML::url($action['a']->href, array($var=>$row_data[$var]));
							}
						}
						$text = new XMLText(' ', $td);
						$td->adopt($action['a']);
					}
				}
				
			}
			
			if($return) return $table;
			else print $table;
			
			//bottom pagination (todo)
		}
		
	}
?>