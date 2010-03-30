<?php
	class esc {
		
		static function attr($string)
		{
			$string = htmlspecialchars($string);
			return $string;
		}
		
	}
?>
