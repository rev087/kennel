<?php
/*
Credits:
	This is a slightly modified class from the Kirby toolkit (http://getkirby.com) by Bastian Allgeier (http://bastian-allgeier.de),
	wich itself is a php port from Rafael Lima's original Javascript CSS Browser Selector: http://rafael.adm.br/css_browser_selector
*/

class browser {

	static public $ua = false;
	static public $browser = false;
	static public $engine = false;
	static public $version = false;
	static public $platform = false;

	function name($ua=null) {
		self::detect($ua);
		return self::$browser;
	}

	function engine($ua=null) {
		self::detect($ua);
		return self::$engine;
	}

	function version($ua=null) {
		self::detect($ua);
		return self::$version;
	}

	function platform($ua=null) {
		self::detect($ua);
		return self::$platform;
	}

	function mobile($ua=null) {
		self::detect($ua);
		return (self::$platform == 'mobile') ? true : false;
	}

	function iphone($ua=null) {
		self::detect($ua);
		return (in_array(self::$platform, array('ipod', 'iphone'))) ? true : false;
	}

	function css($ua=null, $array=false) {
		if (!self::$ua) self::detect($ua);
		$css[] = self::$engine;
		$css[] = self::$browser;
		if(self::$version) $css[] = self::$browser . str_replace('.', '_', self::$version);
		$css[] = self::$platform;
		return ($array) ? $css : implode(' ', $css);
	}

	function detect($ua=null) {
		$ua = ($ua) ? str::lower($ua) : strtolower($_SERVER['HTTP_USER_AGENT']);

		// don't do the detection twice
		if(self::$ua == $ua) return array(
			'browser'	=> self::$browser,
			'engine'	 => self::$engine,
			'version'	=> self::$version,
			'platform' => self::$platform
		);

		self::$ua		 = $ua;
		self::$browser	= false;
		self::$engine	 = false;
		self::$version	= false;
		self::$platform = false;

		// browser
		if(!preg_match('/opera|webtv/i', self::$ua) && preg_match('/msie\s(\d)/', self::$ua, $array)) {
			self::$version = $array[1];
			self::$browser = 'ie';
			self::$engine	= 'trident';
		}	else if(strstr(self::$ua, 'firefox/2')) {
			self::$version = 2;
			self::$browser = 'fx';
			self::$engine	= 'gecko';
		}	else if (strstr(self::$ua, 'firefox/3.5')) {
			self::$version = 3.5;
			self::$browser = 'fx';
			self::$engine	= 'gecko';
		}	else if(strstr(self::$ua, 'firefox/3')) {
			self::$version = 3;
			self::$browser = 'fx';
			self::$engine	= 'gecko';
		} else if(strstr(self::$ua, 'gecko/')) {
			self::$browser = 'gecko';
			self::$engine	= 'gecko';
		} else if(preg_match('/opera(\s|\/)(\d+)/', self::$ua, $array)) {
			self::$engine	= 'presto';
			self::$browser = 'opera';
			self::$version = $array[2];
		} else if(strstr(self::$ua, 'konqueror')) {
			self::$browser = 'konqueror';
			self::$engine	= 'webkit';
		} else if(strstr(self::$ua, 'chrome')) {
			self::$browser = 'chrome';
			self::$engine	= 'webkit';
		} else if(strstr(self::$ua, 'iron')) {
			self::$browser = 'iron';
			self::$engine	= 'webkit';
		} else if(strstr(self::$ua, 'applewebkit/')) {
			self::$browser = 'safari';
			self::$engine	= 'webkit';
			if(preg_match('/version\/(\d+)/i', self::$ua, $array)) { self::$version = $array[1]; }
		} else if(strstr(self::$ua, 'mozilla/')) {
			self::$engine	= 'gecko';
			self::$browser = 'mozilla';
		}

		// platform
		if(strstr(self::$ua, 'j2me')) {
			self::$platform = 'mobile';
		} else if(strstr(self::$ua, 'iphone')) {
			self::$platform = 'iphone';
		} else if(strstr(self::$ua, 'ipod')) {
			self::$platform = 'ipod';
		} else if(strstr(self::$ua, 'mac')) {
			self::$platform = 'mac';
		} else if(strstr(self::$ua, 'darwin')) {
			self::$platform = 'mac';
		} else if(strstr(self::$ua, 'webtv')) {
			self::$platform = 'webtv';
		} else if(strstr(self::$ua, 'win')) {
			self::$platform = 'win';
		} else if(strstr(self::$ua, 'freebsd')) {
			self::$platform = 'freebsd';
		} else if(strstr(self::$ua, 'x11') || strstr(self::$ua, 'linux')) {
			self::$platform = 'linux';
		}

		return array(
			'browser'	=> self::$browser,
			'engine'	 => self::$engine,
			'version'	=> self::$version,
			'platform' => self::$platform
		);

	}

}
?>
