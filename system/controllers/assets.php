<?php
	class Assets_controller extends Controller{
		function __construct() {
			parent::__construct();
		}
		
		function index($type, $file) {
			$target = Vault::$app_root_uri . Vault::getSetting('path', 'assets') .  "/{$type}/{$file}";
			header("location: $target");
		}
		
	}
?>
