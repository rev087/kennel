<?php
	
	class Model {
		private static $structure_cache = array();
		private static $db;
		private $fields;
		private $sync_values = array();
		var $model_name;
		var $CRUDFieldDefs = array();
		
		function __construct($model_name=null) {
			$this->model_name = $model_name;
			
			if(!$model_name) Debug::backtrace();
			
			if(!self::$db) self::$db = new MySQL();
			$this->fields = self::$db->getTableStructure($this->model_name);
		}
		
		/*
		* Model::getFields()
		* Returns the complete field structure as an associative array.
		* Each field has: field name, type, required, default value and current instance value.
		*/
		function getFields() {
			return $this->fields;
		}
		
		/*
		* Model::dump()
		* Prints a report of all model's fields and their properties. See Model::getFields() for a list of properties.
		*/
		function dump() {
			foreach($this->fields as $key=>$value) {
				print "<p><strong>{$key}: </strong>".print_r($value, true)."</p>";
			}
		}
		
		/*
		* Model::getInstance(string $model_name)
		* Returns an instance of the Model class.
		* If an extended Model class exists in the models directory, returns an instance of that class instead.
		*/
		static function getInstance($model_name) {
			/* Doesn't work on PHP <5.3.0
			$called_class = get_called_class();
			$model = strtolower(substr($called_class, 0, (strlen($called_class) - 6)));
			
			if ($called_class == 'Model') return new Model($model_name);
			else return new $called_class($model);
			*/
			$extended_class = ucfirst($model_name) . '_model';
			if (class_exists($extended_class)) return new $extended_class($model_name);
			else return new Model($model_name);
		}
		
		/*
		* misc Model::get()
		* This static method returns one or more Model instancies.
		*/
		static function get($model_name, $where=null, $order=null) {
			if(!self::$db) self::$db = new MySQL();
			
			$sql = "SELECT * FROM `{$model_name}`";
			
			if($where) {
				$arr = array();
				foreach($where as $key=>$value) {
					if(!is_numeric($key)) $arr[] = "`{$key}` = '{$value}'";
					else $arr[] = $value;
				}
				$sql .=' WHERE ' . join(" AND ", $arr);
			}
			
			if($order) {
				if(!is_array($order)) $order = array($order);
				$arr = array();
				foreach($order as $key=>$value) {
					if(!is_numeric($key)) $arr[] = "{$key} {$value}";
					else $arr[] = "{$value} ASC";
				}
				$sql .= ' ORDER BY ' . join(", ", $arr);
			}
			
			$rs = self::$db->query($sql);
			
			$ret = array();
			while($row = self::$db->fetch_assoc($rs)) {
				$inst = self::getInstance($model_name);
				$inst->sync_values = array();
				foreach($row as $key=>$val) {
					$inst->__set($key, $val);
					if ($val===NULL)
						$inst->sync_values[] = "`{$key}` IS NULL";
					else
						$inst->sync_values[] = "`{$key}` = '{$val}'";
				}
				$ret[] = $inst;
			}
			return  $ret;
			
		}
		
		/*
		* array Model::getAll(string $model_name, misc $where)
		* Get all results in an array.
		* Same as Model::get(), but always returns an array, even with a single instance.
		*/
		static function getAll($model_name, $order=null) {
			$ret = self::get($model_name, null, $order);
			if(is_array($ret)) return $ret;
			else return array($ret);
		}
		
		/*
		* object Model::getOne(string $model_name, misc $where)
		* Get the first result.
		*/
		static function getOne($model_name, $where) {
			$rows = self::get($model_name, $where);
			if(count($rows) > 0) return $rows[0];
			else return NULL;
		}
		
		/*
		* void Model::feed(array $array)
		* Feed the given arrey as values to the model
		*/
		function feed($array) {
			foreach($array as $key=>$value) {
				$this->__set($key, $value);
			}
		}
		
		/*
		* void Model::feed(array $array)
		* The exact oposite of feed
		*/
		function toArray() {
			$arr = array();
			foreach($this->fields as $key=>$field) {
				$arr[$field['name']] = $field['value'];
			}
			return $arr;
		}
		
		/*
		* Model::save(void)
		* Save the model.
		* If the model was loaded from the database, this method updates the database record.
		* If it's a new model, this method creates a new database record.
		*/
		function save() {
			$values = array();
			if($this->sync_values) {
				//save a database row that already exists
				foreach($this->fields as $field) {
					if($field['value'] !== NULL) $values[] = "`{$field['name']}` = '{$field['value']}'";
					else "`{$field['name']}` IS NULL";
				}
				$sql = "UPDATE {$this->model_name} SET " . join(", ", $values) . " WHERE " . join(" AND ", $this->sync_values);
			} else {
				//insert a new database row
				foreach ($this->fields as $field)
				{
					if($field['value']) $values[] = "'{$field['value']}'";
					else $values[] = "null";
				}
				$sql = "INSERT INTO {$this->model_name} VALUES (" . join(", ", $values) . ")";
			}
			
			//register the new syncronized values
			foreach($this->fields as $field) {
				if ($field['value'] === NULL)
					$this->sync_values = "`{$field['name']}` IS NULL";
				else
					$this->sync_values = "`{$field['name']}` = '{$field['value']}'";
			}
			
			//execute the query
			self::$db->query($sql);
			
			//upadte the id
			if(!$this->id) $this->id = self::$db->insert_id();
		}
		
		/*
		* Model::delete()
		* Deletes the database record. The model must have been loaded from the database.
		*/
		function delete() {
			if(self::$db->query("DELETE FROM {$this->model_name} WHERE " . join(" AND ", $this->sync_values))) {
				return true;
				unset($this->fields);
			} else {
				return false;
			}
		}
		
		/*
		* Magic!
		*/
		function __get($key) {
			foreach($this->fields as $k=>$field) {
				if($k == $key) return $field['value'];
			}
			Debug::error("Model::__get() - Field '{$key}' not found");
		}
		
		/*
		* Magic!
		*/
		function __set($key, $value) {
			foreach($this->fields as $k=>$field) {
				if($k == $key) {
					$this->fields[$k]['value'] = $value;
					return $value;
				}
			}
			Debug::error("Model::__set() - Field '{$key}' not found");
		}
		
		function __tostring() {
			return $this->model_name;
		}
		
	}
?>
