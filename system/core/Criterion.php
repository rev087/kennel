<?php

	class Criterion
	{
		var $column;
		var $value;
		var $operator;
		
		function __construct($column, $value, $operator)
		{
			$this->column = $column;
			$this->value = $value;
			$this->operator = $operator;
		}
		
	}
	
?>