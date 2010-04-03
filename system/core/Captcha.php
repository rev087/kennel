<?php
	class Captcha
	{
		var $image;
		var $text;
		var $id;
		var $width;
		var $height;
		var $noise;
		
		static function check($string, $id='captcha')
		{
		}
		
		public function __construct($width=160, $height=40, $id='captcha')
		{
			$this->width = $width;
			$this->height = $height;
			$this->id = $id;
			
			$this->noise = $width * $height / 25;
			$this->text = substr(md5(uniqid()), 0, 5);
			
			$this->generate();
		}
		
		private function setSession()
		{
			if(!session_id())
				session_start();
			
			$_SESSION[$this->id] = $this->text;
		}
		
		private function generate()
		{
			$this->image = imagecreate($this->width, $this->height);
			
			imagecolorallocate($this->image, 200, 200, 200);
			$text_color = imagecolorallocate($this->image, 50, 50, 50);
			
			$this->jamm();
			
			$font = imageloadfont(Kennel::$ROOT_PATH . '/system/assets/files/dots.gdf');
			imagestring($this->image, $font, 5, 5,  $this->text, $text_color);
		}
		
		public function jamm() 
		{
			$color = imagecolorallocate($this->image, 50, 50, 50);
			for ($i=0; $i<=$this->noise; $i++)
			{
				$x = rand(0, $this->width);
				$y = rand(0, $this->height);
				
				imagesetpixel($this->image, $x, $y, $color);
				imagesetpixel($this->image, $x+1, $y, $color);
			}
		}
		
		public function output()
		{
			header("Content-Type: image/png");
			imagepng($this->image);
		}
		
		function __destruct()
		{
			imagedestroy($this->image);
		}
	}
?>
