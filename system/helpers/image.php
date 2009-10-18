<?php
	class image
	{
		private $image;
		private $info;
		private $path;
		
		function __construct($image) {
			if (is_string($image))
			{
				$this->path = pathinfo($image);
				$this->info = getimagesize($image);
				
				switch ($this->info['mime'])
				{
					case 'image/jpeg':
						$this->image = imagecreatefromjpeg($image);
						break;
					case 'image/png':
						$this->image = imagecreatefrompng($image);
						break;
					case 'image/gif':
						$this->image = imagecreatefromgif($image);
						break;
				}
			} else {
				$this->info = array(imagesx($image), imagesy($image));
				$this->image = $image;
			}
		}
		
		function output() {
			header("Content-Type: image/jpeg");
			imagejpeg($this->image, null, 95);
		}

		function resize($limit)
		{
			$prop = $this->info[0]>$this->info[1]?$limit/$this->info[0]:$limit/$this->info[1];
			
			$new_w = $this->info[0] * $prop;
			$new_h = $this->info[1] * $prop;
			
			$resized = imagecreatetruecolor($new_w, $new_h);
			imagecopyresampled($resized, $this->image, 0, 0, 0, 0, $new_w, $new_h, $this->info[0], $this->info[1]);
			return $this->image = $resized;
		}

		function square($limit)
		{
			$prop = $this->info[0]<$this->info[1]?$limit/$this->info[0]:$limit/$this->info[1];
			
			$new_w = $this->info[0] * $prop;
			$new_h = $this->info[1] * $prop;
			
			$new_x = ($new_w - $limit) / 2;
			$new_y = ($new_h - $limit) / 2;
			
			$cropped = imagecreatetruecolor($limit, $limit);
			imagecopyresampled($cropped, $this->image, 0, 0, $new_x, $new_y, $new_w, $new_h, $this->info[0], $this->info[1]);
			return $this->image = $cropped;
		}
		
		function save($filename) {
			imagejpeg($this->image, $filename, 95);
		}

	}
?>
