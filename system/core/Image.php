<?php
	class Image
	{
		private $image;
		private $width;
		private $height;
		private $mime;
		private $path;
		
		function __construct($image) {
			if (is_string($image))
			{
				$this->path = pathinfo($image);
				$imageSize = getimagesize($image);
				$this->width = $imageSize[0];
				$this->height = $imageSize[1];
				$this->mime = $imageSize['mime'];
				
				switch ($this->mime)
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
				$this->width = imagesx($image);
				$this->height = imagesy($image);
				$this->image = $image;
			}
		}
		
		function output() {
			header("Content-Type: image/jpeg");
			imagejpeg($this->image, null, 95);
		}

		function resize($limit)
		{
			$prop = $this->width>$this->height?$limit/$this->width:$limit/$this->height;
			
			$new_w = $this->width * $prop;
			$new_h = $this->height * $prop;
			
			$resized = imagecreatetruecolor($new_w, $new_h);
			imagecopyresampled($resized, $this->image, 0, 0, 0, 0, $new_w, $new_h, $this->width, $this->height);
			return $this->image = $resized;
		}
		
		function resizeCrop($width, $height)
		{
			$difW = $width - $this->width;
			$difH = $height - $this->height;
			
			print $difW . ' ' . $difH;
		}
		
		function square($limit)
		{
			$prop = $this->width<$this->height?$limit/$this->width:$limit/$this->height;
			
			$new_w = $this->width * $prop;
			$new_h = $this->height * $prop;
			
			$new_x = ($new_w - $limit) / 2;
			$new_y = ($new_h - $limit) / 2;
			
			$cropped = imagecreatetruecolor($limit, $limit);
			imagecopyresampled($cropped, $this->image, 0, 0, $new_x, $new_y, $new_w, $new_h, $this->width, $this->height);
			return $this->image = $cropped;
		}
		
		function save($filename) {
			imagejpeg($this->image, $filename, 95);
		}

	}
?>
