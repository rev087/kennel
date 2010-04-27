<?php
	/*
	*	i18n helper: internationalization functions
	*/
	class i18n
	{
		// The current language output selection is either:
		// a) set by the router::$PREFIX - eg.: example.com/en/articles
		// b) set by the default_lang in settings.php
		
		function getLang()
		{
			return router::$PREFIX ? router::$PREFIX : Kennel::getSetting('application', 'default_lang');
		}
		
		function get($string)
		{
			
		}
	}
?>
