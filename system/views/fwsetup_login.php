<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="pt-br">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Natural Framework Setup</title>
		<meta name="description" content="Materiais para construção." />
		<link rel="stylesheet" type="text/css" href="<?php print url('assets/css/fwsetup.css') ?>" />
		<script type="text/javascript" src="<?php print url('assets/js/mootools-1.2.3-core-yc.js') ?>"></script>
	</head>
	<body>
		
		<div id="wrapper">
			<div id="header">
				<img src="<?php print url('assets/img/fw_logo.png'); ?>" alt="Natural Framework" />
			</div>
			
			<ul id="menu">
				<li <?php if($action=='modules') print 'class="selected"'; ?>>
					<a id="modules" href="<?php print url('fwsetup/modules') ?>">
						Modules
					</a>
				</li>
				<li <?php if($action=='database') print 'class="selected"'; ?>>
					<a id="database" href="<?php print url('fwsetup/database') ?>">
						Database
					</a>
				</li>
				<li <?php if($action=='settings') print 'class="selected"'; ?>>
					<a id="settings" href="<?php print url('fwsetup/settings') ?>">
						Settings
					</a>
				</li>
			</ul>
			
			<div id="content">
				<h1><?php print ucfirst($action); ?></h1>
				
				<ul>
					<li>Administration</li>
					<li>Advanced Search</li>
					<li>Administration</li>
					<li>Administration</li>
					<li>Administration</li>
					<li>Administration</li>
				</ul>
				
				<div id="footer" ></div>
			</div>
			
		</div>
		
	</body>
</html>
