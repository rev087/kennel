<?php
	class string
	{
		
		static function truncate($string, $limit=50, $append='&hellip;')
		{
		  if (strlen($string) <= $limit)
		  	return $string;
		  
		  $return = substr($string, 0, $limit);
		  
		  if (strpos($string,' ') === FALSE)
		  	return "{$return}{$append}";
		  	
		  return preg_replace('/[^\s]+$/', '', $return) . $append;
		}
		
		static function link($string)
		{
			$emails = '';
			$urls = '';
		}
		
	}
?>
