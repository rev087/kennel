<?php
	
	/**
		*  DEPRECATED; Don't use this library. It will soon be rewritten completely.
		*  In it's current state, it's highly dated and will not work.
		*/
	
	require_once('controllers/controller.XML.php');
	require_once('controllers/controller.Model.php');
	require_once('controllers/controller.MySQL.php');
	
	class ModelForm {
		
		private $model_name;
		private $model;
		private $fieldset;
		private $action;
		private static $db = array();
		
		function __construct($model_name, $fieldset=null, $load_where=null) {
			self::$db = new MySQL();
			$this->model_name = $model_name;
			if(!$load_where) {
				$this->model = Model::getInstance($model_name);
			} else {
				$this->model = Model::getOne($model_name, $load_where);
			}
			
			$this->fieldset = $fieldset;
		}
		
		function output($return=false) {
			$struc = self::$db->getTableStructure($this->model_name);
			
			//form
			$this->form = new XMLElement('form', null, array('method'=>'post'));
			if($this->action) $this->form->action = $this->action;
			foreach($struc as $field) {
				if ($fieldset) {
					if(array_search($field['name'], $fieldset)) $this->field($field);	
				} else $this->field($field);
			}
			
			//submit
			$p = new XMLElement('p', $this->form, array('class'=>'ModelForm_line center'));
			$submit = new XMLELement('input', $p, array('type'=>'submit', 'value'=>'Enviar'));
			
			if($return) return $this->form;
			else print $this->form;
		}
		
		function setAction($url) {
			$this->action = $url;
		}
		
		function field($field) {
			$fields = $this->model->fieldDefs;
			$fieldDefs = $fields[$field['name']];
			switch($fieldDefs['input']) {
				case 'hidden':
					//input
					$value = $this->model->__get($field['name']);
					$input = new XMLElement('input', $this->form, array('type'=>'hidden', 'name'=>$field['name'], 'value'=>$value));
					break;
				case 'radio':
					//paragraph
					$p = new XMLElement('p', $this->form, array('class'=>'ModelForm_line'));
					
					//label
					if($fieldDefs['caption']) $label = new XMLElement('label', $p, array('class'=>'ModelForm_label'), $fieldDefs['caption']);
					else $label = new XMLElement('label', $p, array('class'=>'ModelForm_label'), $field['name']);
					
					//radios
					foreach($fields[$field['name']]['values'] as $key=>$opt) {
						$label = new XMLElement('label', $p, null);
						if($this->model->__get($field['name'])==$opt) {
							$option = new XMLElement('input', $label, array('type'=>'radio', 'value'=>$opt, 'name'=>$field['name'], 'checked'=>$checked));
						} else {
							$option = new XMLElement('input', $label, array('type'=>'radio', 'value'=>$opt, 'name'=>$field['name']));
						}
						$text = new XMLText($key, $label);
					}
					break;
				case 'select':
					//paragraph
					$p = new XMLElement('p', $this->form, array('class'=>'ModelForm_line'));
					
					//label
					if($fieldDefs['caption']) $label = new XMLElement('label', $p, array('class'=>'ModelForm_label'), $fieldDefs['caption']);
					else $label = new XMLElement('label', $p, array('class'=>'ModelForm_label'), $field['name']);
					
					//select
					$select = new XMLElement('select', $p, array('name'=>$field['name']));
					$option = new XMLElement('option', $select, null, '');
					foreach($fields[$field['name']]['values'] as $key=>$opt) {
						if($this->model->__get($field['name'])==$opt) {
							$option = new XMLElement('option', $select, array('value'=>$opt, 'selected'=>'selected'), $key);
						} else {
							$option = new XMLElement('option', $select, array('value'=>$opt), $key);
						}
					}
					break;
				case 'text':
				default:
					//paragraph
					$p = new XMLElement('p', $this->form, array('class'=>'ModelForm_line'));
					
					//label
					if($fieldDefs['caption']) $label = new XMLElement('label', $p, array('class'=>'ModelForm_label'), $fieldDefs['caption']);
					else $label = new XMLElement('label', $p, array('class'=>'ModelForm_label'), $field['name']);
					
					//input
					$value = $this->model->__get($field['name']);
					$input = new XMLElement('input', $p, array('type'=>'text', 'class'=>'text', 'name'=>$field['name'], 'value'=>$value));
			}
		}
		
	}
?>