<?php
	class Blog extends Controller {
		
		function index($page="") {
			print '<h1>Blog</h1>';
			var_dump(func_get_args());
		}
		
		function post() {
			print '<h1>Blog/Post</h1>';
			var_dump(func_get_args());
		}
		
	}
?>