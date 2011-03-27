<?php
	class flash
	{
		public static function embed($filename, $width=null, $height=null, $wmode='opaque')
		{
			$path = assets::flash($filename);
			
			return '<object classid=˜clsid:D27CDB6E-AE6D-11cf-96B8-444553540000˜ width="'.$width.'" height="'.$height.' id="banner" name="player1">
			   <param name="movie" value="player.swf">
			   <param name="allowfullscreen" value="true">
			   <param name="allowscriptaccess" value="always">
			   <param name="flashvars" value="file=playlist.xml">
			   <param name="wmode" value="'.$wmode.'">
			   <embed id="banner"
			          name="banner"
			          src="'.$path.'"
			          width="'.$width.'"
			          height="'.$height.'"
			          wmode="'.$wmode.'"
			          allowscriptaccess="always"
			          allowfullscreen="true"
			          flashvars="file=playlist.xml"
			   />
			</object>';
		}
	}
?>