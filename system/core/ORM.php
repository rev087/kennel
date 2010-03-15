<?php

	class ORM
	{
		static $DB;
		private static $SCHEMA_CACHE = array();
		
		function __construct()
		{
			self::$DB = new MySQL;
		}
		
		static function retrieve($criteria)
		{
			if (!self::$DB) self::$DB = new MySQL;
			
			$schema = self::getSchema($criteria->from_model_name);
			$relationships = $schema->getRelationships();
			
			foreach ($relationships as $rel)
			{
				$criteria->addJoin($rel->foreignModel, "{$criteria->from_model_name}.{$rel->name}", "{$rel->foreignModel}.{$rel->foreignKey}", Criteria::LEFT_JOIN);
			}
			
			$sql = self::getSelectString($criteria);
			$rs = self::$DB->query($sql);
			
			$model_array = array();
			while ($data = self::$DB->fetch($rs))
			{
				$model = Model::getInstance($criteria->from_model_name);
				self::hydrateModel($model, $data);
				
				foreach ($relationships as $relationship)
				{
					$foreignModel_name = $relationship->foreignModel;
					$model->$foreignModel_name = Model::getInstance($foreignModel_name);
					self::hydrateModel($model->$foreignModel_name, $data);
				}
				
				$model_array[] = $model;
			}
			
			return $model_array;
		}
		
		static function retrieveFirst($criteria)
		{
			$criteria->setLimit(1);
			$items = self::retrieve($criteria);
			if (count($items) > 0) return $items[0];
			else return null;
		}
		
		static function delete($criteria)
		{
			if (!self::$DB) self::$DB = new MySQL;
			$sql = self::getDeleteString($criteria);
			self::$DB->query($sql);
		}
		
		static function create($model)
		{
			if (!self::$DB) self::$DB = new MySQL;
			
			$schema = self::getSchema($model);
			$sql = $schema->getCreateString();
			
			self::$DB->query($sql);
		}
		
		static function retrieveByPrimaryKey($model_name, $primary_key_value)
		{
			$schema = self::getSchema($model_name);
			$primaryKey = $schema->getPrimaryKey();
			if(!$primaryKey) Debug::error("ORM::retrieveByPrimaryKey error: no primary key defined for model <strong>{$model_name}</strong>.");
			
			$c = new Criteria($model_name);
			$c->add($primaryKey->name, $primary_key_value);
			$c->setLimit(1);
			
			$instancies = ORM::retrieve($c);
			
			if (isset($instancies[0])) return $instancies[0];
			else return null;
		}
		
		static function retrieveAll($model_name)
		{
			$c = new Criteria($model_name);
			return self::retrieve($c);
		}
		
		static function getSchema($model_name)
		{
			if (!isset(self::$SCHEMA_CACHE[$model_name]))
				self::$SCHEMA_CACHE[$model_name] = new Schema($model_name);
			
			return self::$SCHEMA_CACHE[$model_name];
		}
		
		// PRIVATE METHODS
		//////////////////
		
		function hydrateModel($model, $data)
		{
			foreach ($data as $key=>$value)
			{
				if (substr($key, 0, strlen($model->schema->table)) == $model->schema->table)
				{
					$field_name = substr($key, strlen($model->schema->table . '_'));
					$model->hydrate($field_name, $value);
				}
			}
		}
		
		// ORM::getSelectString(Criteria $criteria)
		function getSelectString($criteria)
		{
			// SELECT
			$sql = "SELECT ";
			$sql .= self::getFieldListString($criteria);
			
			// FROM
			$sql .= "\nFROM ";
			$sql .= self::getFromString($criteria);
			
			// JOINS
			$join_string = self::getJoinString($criteria);
			if ($join_string) $sql .= $join_string;
			
			// WHERE
			$where_string = self::getWhereString($criteria);
			if ($where_string) $sql .= "\nWHERE {$where_string}";
			
			// GROUP
			$group_string = self::getGroupString($criteria);
			if ($group_string) $sql .= "\nGROUP BY {$group_string}";
			
			// ORDER
			$order_string = self::getOrderString($criteria);
			if ($order_string) $sql .= "\nORDER BY {$order_string}";
			
			// LIMIT
			$limit_string = self::getLimitString($criteria);
			if($limit_string) $sql .= "\nLIMIT {$limit_string}";
			
			$sql .= ';';
			
			return $sql;
		}
		
		// ORM::getDeleteString(Criteria $criteria)
		function getDeleteString($criteria)
		{
			// DELETE
			$sql = "DELETE ";
			
			// FROM
			$sql .= "\nFROM ";
			$sql .= self::getFromString($criteria);
			
			// WHERE
			$where_string = self::getWhereString($criteria);
			if ($where_string) $sql .= "\nWHERE {$where_string}";
			
			$sql .= ';';
			
			return $sql;
		}

		
		// ORM::getFieldListString(Criteria $criteria)
		function getFieldListString($criteria)
		{
			$select_array = array();
			
			$schema = self::getSchema($criteria->from_model_name);
			foreach ($schema as $field)
			{
				$select_array[] = "\n `{$schema->table}`.`{$field->name}` AS `{$schema->table}_{$field->name}`";
			}
			
			foreach ($criteria->joins as $join)
			{
				$schema = self::getSchema($join['model_name']);
				foreach ($schema as $field)
				{
					$select_array[] = "\n `{$schema->table}`.`{$field->name}` AS `{$schema->table}_{$field->name}`";
				}
			}
			
			$select_string = implode(', ', $select_array);
			
			return $select_string;
		}
		
		// ORM::getFromString(Criteria $criteria)
		function getFromString($criteria)
		{
			$schema = self::getSchema($criteria->from_model_name);
			return "\n `{$schema->table}`";
		}
		
		// ORM::getJoinString(Criteria $criteria)
		function getJoinString($criteria)
		{
			$joins = array();
			foreach($criteria->joins as $join)
			{
				$schema = self::getSchema($join['model_name']);
				$left_column_reference = self::formatColumnReference($join['left_column'], $criteria);
				$right_column_reference = self::formatColumnReference($join['right_column'], $criteria);
				$joins[] = "\n {$join['join_type']} {$schema->table} ON {$left_column_reference} = {$right_column_reference}";
			}
			
			return implode('', $joins);
		}
		
		// ORM::getWhereString(Criteria $criteria)
		function getWhereString($criteria)
		{
			$where_groups = array();
			foreach($criteria->criterion_groups as $group_key=>$criterion_group)
			{
				foreach ($criterion_group as $criterion)
				{
					$column = self::formatColumnReference($criterion->column, $criteria);
					if ($criterion->value === Criteria::NOW)
						$where_groups[$group_key][] = $column . ' ' . $criterion->operator . ' NOW()';
					elseif ($criterion->value === NULL)
						$where_groups[$group_key][] = $column . ' IS NULL';
					else
						$where_groups[$group_key][] = $column . ' ' . $criterion->operator . ' "' . addslashes(MySQL::escape_string($criterion->value)) . '"';
				}
			}
			
			$where = array();
			foreach ($where_groups as $where_group)
			{
				$where[] = "(" . implode("\n AND ", $where_group) . ')';
			}
			
			return implode(' OR ', $where);
		}
		
		// ORM::getOrderString(Criteria $criteria)
		function getOrderString($criteria)
		{
			$order_params = array();
			
			foreach($criteria->order_by as $order_by)
				$order_params[] = "\n " . self::formatColumnReference($order_by['column'], $criteria) . " {$order_by['direction']}";
			
			return implode(', ', $order_params);
		}
		
		// ORM::getGroupString(Criteria $criteria)
		function getGroupString($criteria)
		{
			$params = array();
			foreach($criteria->group_by as $group_by)
				$params[] = "\n " . self::formatColumnReference($group_by, $criteria);
			
			return implode(', ', $params);
		}
		
		// ORM::getLimitString(Criteria $criteria)
		function getLimitString($criteria)
		{
			if ($criteria->limit)
			{
				if ($criteria->offset) return "{$criteria->offset}, {$criteria->limit}";
				else return "{$criteria->limit}";
			}
			else
				return null;
		}
		
		// ORM::formatColumnReference(String $column, Criteria $criteria);
		function formatColumnReference($column_reference, $criteria)
		{
			if (strpos($column_reference, '.') > 0)
			{
				$column_composition = explode('.', $column_reference);
				$schema = ORM::getSchema($column_composition[0]);
				return '`' . trim($schema->table, '`') . '`.`' . trim($column_composition[1], '`') . '`';
			}
			else
			{
				$schema = ORM::getSchema($criteria->from_model_name);
				return '`' . trim($schema->table, '`') . '`.`' . $column_reference . '`';
			}
		}
	}
	
?>
