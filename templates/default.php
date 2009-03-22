<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
	<title>Vault Framework</title>
	<link rel="stylesheet" type="text/css" href="assets/css/vault.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<? Vault::head(); ?>
</head>
<body>

	<div id="wrapper">
		<div id="header">
			<h1>Vault framework</h1>
			<ul id="menu">
				<li id="menu_svn"><a href="?svn">SVN</a></li>
				<li id="menu_download"><a href="?download">Download</a></li>
				<li id="menu_documentation"><a href="?documentation">Documentation</a></li>
				<li id="menu_home"><a href="?">Home</a></li>
			</ul>
		</div>
		
		<div id="middle_bar">
			<p id="description">Vault is a MVC Framework that helps building web applications with ease.</p>
			<a href="?download" id="download_button">Download v1.0</a>
		</div>
		
		<div id="main">	
			<? Vault::view(); ?>
			<div id="footer">
			</div>
		</div>
	</div>
	
</body>
</html>