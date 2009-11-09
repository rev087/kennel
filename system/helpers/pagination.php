<?php
	
	class pagination {
		
		private $shebang;
		private $pages = array();
		private $items_per_page;
		private $page_number;
		
		function __construct($shebang, $items_per_page, $urlformat='?pg={page}')
		{
			$this->shebang = $shebang;
			$this->items_per_page = $items_per_page;
			$this->urlformat = $urlformat;
			$this->pages = array_chunk($shebang, $items_per_page);
		}
		
		function getPage($page_number=1)
		{
			$this->page_number = $page_number;
			
			if(count($this->pages) > 0)
				return $this->pages[$this->page_number-1];
			else
				return array();
		}
		
		function printLinks($return=false)
		{
			// Initializing the result string
			$links = '';
			
			// Make sure to show pagination even on an empty page
			if(count($this->pages) == 0) return $links = '<span>1</span>';
			
			// First
			if ($this->page_number > 4)
			{
				$links .= $this->anchor(1, '&lt;&lt;');
				//$links .= '<span>...</span>';
			}
			
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
				//if ($i+3 <= count($this->pages))
					$links .= $this->anchor($i);
			}
			
			// Last
			if ($this->page_number < count($this->pages) - 3)
			{
				//$links .= '<span>...</span>';
				$links .= $this->anchor(count($this->pages), '&gt;&gt;');
			}
			
			// Return or print the result string
			if($return) return $links;
			else print $links;
		}
		
		function anchor($page, $label=null)
		{
			if(!$label) $label = $page;
			if($page > count($this->pages)) return '';
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
