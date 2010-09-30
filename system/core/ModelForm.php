<?php
	class ModelForm
	{
		var $model_name;
		var $schema;
		var $template;
		
		function __construct($model_name)
		{
			$this->model_name = $model_name;
			$this->schema = new Schema($model_name);
			$this->template = Template::getInstance();
		}
		
		function field($field_name)
		{
			$field_schema = $this->schema->$field_name;
			$field_id = $this->model_name . '-' . $field_name;
			
			$field = XML::element('div');
			$field->set('class', 'field');
			$field->set('id', "{$field_id}-container");
			
			$label = XML::element('label', $field);
			$label->set('text', i18n::get($field_schema->label ? $field_schema->label : $field_schema->name));
			$label->set('for', $field_id);
			
			switch($field_schema->type)
			{
				case 'varchar':
					$element = $this->input($field_schema, $field_id); break;
				case 'text':
					$element = $this->textarea($field_schema, $field_id); break;
				default:
					$element = null;
			}
			
			if ($element) $field->adopt($element);
			return $field;
		}
		
		function input($field_schema, $field_id)
		{
			$input = XML::element('input');
			$input->set('name', $field_schema->name);
			$input->set('id', $field_id);
			$input->set('class', 'text');
			if ($field_schema->maxlength)
				$input->set('maxlength', $field_schema->maxlength);
			
			return $input;
		}
		
		function textarea($field_schema, $field_id)
		{
			$textarea = XML::element('textarea');
			$textarea->set('name', $field_schema->name);
			$textarea->set('id', $field_id);
			
			return $textarea;
		}
		
	}
?>
