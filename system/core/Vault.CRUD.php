<?php
	//version 0.1
	
	require_once('controllers/controller.HTML.php');
	require_once('controllers/controller.Model.php');
	
	class CRUD {
		
		var $model_name;
		private $listFields = array();
		private $listActions = array();
		private $listFilter = array();
		private $formFields;
		private $dict = array(
			'edit'=>'edit',
			'delete'=>'delete',
			'add'=>'Add new'
		);
		
		function __construct($model_name, $list_fields=null) {
			$this->model_name = $model_name;
		}
		
		function setListFields($listFields) {
			$this->listFields = $listFields;
		}
		
		function setListFilter($where) {
			$this->listFilter = $where;
		}
		
		function setFormFields($formFields) {
			$this->formFields = $formFields;
		}
		
		function setDictTerm($key, $value) {
			$this->dict[$key] = $value;
		}
		
		function setListAction($link, $text, $img, $vars) {
			$this->listActions[] = array('link'=>$link, 'text'=>$text, 'img'=>$img, 'vars'=>$vars);
		}
		
		function generate() {
			switch($_GET['action']) {
				case 'detail':
					break;
				case 'delete':
					$model = Model::get($this->model_name, array('id'=>$_GET['id']));
					$model->delete();
					break;
				case 'edit':
				case 'add':
					require_once('controllers/controller.CRUD_ModelForm.php');
					$form = new ModelForm($this->model_name);
					$form->output($this->formFields);
					break;
				case 'list':
				default:
					require_once('controllers/controller.CRUD_ModelList.php');
					$list = new ModelList($this->model_name, $this->listFields);
					$list->setFilter($this->listFilter);
					$list->setAction(HTML::url(null, array('action'=>'edit')), $this->dict['edit'], null, 'id');
					$list->setAction(HTML::url(null, array('action'=>'delete')), $this->dict['delete'], null, 'id');
					
					foreach($this->listActions as $action) {
						$list->setAction($action['link'], $action['text'], $action['img'], $action['vars']);
					}
					$list->output();
					
					HTML::url(null, array('action'=>'add'))->anchor($this->dict['add'], array('class'=>'CRUDList_add'));
			}
		}
		
	}
?>