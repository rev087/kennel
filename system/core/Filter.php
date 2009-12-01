<?php
	class Filter
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
		
		// Order Types
		const ASC = 'ASC';
		const DESC = 'DESC';
		
		var $criterion_groups = array();
		var $order = array();
		var $limit;
		var $offset;
		
		function add($column, $value, $operator=self::EQUAL)
		{
			$this->criterion_groups[0][] = new Criterion($column, $value, $operator);
		}
		
		function addOr()
		{
			$criterions = func_get_args();
			$this->criterion_groups[] = $criterions;
		}
		
		function getNewCriterion($column, $value, $operator=self::EQUAL)
		{
			return new Criterion($column, $value, $operator);
		}
		
		function addOrder($column, $type=self::ASC)
		{
			$this->order[] = array('column'=>$column, 'type'=>$type);
		}
		
		function setLimit($limit, $offset=null)
		{
			$this->limit = $limit;
			$this->offset = $offset;
		}
		
		function getWhereString()
		{
			$where_groups = array();
			foreach($this->criterion_groups as $group_key=>$criterion_group)
			{
				foreach ($criterion_group as $criterion)
				{
					$where_groups[$group_key][] = '`' . $criterion->column . '` ' . $criterion->operator . ' "' . $criterion->value . '"';
				}
			}
			
			$where = array();
			foreach ($where_groups as $where_group)
			{
				$where[] = '(' . implode(' AND ', $where_group) . ')';
			}
			
			return implode(' OR ', $where);
		}
		
		function getOrderString()
		{
			$order_array = array();
			foreach ($this->order as $order)
				$order_array[] = $order['column'] . ' ' . $order['type'];
			return implode(', ', $order_array);
		}
		
		function getLimitString()
		{
			return ($this->limit ? $this->limit : '') . ($this->offset ? ', ' . $this->limit : '');
		}
		
	}
?>