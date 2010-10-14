<?php
	class ModelForm
	{
		var $model_name;
		var $schema;
		var $template;
		var $method;
		var $action;
		var $errors = array();
		var $values = array();
		
		function __construct($model_name, $method='post', $action=null)
		{
			$this->model_name = $model_name;
			$this->method = $method;
			$this->action = $action;
			$this->schema = new Schema($model_name);
			$this->template = Template::getInstance();
		}
		
		function field($field_name, $custom_label=null, $custom_attributes=null)
		{
			$field_schema = $this->schema->$field_name;
			$field_id = $this->model_name . '_' . $field_name;
			
			// Field value
			$field_value = count($this->values) && array_key_exists($field_id, $this->values) ? $this->values[$field_id] : null;
						
			// Field error
			$field_error = array_key_exists($field_id, $this->errors) ? $this->errors[$field_id] : null;
			
			$field = XML::element('div');
			$field->set('class', 'field');
			$field->set('id', "{$field_id}_container");
			if ($field_error) $field->set('class', 'field invalid');
			
			$label = XML::element('label', $field);
			$label->set('text', i18n::get(pick($custom_label, $field_schema->label, $field_schema->name)));
			$label->set('for', $field_id);
			if ($field_error) $label->set('class', 'invalid');
			
			switch($field_schema->type)
			{
				case 'varchar':
					$element = $this->input($field_schema, $field_id, $custom_attributes, $field_value, $field_error); break;
				case 'text':
					$element = $this->textarea($field_schema, $field_id, $custom_attributes, $field_value, $field_error); break;
				default:
					$element = null;
			}
			
			
			if ($element) $field->adopt($element);
			
			if ($field_error)
			{
				$p = XML::element('p', $field);
				$p->set('class', 'error');
				$p->set('text', $field_error);
			}
			
			return $field;
		}
		
		function input($field_schema, $field_id, $attributes=null, $field_value=null, $field_error=null)
		{
			$input = XML::element('input');
			$input->set('name', $field_id);
			$input->set('id', $field_id);
			$input->set('class', 'text');
			
			// Custom attributes
			if (is_array($attributes))
				foreach ($attributes as $att=>$value)
					$input->set($att, $value);
				
			// Value
			if ($field_value)
				$input->set('value', $field_value);
			
			// Invalid
			if ($field_error)
				$input->set('class', 'text invalid');
			
			// Max length
			if ($field_schema->maxlength)
				$input->set('maxlength', $field_schema->maxlength);
			
			return $input;
		}
		
		function textarea($field_schema, $field_id, $attributes=null, $field_value=null, $field_error=null)
		{
			$textarea = XML::element('textarea');
			$textarea->set('name', $field_id);
			$textarea->set('id', $field_id);
			
			// Custom attributes
			if (is_array($attributes))
				foreach ($attributes as $att=>$value)
					$textarea->set($att, $value);
			
			// Value
			if ($field_value)
				$textarea->set('text', $field_value);
			else
				$textarea->set('text', '');
			
			// Invalid
			if ($field_error)
				$textarea->set('class', 'invalid');
			
			return $textarea;
		}
		
		function validate($values=null)
		{
			$values = pick($values, $this->populate());
			
			foreach ($this->schema as $field)
			{
				$input_id = $this->model_name . '_' . $field->name;
				$input_value = $values[$input_id];
				
				// Required error (not primary key, not foreign key and null or zero length string)
				if (!$field->primaryKey && !$field->foreignKey && $field->required && ($input_value === null || strlen($input_value) === 0))
				{
					$this->errors[$input_id] = i18n::get('This field is required.');
					continue;
				}
				elseif (!$field->required && ($input_value === null || strlen($input_value) === 0))
				{
					continue;
				}
				
				// Validate via regex, validation template or field type
				$regex = $field->regex ? 'regex' : null;
				$validation_type = pick($regex, $field->template, $field->type);
				switch ($validation_type)
				{
					// Regex matching
					case 'regex':
						// Regex error
						if (!preg_match($field->regex, $input_value))
						{
							$this->errors[$input_id] = i18n::get('Please type a valid value for this field.'); continue;
						}
						break;
					
					// Numeric template
					case 'numeric':
					case 'int':
					case 'tinyint':
						// Non-numeric error
						if ($input_value !== null && !is_numeric($input_value))
						{
							$this->errors[$input_id] = i18n::get('This field accepts numbers only.'); continue;
						}
						
						break;
					
					// E-mail template
					case 'email':
						// E-mail error
						$email_regex = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9][a-zA-Z0-9._-]+.[a-zA-Z0-9._-]{2,}$/';
						if (!preg_match($email_regex, $input_value))
						{
							$this->errors[$input_id] = i18n::get('Please type a valid e-mail address.'); continue;
						}
						
						break;
					
					// Currency template
					case 'currency':
					case 'decimal':
						// Currency error
						$currency_regex = '/^[0-9,.]+$/';
						if ($input_value !== null && !preg_match($currency_regex, $input_value))
						{
							$this->errors[$input_id] = i18n::get('Please type a valid amount.'); continue;
						}
						break;
					
					// Field type: varchar, text
					case 'varchar':
					case 'text':
						$length = strlen($input_value);
						
						// Minlength error
						if ($field->minlength && $length < $field->minlength)
						{
							$this->errors[$input_id] = i18n::get('This field requires at least %0 characters.', array($field->minlength));
							continue;
						}
						
						// Maxlength error
						if ($field->maxlength && $length > $field->maxlength)
						{
							$this->errors[$input_id] = i18n::get('This field\'s maximun length is %0 characters.', array($field->maxlength));
						}
						
						break;
					
					case 'datetime':
						break;
					
					default:
						print '<p><strong>Unhandled field type "'. $field->type .'"</strong></p>';
				}
			}
			
			if (count($this->errors) === 0) return true;
			else return false;
		}
		
		function populate($values=null)
		{
			if ($this->method == 'post')
				$values = pick($values, Input::post());
			elseif ($this->method == 'get')
				$values = pick($values, Input::get());
			
			foreach ($this->schema as $field)
			{
				$input_id = $this->model_name . '_' . $field->name;
				
				if (array_key_exists($input_id, $values)) $this->values[$input_id] = $values[$input_id];
				else $this->values[$input_id] = null;
			}
			
			return $this->values;
		}
		
		function dumpErrors($return=false)
		{
			$dump = '<table>';
			foreach ($this->errors as $field=>$message)
			{
				$dump .= '<tr>';
				$dump .= '<th style="font-weight: bold;">' . $field . '</th>';
				$dump .= '<td>' . $message . '</td>';
				$dump .= '<td>' . var_export($this->values[$field], true) . '</td>';
				$dump .= '</tr>';
			}
			$dump .= '</table>';
			
			if ($return) return $dump;
			else print $dump;
		}
		
	}
?>
