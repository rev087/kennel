<?php
	class Banner_settings_controller extends Cms_controller
	{
		
		public function index($page_number = null)
		{
			$c = new Criteria('banner_position');
			$c->addAscendingOrder('keyword');
			
			$pag = new Pagination($c, 20);
			$this->template->positions = $pag->getPage($page_number);
			$this->template->pagination = $pag->getLinks(url('cms/banner_settings/{page}'));
			
			$this->template->content = new View('cms_banner_settings');
			$this->template->render();
		}
		
		public function add_position()
		{
			$this->edit_position();
		}
		
		public function edit_position($id=null)
		{
			if ($id && !is_numeric($id)) $this->error('Invalid Request');
			
			if ($id)
				$pos = ORM::retrieveByPrimaryKey('banner_position', $id);
			else
				$pos = new Model('banner_position');
			
			$view = new View('cms_banner_position_edit');
			$view->pos = $pos;
			$view->render();
		}
		
		public function save_position()
		{
			if (Input::post('id') && !is_numeric(Input::post('id')))
				$this->error('Invalid Request');
			
			if (Input::post('id'))
				$pos = ORM::retrieveByPrimaryKey('banner_position', Input::post('id'));
			else
				$pos = new Model('banner_position');
			
			$pos->title = Input::post('title');
			$pos->keyword = permalink::generate(Input::post('title'));
			$pos->width = Input::post('width');
			$pos->height = Input::post('height');
			$pos->rotation = Input::post('rotation');
			$pos->save();
			
			$view = new Json_response();
			$view->message('ok', "Banner position <strong>{$pos->title}</strong> saved");
			$view->redirect('', 1000);
			$view->render();
		}
		
		public function delete_position($id=null)
		{
			if (!$id || !is_numeric($id)) $this->error('Invalid Request');
			
			$pos = ORM::retrieveByPrimaryKey('banner_position', $id);
			
			if (!$pos) return $this->index();
			
			$view = new Json_response();
			$view->message('ok', "Banner position <strong>{$pos->title}</strong> deleted");
			$view->redirect('', 1000);
			
			$pos->delete();
			$view->render();
		}
		
	}
?>
