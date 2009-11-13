<?php
	//database settings
	$settings['database']['host'] = 'localhost';
	$settings['database']['user'] = 'root';
	$settings['database']['pass'] = '';
	$settings['database']['database'] = 'cinemagic';
	
	//paths
	$settings['path']['system'] = '/system';
	$settings['path']['views'] = '/application/views';
	$settings['path']['controllers'] = '/application/controllers';
	$settings['path']['assets'] = '/application/assets';
	$settings['path']['modules'] = '/modules';
	$settings['path']['helpers'] = '/helpers';
	$settings['path']['models'] = '/models';
	
	//application settings
	$settings['application']['app_id'] = 'cinemagic';
	$settings['application']['use_mod_rewrite'] = true;
	$settings['application']['show_benchmark'] = false;
	$settings['application']['debug_mode'] = true;
	$settings['application']['log_errors'] = true;
	
	//user authentication - if your application doesn't use authentication, you can safelly ignore these settings
	$settings['auth']['model_name'] = 'user';
	$settings['auth']['username_field'] = 'username';
	$settings['auth']['password_field'] = 'password';
	$settings['auth']['userlevel_field'] = null;
	$settings['auth']['lifetime'] = 1209600;
?>
