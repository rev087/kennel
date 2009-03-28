<?php
	
	//version 1.0
	//requires settings.php
	require_once('system/Vault.XML.php');
	
	class MySQL {
		public static $host;
		public static $user;
		public static $pass;
		public static $database;
		static $conn;
		
		public static $num_queries = 0;
		private static $structure_cache = array();
		//paging
		
		//constructor/destructor functions
		//////////////////////////////////
		function __construct($host=null, $user=null, $pass=null, $database=null) {
			if(!self::$conn) {
				self::$host = Vault::getSetting('database','host');
				self::$user = Vault::getSetting('database','user');
				self::$pass = Vault::getSetting('database','pass');
				self::$database = Vault::getSetting('database','database');
				self::$conn = mysql_connect(($host?$host:self::$host), ($user?$user:self::$user), ($pass?$pass:self::$pass));
				mysql_set_charset('utf-8', self::$conn);
				mysql_select_db(($database?$database:self::$database), self::$conn);
			}
		}
		
		function __destruct() {
			if(self::$conn) @mysql_close(self::$conn);
		}
		
		//query functions
		/////////////////
		function query($sql) {
			self::$num_queries++;
			$rs = mysql_query($sql, self::$conn);
			if(mysql_error()) self::dumpError($sql);
			else return $rs;
		}
		
		function dumpError($sql) {
			$table = new XMLElement('table', null, array('border'=>'1'));
			
			$tr = new XMLElement('tr', $table);
			$th = new XMLElement('th', $tr, array('colspan'=>'2'), "SQL query returned an error");
			
			$tr = new XMLElement('tr', $table);
			$th = new XMLElement('th', $tr, null, 'query');
			$td = new XMLElement('td', $tr, null, $sql);
			
			$tr = new XMLElement('tr', $table);
			$th = new XMLElement('th', $tr, null, 'error');
			$td = new XMLElement('td', $tr, null, mysql_error(self::$conn));
			
			$full_backtrace = debug_backtrace();
			$backtrace = $full_backtrace[2];
			
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
			return mysql_insert_id(self::$conn);
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
			if(self::$structure_cache[$model_name]) return self::$structure_cache[$model_name];
			
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
