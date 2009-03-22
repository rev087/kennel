<?php
	class Main extends Controller {
		
		function index($page="") {
			require_once(Vault::getPath('templates').'\default.php');
		}
		
		function about() {
			print '<h1>About</h1>';
		}
		
		function notfound() {
			require_once(Vault::getPath('templates').'\default.php');
			print 'NOT  FOUND!';
		}
		
	}
?>