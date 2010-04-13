<?php
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
	}
?>
