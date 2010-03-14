<?php
	class Schema implements Iterator
	{
		var $table;
		var $position = 0;
		private $fields;
		
		function current() { return $this->fields[$this->position]; }
		function key() { return $this->fields[$this->position]->name; }
		function next() { ++$this->position; }
		function valid() { return isset($this->fields[$this->position]); }
		function rewind() { $this->position = 0; }
		
		function __construct($model_name)
		{
			if (!$model_name) Debug::error("Schema::__construct - undefined model name.");
			
			$path = Kennel::cascade($model_name, 'schemas');
			if (!$path) Debug::error("Schema::__construct - model schema for \"{$model_name}\" not found.");
			
			$doc = new DOMDocument;
			$doc->load(realpath($path));
			
			$root = $doc->getElementsByTagName('model')->item(0);
			$this->table = $root->getAttribute('table');
			
			$fields = $doc->getElementsByTagName('field');
			
			foreach($fields as $field)
			{
				$this->fields[] = new Field($this->table, $field);
			}
			
		}
		
		function __get($name)
		{
			foreach($this as $key=>$field)
			{
				if($key === $name) return $field;
			}
			
			return null;
		}
		
		function getPrimaryKey()
		{
			foreach($this as $field)
			{
				if(isset($field->primaryKey)) return $field;
			}
		}
		
		function getRelationships()
		{
			$foreign_keys = array();
			foreach($this as $field)
			{
				if ($field->foreignKey && $field->foreignModel) $foreign_keys[] = $field;
			}
			return $foreign_keys;
		}
		
		function getCreateString()
		{
			$sql = "CREATE TABLE IF NOT EXISTS `{$this->table}`";
			
			$fields = array();
			foreach($this as $field)
			{
				$fields[] = $field->getCreateString();
			}
			
			$sql .= " (\n" . implode(", \n", $fields) . "\n);";
			
			return $sql;
		}
		
		function isField($name)
		{
			foreach ($this as $field)
			{
				if ($field->name == $name)
					return true;
			}
			return false;
		}
		
	}
?>
