<?php
	class Field
	{
		var $table;
		
		var $name;
		var $type;
		var $required;
		var $unique;
		var $primaryKey;
		var $size;
		var $defaultValue;
		var $maxlength;
		var $minlength;
		var $validator;
		
		var $foreignKey;
		var $foreignModel;
		
		private $errors;
		
		const ERROR_REQUIRED = 1;
		const ERROR_UNIQUE = 2;
		const ERROR_MAXLENGTH = 4;
		const ERROR_ = 8;
		
		/**
			*  Field::__construct([DOMElement $element]) 
			*/
		
		function __construct($table, $element=null)
		{
			$this->table = $table;
			
			if(isset($element))
				$this->hydrateFromDOMElement($element);
		}
		
		function __toString()
		{
			return "{$this->table}.{$this->name}";
		}
		
		/**
			*  Field::hydrateFromDOMElement(DOMElement $element)
			*/
		
		function getCreateString()
		{
			// NAME
			$createString = "`{$this->name}`";
			
			// TYPE
			switch (strtolower($this->type))
			{
				case 'varchar':
					$createString .= " VARCHAR({$this->size})";
					break;
				case 'float':
					$createString .= " FLOAT({$this->size})";
					break;
				case 'double':
					$createString .= " DOUBLE({$this->size})";
					break;
				case 'decimal':
					$createString .= " DECIMAL({$this->size})";
					break;
				case 'int':
					$createString .= " INT({$this->size})";
					break;
				case 'tinyint':
					$createString .= " TINYINT({$this->size})";
					break;
				case 'text':
					$createString .= " TEXT";
					break;
				case 'datetime':
					$createString .= " DATETIME";
					break;
				default:
					Debug::error("Field::getCreateString - Unsuported field type \"{$this->type}\" for field \"{$this->name}\" on table \"{$this->table}\"");
			}
			
			// PRIMARY KEY
			if ($this->primaryKey) $createString .= ' PRIMARY KEY AUTO_INCREMENT';
			
			// REQUIRED
			if ($this->required && !$this->primaryKey) $createString .= ' NOT NULL';
			
			// DEFAULT
			if ($this->defaultValue) $createString .= ' DEFAULT "' . $this->defaultValue . '"';
			
			// UNIQUE
			if ($this->unique && !$this->primaryKey) $createString .= ' UNIQUE';
			
			return $createString;
		}
		
		function hydrateFromDOMElement($element)
		{
			$this->name = $element->getAttribute('name');
			$this->type = $element->getAttribute('type');
			$this->required = $element->getAttribute('required') == 'true' ? true : false;
			$this->unique = $element->getAttribute('unique') == 'true' ? true : false;
			$this->primaryKey = $element->getAttribute('primaryKey') == 'true' ? true : false;
			$this->size = intval($element->getAttribute('size'));
			$this->defaultValue = $element->getAttribute('default');
			$this->maxlength = intval($element->getAttribute('maxlength'));
			$this->minlength = intval($element->getAttribute('minlength'));
			$this->validator = $element->getAttribute('validator');
			
			$this->foreignKey = $element->getAttribute('foreignKey');
			$this->foreignModel = $element->getAttribute('foreignModel');
		}
		
		function validate($value)
		{
			$this->errors = array();
			$errors = false;
			
			// size and maxlength
			$maxlength = $this->size < $this->maxlength ? $this->size : $this->maxlength;
			if (strlen($value) > $maxlength)
				$this->errors[] = Field::ERROR_MAXLENGTH;
			
			// unique (TODO)
		}
	}
?>
