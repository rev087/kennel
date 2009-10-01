<?php
	
	class Request {
		
		static $controller;
		static $action;
		static $arguments;
		
		function __construct()
		{
			
		}
		
		function redirect($url)
		{
			header("Location: $url");
		}
		
		static function debug($return=false)
		{
			$table = XML::element('table', null, array(
				'border'=>'1', 'style'=>'background-color: #FFF; color: #000;'
			));
			
			$tr = XML::element('tr', $table);
			$td = XML::element('td', $tr, array('style'=>'font-weight: bold;'), 'Controller');
			$td = XML::element('td', $tr, null, self::$controller);
			
			$tr = XML::element('tr', $table);
			$td = XML::element('td', $tr, array('style'=>'font-weight: bold;'), 'Action');
			$td = XML::element('td', $tr, null, self::$action);
			
			$tr = XML::element('tr', $table);
			$td = XML::element('td', $tr, array('style'=>'font-weight: bold;'), 'Arguments');
			$td = XML::element('td', $tr, null, self::$arguments);
			
			if($return) return $table;
			else print $table;
		}
		
	}
	
?>
