<?php
	
	class Model {
		
		private static $DB;
		private $synced_data;
		private $data;
		var $schema;
		var $model_name;
		
		/**
			*  Instance Methods 
			*/
		
		function __construct($model_name)
		{
			if (!is_string($model_name)) Debug::error('Model::__construct - $model_name must be a string.');
			$this->model_name = $model_name;
			$this->schema = ORM::getSchema($model_name);
			foreach ($this->schema as $field)
			{
				$this->data[$field->name] = null;
				$this->synced_data[$field->name] = null;
			}
		}
		
		function __set($name, $value)
		{
			$this->data[$name] = $value;
		}
		
		function __get($name)
		{
			if (isset($this->data[$name]))
				return $this->data[$name];
			else
				return null;
		}
		
		function __toString()
		{
			return $this->model_name;
		}
		
		function hydrate($field, $value)
		{
			$this->data[$field] = stripslashes($value);
			$this->synced_data[$field] = $value;
		}
		
		function delete()
		{
			$c = new Criteria($this->model_name);
			foreach ($this->schema as $field)
			{
				$c->add($field->name, $this->data[$field->name]);
			}
			ORM::delete($c);
			unset($this->synced_data);
			unset($this->data);
		}
		
		function save()
		{
			$sql = $this->getSaveQuery();
			if (!self::$DB) self::$DB = new MySQL;
			self::$DB->query($sql);
			
			$primaryKey = $this->schema->getPrimaryKey();
			
			if (!$this->data[$primaryKey->name])
			{
				//Update the PrimaryKey
				$insertId = self::$DB->insert_id();
				$this->synced_data[$primaryKey->name] = $insertId;
				$this->data[$primaryKey->name] = $insertId;
			}
		}
		
		function getSaveQuery()
		{
			$primaryKey = $this->schema->getPrimaryKey();
			
			$columns = array();
			$newValues = array();
			$syncedValues = array();
			
			// Add quotes for non-numeric field values, used while building the SQL statement
			foreach ($this->schema as $field)
			{
				$columns[] = '`' . $field->name . '`';
				switch (strtolower($field->type))
				{
					case 'varchar':
					case 'text':
					case 'datetime':
						if ($this->data[$field->name] !== NULL)
							$newValues[] = '"' . MySQL::escape_string($this->data[$field->name]) . '"';
						else
							$newValues[] = 'NULL';
							
						if ($this->synced_data[$field->name] !== NULL)
							$syncedValues[] = '"' . MySQL::escape_string($this->synced_data[$field->name]) . '"';
						else
							$syncedValues[] = 'NULL';
						break;
					case 'int':
					case 'float':
					case 'tinyint':
						if ($this->data[$field->name] !== NULL && is_numeric($this->data[$field->name]))
							$newValues[] = $this->data[$field->name];
						else
							$newValues[] = 'NULL';
						
						if ($this->synced_data[$field->name] !== NULL && is_numeric($this->sunced_data[$field->name]))
							$syncedValues[] = $this->synced_data[$field->name];
						else
							$syncedValues[] = 'NULL';
						break;
					default:
						Debug::error("Model::save - Unsuported field type \"{$field->type}\" for field \"{$field->name}\" on model \"{$this->model_name}\"");
				}
			}
			
			// Check if it's an existing record, build SQL statement accordingly
			if (!$this->synced_data[$primaryKey->name])
			{
				$sql = "INSERT INTO {$this->schema->table} (";
				$sql .= implode(', ', $columns);
				$sql .= ")\nVALUES (";
				$sql .= implode(', ', $newValues);
				$sql .= ");";
			} else
			{
				$dataList = array();
				foreach ($columns as $key=>$column)
					$dataList[] = "\n {$column} = {$newValues[$key]}";
				
				$c = new Criteria($this->model_name);
				foreach ($this->schema as $field)
					$c->add($field->name, $this->synced_data[$field->name]);
				
				$sql = "UPDATE {$this->schema->table} SET " . implode(', ', $dataList);
				$sql .= "\nWHERE " . ORM::getWhereString($c) . ';';
			}
			
			return $sql;
		}
		
		function toArray()
		{
			return $this->data;
		}
		
		function fromArray($data)
		{
			foreach ($data as $field=>$value)
			{
				$this->data[$field] = $value;
			}
		}
		
		function dump($dump_relationships=true)
		{
			print '<div><pre style="padding: 5px; border: 1px solid #1E9C6D; background-color: #FFF; color: #1E9C6D; float: left; text-align: left;">';
			print '<h2 style="margin: 0px 0px 5px 0px; padding: 0px 5px; background-color: #1E9C6D; color: #FFF;">';
			$class = get_class($this);
			print "{$this->model_name}: {$class}";
			print '</h2>';
			foreach ($this->schema as $field)
			{
				if ($field->primaryKey) print '<strong>*</strong> ';
				elseif ($field->foreignKey && $field->foreignModel) print '<strong>~</strong> ';
				else print '&nbsp; ';
				
				if ($field->required)
					print "<strong>{$field->name}</strong> = \"{$this->data[$field->name]}\"<br />";
				else
					print "<strong>[{$field->name}]</strong> = \"{$this->data[$field->name]}\"<br />";
			}
			
			if ($dump_relationships)
				foreach ($this->schema->getRelationships() as $relationship)
				{
					$foreignModel = $relationship->foreignModel;
					if ($this->$foreignModel) $this->$foreignModel->dump();
				}
			
			print '</pre></div><div style="clear: both;"></div>';
		}
		
		/**
			*  Static methods 
			*/
		
		static function getInstance($model_name)
		{
			$path = Kennel::cascade($model_name, 'models');
			if ($path)
			{
				$class = ucfirst($model_name) . '_model';
				$instance = new $class;
			}
			else
			{
				$class = 'Model';
				$instance = new $class($model_name);
			}
			
			return $instance;
		}
		
	}
?>
