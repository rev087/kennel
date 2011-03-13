<?php
  class banners
  {
  	static function render($position, $limit=null)
  	{
  		$html = '';
  		$banners = self::get($position, $limit);
  		if (count($banners))
  		{
				foreach ($banners as $banner) {
					if ($banner->url)
					{
						$a = XML::element('a', null, array('class'=>'banner_link', 'href'=>url($banner->url)));
						$img = XML::element('img', $a, array('class'=>'banner', 'src'=>$banner->image->path(), 'alt'=>$banner->title));
						$html .= $a;
					}
					else 
					{
						$img = XML::element('img', null, array('class'=>'banner', 'src'=>$banner->image->path(), 'alt'=>$banner->title));
						$html .= $img;
					}
				}
			}
  		return $html;
  	}
  	
    static function get($position, $limit=null)
    {
      $html = '';
      
			$c = new Criteria('banner');
      $cp = new Criteria('banner_position');
      
      # Get by either position.id or position.keyword
      if (is_numeric($position))
      	$cp->add('id', $position);
      else
      	$cp->add('keyword', $position);
      	
      $pos = ORM::retrieveFirst($cp);
      if (!$pos) return null;
      
      $c->add('position_id', $pos->id);
			
			$c->add('status', 1);
			if($limit)
				$c->setLimit($limit);
			
			# Banner rotation
			if ($pos->rotation == 0)
				$c->addAscendingOrder('id');
			elseif ($pos->rotation == 1)
				$c->addRandomOrder();
			
			$banners = ORM::retrieve($c);
			
			return $banners;
			
			/*
      foreach ($banners as $banner)
      {
				$img = XML::element('img');
				$img->src = $banner->image->path();
				$img->alt = $banner->title;
				$img->set('class', 'banner_image');
				
				if ($banner->url)
				{
					$a = XML::element('a');
					$a->href = $banner->url;
					$a->set('class', 'banner_link');
					$a->adopt($img);
					$html .= $a;
				}
				else
				{
					$html .= $img;
				}
      }
      
      return $html;
      */
    }
    
  }
?>
