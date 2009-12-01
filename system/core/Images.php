<?php
	require_once("models/model.Image.php");
	
	class Images {
		
		function store($file, $category, $id=null) {
			$uniqid = uniqid();
			$pathinfo = pathinfo($file['name']);
			$path = "img/library/{$pathinfo['filename']}{$uniqid}.{$pathinfo['extension']}";
			
			move_uploaded_file($file['tmp_name'], $path);
			
			$size = getimagesize($path);
			
			$img = new Image($id);
			$img->category = $category;
			$img->filepath = $path;
			$img->name = $pathinfo['basename'];
			$img->format = $pathinfo['extension'];
			$img->width = $size[0];
			$img->height = $size[1];
			$img->save();
			
			return $img->id;
		}
		
		function getAll($category=null) {
			return Image::getAll($category);
		}
		
		function path($id) {
			$img = new Image($id);
			return $img->path;
		}
		
	}
?>
