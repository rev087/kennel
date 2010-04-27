<?php
	// Database settings
	$settings['database']['host'] = 'adddress';
	$settings['database']['user'] = 'username';
	$settings['database']['pass'] = 'password';
	$settings['database']['database'] = 'database';
	
	// Application settings
	$settings['application']['app_title'] = 'Example Application';
	$settings['application']['app_id'] = 'example_app';
	$settings['application']['use_mod_rewrite'] = true;
	$settings['application']['show_benchmark'] = false;
	$settings['application']['debug_mode'] = true;
	$settings['application']['log_errors'] = true;
	
	// i18n settings
	$settings['i18n']['enabled'] = true; // Other i18n settings are ignored if this is set to false
	$settings['i18n']['detect'] = true;
	$settings['i18n']['default'] = 'pt';
	$settings['i18n']['list'] = 'pt|en|es';
	
	// User authentication - if your application doesn't use authentication, you can safelly ignore these settings
	$settings['auth']['model_name'] = 'user';
	$settings['auth']['username_field'] = 'username';
	$settings['auth']['password_field'] = 'password';
	$settings['auth']['userlevel_field'] = null;
	$settings['auth']['lifetime'] = 1209600;
?>
