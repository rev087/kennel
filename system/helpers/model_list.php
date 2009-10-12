<?php	
	class model_list
	{	
		private $model_name;
		private $model;
		
		private $pagination;
		private $page_number;
		private $items_per_page;
		private $page_url;
		
		private $filter_fields;
		private $filters = array();
		private $input;
		
		var $page;
		
		private $fields = array();
		private $actions = array();
		
		var $empty_message = 'No results found';
		
		function __construct($model_name, $fields=null)
		{
			$this->model = new Model($model_name);
			$this->model_name = $model_name;
			$this->fields = $fields;
		}
		
		function setPagination($items_per_page=10, $page_url='?pg={page}')
		{
			$this->items_per_page = $items_per_page;
			$this->page_url = $page_url;
		}
		
		function setPage($page_number=1)
		{
			$this->page_number = $page_number;
		}
		
		function setFiltering($filter_fields)
		{
			$this->input = new Input();
			$this->filter_fields = $filter_fields;
			
			foreach($filter_fields as $fieldname=>$caption)
			{
				if($this->input->get("filter_{$fieldname}"))
					$this->filters[] = "`${fieldname}` LIKE '%" . $this->input->get("filter_{$fieldname}") . "%'";
			}
		}
		
		private function getPage()
		{
			if(!isset($this->items_per_page))
			{
				$this->page = $this->model->get($this->model_name, $this->filters);
			}
			else
			{
				$array = $this->model->get($this->model_name, $this->filters);
				$this->pagination = new pagination($array, $this->items_per_page, $this->page_url);
				$this->page = $this->pagination->getPage($this->page_number);
			}
		}
		
		function setAction($link, $html, $vars=array())
		{
			if(!is_array($vars)) $vars = array($vars);
			
			$this->actions[] = array('link'=>$link, 'html'=>$html, 'vars'=>$vars);
		}
		
		function render_pagination()
		{
			if(!isset($this->page)) $this->getPage();
			
			$this->pagination->printLinks();
		}
		
		function render_filtering()
		{
			
			$form = XML::element('form', null, array('class'=>'filter_box'));
			$clearvars = array();
			
			foreach($this->filter_fields as $fieldname=>$caption)
			{
				$p = XML::element('p', $form);
				$label = XML::element('label', $p, array('for'=>'filter_'.$fieldname), $caption);
				$input = XML::element('input', $p, array(
					'name'=>$fieldname,
					'class'=>'text',
					'name'=>"filter_{$fieldname}",
					'id'=>"filter_{$fieldname}",
					'value'=>$this->input->get("filter_{$fieldname}")
				));
			}
			
			$p = XML::element('p', $form);
			$ok = XML::element('input', $p, array(
				'type'=>'submit',
				'class'=>'ok',
				'value'=>'Filter'
			));
			$p->setText(' ');
			$cancel = XML::element('input', $p, array(
				'type'=>'reset',
				'class'=>'cancel',
				'value'=>'Clear'
			));
			
			print $form;
		}
		
		function render_list()
		{
			if(!isset($this->page)) $this->getPage();
			
			$table = XML::element('table');
			$tr = XML::element('tr', $table);
			
			//Columns
			foreach($this->fields as $name=>$caption)
			{
				$th = XML::element('th', $tr, null, $caption);
			}
			
			//Actions
			if(count($this->actions) > 0 && count($this->page) > 0)
				$th = XML::element('th', $tr, null, 'Actions');
			
			if(count($this->page) == 0)
			{
				$tr = XML::element('tr', $table, array('class'=>'error'));
				$td = XML::element('td', $tr, array('colspan'=>count($this->fields)));
				$td->setText($this->empty_message);
			}
			else
			{
				$n=0;
				foreach($this->page as $row) {
					$class = $n++%2?'even':'odd';
					$tr = XML::element('tr', $table, array('class'=>$class));
					
					//Columns
					foreach($this->fields as $name=>$caption)
					{
						$td = XML::element('td', $tr);
						$td->setText($row->$name);
					}
					//Actions
					if(count($this->actions) && count($this->page) > 0)
					{
						$td = XML::element('td', $tr);
						foreach($this->actions as $action)
						{
							foreach($action['vars'] as $key=>$var)
							{
								$key++;
								$action['link'] = str_replace("%{$key}", $row->$var, $action['link']);
								$action['html'] = str_replace("%{$key}", $row->$var, $action['html']);
							}
							
							
							$a = XML::element('a', $td, array('href'=>$action['link'], 'class'=>'list_action'));
							$a->setText(" {$action['html']} ");
						}
					}
				}
			}
			
			print $table;
		}
		
	}
?>
