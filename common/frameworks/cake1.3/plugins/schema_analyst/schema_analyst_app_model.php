<?php
/**
 * SchemaAnalystAppModel
 *
 * Part of the SchemaAnalyst plugin.
 *
 * @author Frank de Graaf (Phally)
 * @license MIT license
 * @link http://github.com/phally
 */
class SchemaAnalystAppModel extends AppModel {

/**
 * The models in this plugin don't use tables.
 * @var mixed
 * @access public
 */
	public $useTable = false;

/**
 * No find types should be overridden by default.
 * @var array
 * @access protected
 */
	protected $methods = array();

/**
 * SchemaAnalystAppModel::find()
 *
 * Overrides Model::find().
 *
 * If the find type is found in the self::$methods 
 * array, a custom method will be called instead of
 * CakePHP's find. Parameter docblock copied from
 * CakePHP's source.
 *
 * @param	array	$conditions 	SQL conditions array, or type of find operation (all / first / count /
 * 									neighbors / list / threaded).
 * @param 	mixed 	$fields 		Either a single string of a field name, or an array of field names, or
 * 									options for matching
 * @param 	string 	$order 			SQL ORDER BY conditions (e.g. "price DESC" or "name ASC")
 * @param	integer	$recursive		The number of levels deep to fetch associated records
 * @return 	array 					Array of records
 * @access 	public
 */
	public function find($conditions = null, $fields = array(), $order = null, $recursive = null) {
		if (is_string($conditions) && in_array($conditions, $this->methods)) {
			return $this->{'find' . Inflector::camelize($conditions)}($conditions, $fields, $order, $recursive);
		}
		return parent::find($conditions, $fields, $order, $recursive);
	}
}
?>