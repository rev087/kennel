<?php
	
	class Request {
		
		function __construct() {
			
		}
		
		function redirect($url) {
			header("Location: $url");
		}
		
	}
	
?>