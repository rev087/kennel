<?php
	class Banners_controller extends Cms_controller
	{
		public function index($page_number=1)
		{
			if(!is_numeric($page_number)) $page_number = 1;
			$this->template->head = 
				html::js('Swiff.Uploader.js', 'cms_banners.js', 'tabs.js', 'progressbar.js', 'lightbox.js') .
				html::css('cms_image_pick.css');
				
			$this->template->content = new View('cms_banners');
			
			$positions = ORM::retrieveAll('banner_position');
			$banners = array();
			foreach ($positions as $position)
			{
				$c = new Criteria('banner');
				$c->add('position_id', $position->id);
				$banners[$position->id] = ORM::retrieve($c);
			}
			
			$this->template->positions = $positions;
			$this->template->banners = $banners;
			$this->template->render();
		}
		
		public function save() {
			if (!Input::post('id'))
				$banner = new Model('banner');
			else
				$banner = ORM::retrieveByPrimaryKey('banner', Input::post('id'));
			
			$banner->position_id = Input::post('position_id');
			$banner->title = Input::post('title');
			$banner->description = Input::post('description');
			$banner->image_id = Input::post('image_id');
			$banner->status = Input::post('status');
			$banner->publish_date = Input::post('publish_date');
			$banner->unpublish_date = Input::post('unpublish_date');
			$banner->url = Input::post('url');
			
			$banner->save();
			
			$view = new Json_response();
			$view->message('ok', "Banner <strong>{$banner->title}</strong> saved.");
			$view->redirect('', 1000);
			$view->render();
		}
		
		public function delete($id) {
			$banner = ORM::retrieveByPrimaryKey('banner', $id);
			
			$view = new Json_response();
			$view->message('ok', "Banner <strong>{$banner->title}</strong> deleted.");
			$view->redirect('', 1000);
			
			$banner->delete();
			$view->render();
		}
		
		public function edit($position_id, $id=null)
		{
			$view = new View('cms_modal_banners_edit');
			$view->position_id = $position_id;
			if ($id)
				$view->banner = ORM::retrieveByPrimaryKey('banner', $id);
			else
				$view->banner = new Model('banner');
			$view->render();
		}
		
		public function pick_image()
		{
			$a = XML::element('a', null, array('href'=>Input::post('path'), 'class'=>'lightbox'));
			$img = XML::element('img', $a, array('src'=>Input::post('thumb')));
			
			$view = new Json_response();
			$view->update('.image-selection', $a->output());
			$view->update('#image_id', Input::post('id'));
			$view->render();
		}
		
		public function submit_image()
		{
			$image = Images_controller::submit();
			$a = XML::element('a', null, array('href'=>$image->path(), 'class'=>'lightbox'));
			$img = XML::element('img', $a, array('src'=>$image->thumb(150, 150)));
			
			$view = new Json_response();
			$view->update('.image-selection', $a->output());
			$view->update('#image_id', $image->id);
			$view->render();
		}
		
	}
?>
