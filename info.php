<?php
	$info['id'] = "banners";
	
	$info['name']['en_us'] = "Banners";
	$info['name']['pt_br'] = "Banners";
	
	$info['menu'][] = array(
		'category'=>'media',
		'id'=>'banners',
		'label'=>'Banners'
	);
	
	$info['menu'][] = array(
		'category'=>'settings',
		'id'=>'banner_settings',
		'label'=>'Banners'
	);
	
	$info['dependencies'][] = 'moshcore';
	$info['dependencies'][] = 'imagebank';
?>
