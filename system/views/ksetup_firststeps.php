<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="pt-br">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Kennel Setup</title>
		<meta name="description" content="Kennel Framework Setup" />
		<link rel="stylesheet" type="text/css" href="<?php print assets::css('ksetup.css') ?>" />
	</head>
	<body>
		
		<div id="wrapper">
			<div id="header">
				<img src="<?php print assets::img('k_logo.png'); ?>" alt="Kennel Framework" />
			</div>
			
			<div id="content">
				<h1>First Steps</h1>
				<p class="msg error">settings.php not found</p>
				<div id="footer" ></div>
			</div>
			
		</div>
		
		<?php if (isset($_GET['omg'])): ?>
		<!--egg-->
		<script type="text/javascript">
			document.body.style.MozTransform = "rotate(5deg)";
			document.body.style.webkitTransform = "rotate(5deg)";
		</script>
		<h1 style="
			clear: both;
			text-align: center;
			color: #E2E0DE;
			background-color: #900;
			margin: 50px;
			position: relative;
			top: 20px;
			line-height: 35px;
			-moz-border-radius: 5px;
			float: left;
			padding: 5px 10px 5px 50px;
      -webkit-transform: rotateY(45deg);

		">
			OMG! YOU BROKE IT!
		</h1>
		<!--/egg-->
		<?php endif; ?>
		
	</body>
</html>
