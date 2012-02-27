<?php
	class rest
	{
		/** 
		 * Send a GET requst using cURL 
		 * @param string $url to request 
		 * @param array $get values to send 
		 * @param array $options for cURL 
		 * @return string 
		 */ 
		function get($url, array $get = array(), array $options = array())
		{
			$defaults = array( 
					CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get), 
					CURLOPT_HEADER => 0, 
					CURLOPT_RETURNTRANSFER => TRUE, 
					CURLOPT_TIMEOUT => 4 
			); 
	
			$ch = curl_init(); 
			curl_setopt_array($ch, ($options + $defaults)); 
			if( ! $result = curl_exec($ch))  trigger_error(curl_error($ch)); 
			curl_close($ch); 
			return $result; 
		}
		
		function xml($url)
		{
			$xml = self::get($url);
			
			$xmldoc = new DOMDocument('1.0');
			$xmldoc->loadXML($xml);
			
			return $xmldoc;
		}
		
		function json($url)
		{
		  $json = self::get($url);
		  
		  return json::decode($json);
		}
	}
?>
