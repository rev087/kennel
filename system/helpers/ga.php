<?php 

	/**
	 * The 'ga' helper streamlines the implementation of Google Analytics
	 * tracking
	 * 
	 * @see https://developers.google.com/analytics/devguides/collection/gajs/eventTrackerGuide Stuff
	 */
	class ga
	{
		static function script($user_account)
		{
			$view = new View('ga');
			$view->user_account = $user_account;
			return $view;
		}

		static function push($category, $action, $label=null, $value=null)
		{
			$text = "_gaq.push(['_trackEvent', '{$category}', '{$action}'";
			$text .= $label !== null && is_string($label) ? ", '{$label}'" : ', null';
			$text .= $value !== null && is_numeric($value) ? ", {$value}" : ', null';
			$text .= "]);";
			return $text;
		}
	}
?>