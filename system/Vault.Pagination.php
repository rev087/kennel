<?php
	
	class Pagination {
		
		private $shebang;
		private $pages;
		private $items_per_page;
		private $page_number;
		
		function __construct($shebang, $items_per_page) {
			$this->shebang = $shebang;
			$this->items_per_page = $items_per_page;
			$this->pages = array_chunk($shebang, $items_per_page);
		}
		
		function getPage($page_number=null) {
			if(!$page_number) $this->page_number = 1;
			else $this->page_number = $page_number;
			
			return $this->pages[$this->page_number-1];
		}
		
		function printLinks() {
			foreach($this->pages as $page => $item) {
				$page++; //user-readable page is aways +1 relative to the array index
				
				if($page == $this->page_number) print '<span>'.$page.'</span>';
				else print '<a href="?pg='.$page.'">'.$page.'</a>';
			}
		}
		
	}
	
?>
