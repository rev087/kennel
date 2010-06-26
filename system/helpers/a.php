<?php
	
	// Array helpers
	class a
	{
		function flatten($array, $return=null)
		{
			if (!$return) $return = array();
			
			for ($x = 0; $x < count($array); $x++)
				if (is_array($array[$x]))
				  $return = self::flatten($array[$x],$return);
				elseif ($array[$x]) 
					$return[] = $array[$x];
			return $return;
		}
		
		function split($array, $size=5)
		{
			$result = array();
			while (count($array) >= $size)
			{
				$result[] = array_slice($array, 0, $size);
				$array = array_slice($array, $size);
			}
			
			if (count($array) > 0)
				$result = array_merge($result, array($array));
			
			return $result;
		} 
		
	}
?>
