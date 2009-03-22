<?php
	require_once('controllers/controller.Settings.php');
	require_once('controllers/controller.XML.php');
	//set_error_handler(array("Debug", "error"));
	
	class Debug {
		
		static $backtrace;
		static $notice = E_USER_NOTICE;
		static $warning = E_USER_WARNING;
		static $error = E_USER_ERROR;
		
		/*
		static function backtrace() {
			self::$backtrace = debug_backtrace();
			
			foreach(self::$backtrace as $step) {
				if($step['class'] != "Debug") {
					print '<br /><table border="1">';
					foreach($step as $key=>$val) {
						if($key != "object") {
							print '<tr>';
							print "<td><strong>{$key}</strong></td>";
							print '<td>'.print_r($val, true).'</td>';
							print '</tr>';
						}
					}
					print '</table><br />';
				}
			}
			
		}
		*/
		
		function dumpError($error) {
			$table = new XMLElement('table', null, array('border'=>'1'));
			
			$tr = new XMLElement('tr', $table);
			$th = new XMLElement('th', $tr, array('colspan'=>'2'), "Error");
			
			$tr = new XMLElement('tr', $table);
			$th = new XMLElement('th', $tr, null, 'error');
			$td = new XMLElement('td', $tr, null, $error);
			
			$full_backtrace = debug_backtrace();
			$backtrace = $full_backtrace[3];
			
			$tr = new XMLElement('tr', $table);
			$th = new XMLElement('th', $tr, null, 'file');
			$td = new XMLElement('td', $tr, null, $backtrace['file']);
			
			$tr = new XMLElement('tr', $table);
			$th = new XMLElement('th', $tr, null, 'line');
			$td = new XMLElement('td', $tr, null, $backtrace['line']);
			
			if($backtrace['class']) {
				$tr = new XMLElement('tr', $table);
				$th = new XMLElement('th', $tr, null, 'class');
				$td = new XMLElement('td', $tr, null, $backtrace['class']);
			}
			
			if($backtrace['function']) {
				$tr = new XMLElement('tr', $table);
				$th = new XMLElement('th', $tr, null, 'function');
				$td = new XMLElement('td', $tr, null, $backtrace['function']);
			}
			
			print $table;
			die();
		}
		
		static function error_handler($errno = E_USER_WARNING, $errstr) {
			self::$backtrace = debug_backtrace();
			
			$ignore = array(E_STRICT);
			
			if(array_search($errno, $ignore) === false && Settings::get('debug')) {
				print '<pre style="text-align: left;">';
				debug_print_backtrace();
				print '</pre>';
			}
			
			if(Settings::get('log_errors')) {
				error_log("{$message}", 3, "errorlog.txt");
			}
			
		}
		
		static function error($errstr, $backtrace=false) {
			if(Settings::get('debug_mode')) {
				self::dumpError($errstr);
				die();
			}
		}
		
	}
?>
