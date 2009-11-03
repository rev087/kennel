<?php
	class Nav {
		
		function view($view) {
			$path = "views/{$view}.php";
			if(is_file($path)) include($path);
			else include("views/404.php");
		}
		
	}
?>
