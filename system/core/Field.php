<?php
	class Field
	{
		var $table;
		
		var $name;
		var $type;
		var $unique;
		var $primaryKey;
		var $size;
		var $default;
		var $unsigned;
		
		// Presentation
		var $label;
		
		// Relationship
		var $foreignKey;
		var $foreignModel;
		
		// Validation
		var $required;
		var $template;
		var $regex;
		var $maxlength;
		var $minlength;
		
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
				case 'char':
					$createString .= " CHAR({$this->size})";
					break;
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
				case 'bigint':
					$createString .= " BIGINT($this->size)";
					break;
				case 'text':
					$createString .= " TEXT";
					break;
				case 'datetime':
					$createString .= " DATETIME";
					break;
				case 'date':
					$createString .= " DATE";
					break;
				case 'time':
					$createString .= " TIME";
					break;
				default:
					debug::error("Field::getCreateString - Unsuported field type \"{$this->type}\" for field \"{$this->name}\" on table \"{$this->table}\"");
			}
			
			// UNSIGNED
			if ($this->unsigned) $createString .= ' UNSIGNED';
			
			// PRIMARY KEY
			if ($this->primaryKey) $createString .= ' PRIMARY KEY AUTO_INCREMENT';
			
			// REQUIRED
			if ($this->required) $createString .= ' NOT NULL';
			
			// DEFAULT
			if ( isset($this->default) ) $createString .= ' DEFAULT "' . $this->default . '"';
			
			// UNIQUE
			if ($this->unique && !$this->primaryKey) $createString .= ' UNIQUE';
			
			return $createString;
		}
		
		function cast($value)
		{
			if ($value === NULL) return NULL;
			
			switch (strtolower($this->type))
			{
				case 'varchar':
				case 'char':
				case 'datetime':
				case 'text':
				case 'date':
				case 'time':
					return (string) $value;
				case 'int':
				case 'tinyint':
				case 'bigint':
					return (int) $value;
				case 'float':
				case 'double':
				case 'decimal':
					return (float) $value;
				default:
					debug::error("Field::cast - Unsuported field type \"{$this->type}\" for field \"{$this->name}\" on table \"{$this->table}\"");
					return NULL;
			}
		}
		
		function hydrateFromDOMElement($element)
		{
			// Basics
			$this->name = $element->getAttribute('name');
			$this->type = $element->getAttribute('type');
			$this->unique = $element->getAttribute('unique') == 'true' ? true : false;
			$this->primaryKey = $element->getAttribute('primaryKey') == 'true' ? true : false;
			$this->size = intval($element->getAttribute('size'));
			if ($element->hasAttribute('default')) $this->default = $element->getAttribute('default');
			$this->unsigned = $element->getAttribute('unsigned') == 'true' ? true : false;
			
			// Presentation
			$this->label = $element->getAttribute('label');
			
			// Relationship
			$this->foreignKey = $element->getAttribute('foreignKey');
			$this->foreignModel = $element->getAttribute('foreignModel');
			
			// Validation
			$this->required = $element->getAttribute('required') == 'true' ? true : false;
			$this->regex = $element->getAttribute('regex');
			$this->template = $element->getAttribute('template');
			$this->maxlength = $element->getAttribute('maxlength') ? intval($element->getAttribute('maxlength')) : intval($element->getAttribute('size'));
			$this->minlength = intval($element->getAttribute('minlength'));
		}
	}
?>
