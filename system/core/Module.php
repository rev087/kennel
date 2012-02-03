<?php
	/*
		Module meta data handling
	*/
	class Module {
		
		var $id;
		var $dependencies = array(); // Modules it depends upon
		var $doc; // XMLDocument
		var $settings; // Module node
		private static $INSTANCES = array();
		
		function __construct($mod_id)
		{
			$this->read($mod_id);
		}
		
		private function read($mod_id)
		{
			$this->id = $mod_id;
			$this->doc = new DOMDocument('1.0');
			$this->doc->formatOutput = true;
			$this->doc->preserveWhiteSpace = false;
			$this->doc->load(Kennel::$ROOT_PATH . '/modulesettings.xml');
			$root = $this->doc->getElementsByTagName('modules')->item(0);
			
			foreach ($root->childNodes as $node)
				if ($node->nodeType == 1 && $node->getAttribute('id') == $this->id)
					$this->settings = $node;			
			
			// If the module is not listed in the settings file, create the node
			if (!$this->settings && Module::checkPermission())
			{
				$this->settings = $this->doc->createElement('module');
				$this->settings->setAttribute('id', $this->id);
				$root->appendChild($this->settings);
				$this->save();
			}
		}
		
		private function save()
		{
			if (Module::checkPermission())
				$this->doc->save(Kennel::$ROOT_PATH . '/modulesettings.xml');
		}
		
		static function getInstance($mod_id)
		{
			if (isset(self::$INSTANCES[$mod_id]))
				return self::$INSTANCES[$mod_id];
			else
				return new Module($mod_id);
		}
		
		/*	This method can be called statically or instanced, like this:
		 *	$module->get(string $variable);
		 *	Module::get(string $mod_id, string $variable);
		 */
		function get()
		{
			$arguments = func_get_args();
			if (count($arguments) === 1)
			{
				// Instanced call
				if ($this->settings)
  				foreach ($this->settings->childNodes as $node)
  					if ($node->nodeType == 1 && $node->getAttribute('name') == $arguments[0])
  						return $node->getAttribute('value');
				return null;
			}
			elseif (count($arguments) === 2)
			{
				// Static call
				$mod = self::getInstance($arguments[0]);
				return $mod->get($arguments[1]);
			}
		}
		
		function set($variable, $value)
		{
			foreach ($this->settings->childNodes as $node)
				if ($node->nodeType == 1 && $node->getAttribute('name') == $variable)
				{
					$node->setAttribute('value', $value);
					$this->save();
					return $value;
				}
			// Create the node if the variable was not found
			$setting = $this->doc->createElement('setting');
			$setting->setAttribute('name', $variable);
			$setting->setAttribute('value', $value);
			$this->settings->appendChild($setting);
			$this->save();
		}
		
		function checkPermission()
		{
			return is_writable(Kennel::$ROOT_PATH . '/modulesettings.xml');
		}
		
	}
?>
