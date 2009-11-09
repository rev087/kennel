<?php
	
	class model_pagination {
		
		private $model;
		private $where;
		private $order;
		private $items_per_page;
		private $class_name;
		
		public $count;
		public $pages;
		public $page_number;
		
		static $db;
		
		function __construct($model, $items_per_page=10, $where=NULL, $order=NULL, $class_name='Model')
		{
			$this->model = $model;
			$this->items_per_page = $items_per_page;
			$this->where = $where;
			$this->order = $order;
			$this->class_name = $class_name;
			
			self::$db = new MySQL;
			$rs = self::$db->query($this->getSql());
			$this->count = self::$db->num_rows($rs);
			$this->pages = ceil($this->count / $this->items_per_page);
		}
		
		private function getSql()
		{
			$sql = "SELECT * FROM `{$this->model}`";
			
			if($this->where) {
				if(!is_array($this->where)) $this->where = array($this->where);
				$arr = array();
				foreach($this->where as $key=>$value) {
					if(!is_numeric($key)) $arr[] = "`{$key}` = '{$value}'";
					else $arr[] = $value;
				}
				$sql .=' WHERE ' . join(" AND ", $arr);
			}
			
			if($this->order) {
				if(!is_array($this->order)) $this->order = array($this->order);
				$arr = array();
				foreach($this->order as $key=>$value) {
					if(!is_numeric($key)) $arr[] = "{$key} {$value}";
					else $arr[] = "{$value} ASC";
				}
				$sql .= ' ORDER BY ' . join(", ", $arr);
			}
			
			return $sql;
		}
		
		function getPage($page_number=1)
		{
			$this->page_number = $page_number;
			
			$sql = $this->getSql() . ' LIMIT ' . (($page_number - 1) * $this->items_per_page). ',' . $this->items_per_page;
			
			$result = array();
			$rs = self::$db->query($sql);
			while ($row = self::$db->fetch_assoc($rs))
			{
				$obj = new $this->class_name($this->model);
				$obj->feed($row);
				
				$result[] = $obj;
			}
			
			return $result;
		}
		
		function getLinks($urlformat='?pg={page}')
		{
			$this->urlformat = $urlformat;
			
			// Initializing the result string
			$links = '';
			
			// Make sure to show pagination even on an empty page
			if($this->pages == 0) return $links = '<span>1</span>';
			
			// First
			if ($this->page_number > 4)
			{
				$links .= $this->anchor(1, '&lt;&lt;');
				//$links .= '<span>...</span>';
			}
			
			// Previous
			/*
			if ($this->page_number > 1)
			{
				$links .= $this->anchor(1, '&lt;');
			}
			*/
			
			// Left Range
			for ($i = $this->page_number-3; $i < $this->page_number; $i++)
			{
				if ($i > 0) $links .= $this->anchor($i);
			}
			
			// Current Page
			$links .= '<span>' . $this->page_number . '</span>';
			
			// Right Range
			for ($i = $this->page_number+1; $i <= $this->page_number + 3; $i++)
			{
				//if ($i+3 <= $this->pages)
					$links .= $this->anchor($i);
			}
			
			// Next
			/*
			if ($this->page_number < $this->pages)
			{
				$links .= $this->anchor($this->page_number + 1, '&gt;');
			}
			*/
			
			// Last
			if ($this->page_number < $this->pages - 3)
			{
				//$links .= '<span>...</span>';
				$links .= $this->anchor($this->pages, '&gt;&gt;');
			}
			
			// Return the result string
			return $links;
		}
		
		function anchor($page, $label=null)
		{
			if(!$label) $label = $page;
			if($page > $this->pages) return '';
			if (is_object($this->urlformat) && get_class($this->urlformat))
			{
				$anchor = clone $this->urlformat;
				$anchor->href = str_replace('{page}', $page, $anchor->href);
				$anchor->setText($label);
				return $anchor;
			}
			else
				return '<a href="'.str_replace('{page}', $page, $this->urlformat).'">'.$label.'</a>';
		}
		
	}
	
?>
