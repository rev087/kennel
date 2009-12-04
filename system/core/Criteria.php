<?php

	class Criteria
	{
		// Operators
		const EQUAL = '=';
		const NOT_EQUAL = '!=';
		const LIKE = 'LIKE';
		const IN = 'IN';
		const GREATER_THEN = '>';
		const LESS_THEN = '<';
		const GREATER_EQUAL = '>=';
		const LESS_EQUAL = '<=';
		
		// Joins
		const LEFT_JOIN = 'LEFT JOIN';
		const RIGHT_JOIN = 'RIGHT JOIN';
		const INNER_JOIN = 'INNER JOIN';
		
		// Values
		const NOW = 'NOW()';
		
		// Query variables
		var $criterion_groups = array();
		var $from_model_name;
		var $joins = array();
		var $order_by = array();
		var $group_by = array();
		var $limit;
		var $offset = 0;
		
		function __construct($model_name=null)
		{
			$this->from_model_name = $model_name;
		}
		
		function add($column, $value, $operator=Criteria::EQUAL)
		{
			$this->criterion_groups[0][] = new Criterion($column, $value, $operator);
		}
		
		function setLimit($limit)
		{
			$this->limit = $limit;
		}
		
		function setOffset($offset)
		{
			$this->offset = $offset;
		}
		
		function addAscendingOrder($column)
		{
			$this->order_by[] = array('column'=>$column, 'direction'=>'ASC');
		}
		
		function addDescendingOrder($column)
		{
			$this->order_by[] = array('column'=>$column, 'direction'=>'DESC');
		}
		
		function addGroupBy($column)
		{
			$this->group_by[] = $column;
		}
		
		function setFrom($model_name)
		{
			$this->from_model_name = $model_name;
		}
		
		function addJoin($model_name, $left_column, $right_column, $join_type=self::INNER_JOIN)
		{
			if (is_array($left_column))
			{
				$schema = Model::getSchema($left_column[0]);
				$left_column = "{$schema->table}.$left_column[1]";
			}
			
			if (is_array($right_column))
			{
				$schema = Model::getSchema($right_column[0]);
				$right_column = "{$schema->table}.$right_column[1]";
			}
			
			$join = array(
				'model_name' => $model_name,
				'left_column' => $left_column,
				'right_column' => $right_column,
				'join_type' => $join_type
			);
			
			if (array_search($join, $this->joins) === false) $this->joins[] = $join;
			
		}
		
		function addOr()
		{
			$this->criterion_groups[] = func_get_args();
		}
		
		function getNewCriterion($field, $value, $operator=Criteria::EQUAL)
		{
			return new Criterion($field, $value, $operator);
		}
		
	}
	
?>