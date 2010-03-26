<?php
	
	class Pagination {
		
		private $criteria;
		private $items_per_page;
		private $class_name;
		
		public $count;
		public $pages;
		public $page_number;
		
		private static $DB;
		
		function __construct($criteria, $items_per_page=10)
		{
			self::$DB = new MySQL;
			
			$this->criteria = $criteria;
			$this->items_per_page = $items_per_page;
			
			$sql = ORM::getSelectString($criteria);
			$rs = self::$DB->query($sql);
			
			$this->count = self::$DB->num_rows($rs);
			$this->pages = ceil($this->count / $this->items_per_page);
		}
		
		function getPage($page_number=1)
		{
			if (!is_numeric($page_number)) $page_number = 1;
			$this->page_number = $page_number;
			
			$this->criteria->setOffset(($page_number - 1) * $this->items_per_page);
			$this->criteria->setLimit($this->items_per_page);
			
			return ORM::retrieve($this->criteria);
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
