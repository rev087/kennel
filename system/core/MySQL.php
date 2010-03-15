<?php
	
	//version 1.0
	//requires settings.php
	
	class MySQL {
		public static $host;
		public static $user;
		public static $pass;
		public static $database;
		private static $CONN;
		
		public static $last_query;
		public static $num_queries = 0;
		private static $structure_cache = array();
		
		//constructor/destructor functions
		//////////////////////////////////
		function __construct() {
			if(!self::$CONN) self::connect();
		}
		
		function connect($host=null, $user=null, $pass=null, $database=null)
		{
			if (self::$CONN && @mysql_ping(self::$CONN)) return;
			
			self::$host = Kennel::getSetting('database','host');
			self::$user = Kennel::getSetting('database','user');
			self::$pass = Kennel::getSetting('database','pass');
			self::$database = Kennel::getSetting('database','database');
			
			self::$CONN = mysql_connect(($host?$host:self::$host), ($user?$user:self::$user), ($pass?$pass:self::$pass));
			mysql_set_charset('utf-8', self::$CONN);
			mysql_select_db(($database?$database:self::$database), self::$CONN);
		}
		
		function __destruct() {
			@mysql_close(self::$CONN);
		}
		
		//query functions
		/////////////////
		function query($sql) {
			self::connect();
			
			self::$num_queries++;
			$rs = mysql_query($sql, self::$CONN);
			self::$last_query = $sql;
			if(mysql_error()) self::dumpError($sql);
			else return $rs;
		}
		
		function dumpError($sql) {
			$table = XML::element('table', null, array('border'=>'1'));
			
			$tr = XML::element('tr', $table);
			$th = XML::element('th', $tr, array('colspan'=>'2'), "SQL query returned an error");
			
			$tr = XML::element('tr', $table);
			$th = XML::element('th', $tr, null, 'query');
			$td = XML::element('td', $tr, null, "<pre>{$sql}</pre>");
			
			$tr = XML::element('tr', $table);
			$th = XML::element('th', $tr, null, 'error');
			$td = XML::element('td', $tr, null, mysql_error(self::$CONN));
			
			$full_backtrace = debug_backtrace();
			$backtrace = $full_backtrace[2];
			
			
			if (isset($backtrace['file']))
			{
				$tr = XML::element('tr', $table);
				$th = XML::element('th', $tr, null, 'file');
				$td = XML::element('td', $tr, null, $backtrace['file']);
			}
			
			if (isset($backtrace['line']))
			{
				
				$tr = XML::element('tr', $table);
				$th = XML::element('th', $tr, null, 'line');
				$td = XML::element('td', $tr, null, $backtrace['line']);
			}
			
			if($backtrace['class']) {
				$tr = XML::element('tr', $table);
				$th = XML::element('th', $tr, null, 'class');
				$td = XML::element('td', $tr, null, $backtrace['class']);
			}
			
			if($backtrace['function']) {
				$tr = XML::element('tr', $table);
				$th = XML::element('th', $tr, null, 'function');
				$td = XML::element('td', $tr, null, $backtrace['function']);
			}
			
			print $table;
			die();
		}
		
		function queryFetch($sql) {
			$rs = $this->query($sql);
			return $this->fetch($rs);
		}
		
		//result functions
		//////////////////
		function num_rows($rs) {
			return mysql_num_rows($rs);
		}
		
		function affected_rows() {
			return mysql_affected_rows();
		}
		
		function insert_id() {
			return mysql_insert_id(self::$CONN);
		}
		
		//fetch functions
		/////////////////
		function fetch($rs) {
			if(gettype($rs) != "resource") return false;
			else return mysql_fetch_object($rs);
		}
		
		function fetch_object($rs=null) {
			if(gettype($rs) != "resource") return false;
			else return mysql_fetch_object($rs);
		}
		
		function fetch_row($rs=null) {
			if(gettype($rs) != "resource") return false;
			else return mysql_fetch_row($rs);
		}
		
		function fetch_array($rs=null) {
			if(gettype($rs) != "resource") return false;
			else return mysql_fetch_array($rs);
		}
		
		function fetch_assoc($rs=null) {
			if(gettype($rs) != "resource") return false;
			else return mysql_fetch_assoc($rs);
		}
		
		function fetch_all($rs) {
			if(gettype($rs) != "resource") return false;
			$ret = array();
			while($obj = mysql_fetch_object($rs)) {
				$ret[] = $obj;
			}
			return $ret;
		}
		
		function fetch_all_array($rs) {
			if(gettype($rs) != "resource") return false;
			$ret = array();
			while($obj = mysql_fetch_array($rs)) {
				$ret[] = $obj;
			}
			return $ret;
		}
		
		//misc functions
		////////////////
		function escape_string($string)
		{
			self::connect();
			return mysql_real_escape_string($string, self::$CONN);
		}
		
		function dateToUs($str) {
			if(!$str) return false;
			$split = split("/",$str);
			if(!$split[2] || !$split[1] || !$split[0]) return false;
			return $split[2]."-".$split[1]."-".$split[0];
		}
		
		function dateToBr($str) {
			if(!$str) return false;
			$split = split("-",$str);
			if(!$split[2] || !$split[1] || !$split[0]) return false;
			return $split[2]."/".$split[1]."/".$split[0];
		}
		
		function num_queries() {
			return self::$num_queries;
		}
		
		function getTableStructure($table) {
			if(array_key_exists($table, self::$structure_cache)) return self::$structure_cache[$table];
			
			$structure = array();
			$rs = self::query("DESC {$table}");
			while($row = self::fetch($rs)) {
				unset($field);
				$field = array(
					'name'=>$row->Field,
					'type'=>$row->Type,
					'requiresd'=>$row->Null=="NO"?0:1,
					'default_value'=>$row->Default,
					'value'=>$row->Default?$row->Default:null
				);
				$structure[$row->Field] = $field;
			}
			
			self::$structure_cache[$table] = $structure;
			return $structure;
		}
		
	}
?>
