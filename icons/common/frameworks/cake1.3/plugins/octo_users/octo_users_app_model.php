<?php

class OctoUsersAppModel extends AppModel {
	protected $_plugin = 'OctoUsers';
	
	public function __construct($id = false, $table = null, $ds = null) {
		foreach(get_class_methods($this) as $method) {
			if (strpos($method, '_find') !== false) {
				$method = Inflector::variable(str_replace('_find', '', $method));

				$finders[$method] = true;
			}
		}
		$this->_findMethods = $finders + array('all' => true);
		parent::__construct($id, $table, $ds);
	}
}