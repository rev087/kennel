<?php
	/*
	*	i18n helper: internationalization functions
	*/
	class i18n
	{
		// The current language output selection is either:
		// a) set by the router::$PREFIX - eg.: example.com/en/articles
		// b) set by the default_lang in settings.php
		
		private static $_BROWSER;
		
		// List of language codes
		private static $LANGS = array(
			'af'=>'afrikaans',
			'ar'=>'arabic',
			'bg'=>'bulgarian',
			'ca'=>'catalan',
			'cs'=>'czech',
			'da'=>'danish',
			'de'=>'german',
			'el'=>'greek',
			'en'=>'english',
			'es'=>'spanish',
			'et'=>'estonian',
			'fi'=>'finnish',
			'fr'=>'french',
			'gl'=>'galician',
			'he'=>'hebrew',
			'hi'=>'hindi',
			'hr'=>'croatian',
			'hu'=>'hungarian',
			'id'=>'indonesian',
			'it'=>'italian',
			'ja'=>'japanese',
			'ko'=>'korean',
			'ka'=>'georgian',
			'lt'=>'lithuanian',
			'lv'=>'latvian',
			'ms'=>'malay',
			'nl'=>'dutch',
			'no'=>'norwegian',
			'pl'=>'polish',
			'pt'=>'portuguese',
			'ro'=>'romanian',
			'ru'=>'russian',
			'sk'=>'slovak',
			'sl'=>'slovenian',
			'sq'=>'albanian',
			'sr'=>'serbian',
			'sv'=>'swedish',
			'th'=>'thai',
			'tr'=>'turkish',
			'uk'=>'ukrainian',
			'zh'=>'chinese'
		);
		
		function browser()
		{
			// Avoid detecting twice
			if (self::$_BROWSER) return self::$_BROWSER;
			
			foreach (self::$LANGS as $key=>$lang)
			{
				if (strpos($_SERVER["HTTP_ACCEPT_LANGUAGE"], $key) === 0)
					return self::$_BROWSER = $key;
			}
		}
		
		function getLang($long_title=false)
		{
			if (router::$PREFIX) $code = router::$PREFIX;
			elseif (Kennel::getSetting('i18n', 'detect') && $browser = self::browser() && preg_match('/' . Kennel::getSetting('i18n','list') . '/', self::browser())) $code = self::browser();
			else $code = Kennel::getSetting('i18n', 'default');
			
			if ($long_title) return self::$LANGS[$code];
			else return $code;
		}
		
		function get($string)
		{
			
		}
	}
?>
