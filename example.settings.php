<?php
	// Database settings
	$settings['database']['host'] = 'adddress';
	$settings['database']['user'] = 'username';
	$settings['database']['pass'] = 'password';
	$settings['database']['database'] = 'database';
	$settings['database']['prefix'] = '';
	
	// Application settings
	$settings['application']['title'] = 'Example Application';
	$settings['application']['id'] = 'example_app';
	$settings['application']['timezone'] = 'America/Sao_Paulo';
	$settings['application']['use_mod_rewrite'] = true;
	$settings['application']['show_benchmark'] = false;
	$settings['application']['debug_mode'] = true;
	$settings['application']['log_errors'] = true;
	
	// E-mail settings
	$settings['email']['smtp_host'] = 'smtp.example.com';
	$settings['email']['port'] = '465';
	$settings['email']['user'] = 'user@example.com';
	$settings['email']['pass'] = 'password';
	$settings['email']['encryption'] = 'ssl'; // "ssl", "tsl" or null
	
	// i18n settings
	$settings['i18n']['enabled'] = false; // Other i18n settings are ignored if this is set to false
	$settings['i18n']['detect'] = false;
	$settings['i18n']['redirect'] = false;
	$settings['i18n']['default'] = 'pt'; // Ignored if detect is enabled
	$settings['i18n']['list'] = 'pt|en|es';
	
	// User authentication - if your application doesn't use authentication, you can safelly ignore these settings
	$settings['auth']['model_name'] = 'user';
	$settings['auth']['username_field'] = 'username';
	$settings['auth']['password_field'] = 'password';
	$settings['auth']['userlevel_field'] = null;
	$settings['auth']['lifetime'] = 1209600;
?>
