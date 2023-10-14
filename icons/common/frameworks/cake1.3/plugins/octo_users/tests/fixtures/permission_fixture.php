<?php
/* Permission Fixture generated on: 2011-01-25 13:28:23 : 1295954903 */
class PermissionFixture extends CakeTestFixture {
	public $name = 'Permission';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'controller_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'role_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'_create' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'_read' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'_update' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'_delete' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'_admin' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'controller_id' => array('column' => array('controller_id', 'role_id'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	public $records = array(
		array(
			'id' => 'permission-1',
			'controller_id' => 'controller-1',
			'role_id' => 'role-1',
			'_create' => 1,
			'_read' => 1,
			'_update' => 1,
			'_delete' => 1,
			'_admin' => 1
		),
		array(
			'id' => 'permission-2',
			'controller_id' => 'controller-2',
			'role_id' => 'role-1',
			'_create' => 1,
			'_read' => 1,
			'_update' => 1,
			'_delete' => 1,
			'_admin' => 0
		),		
	);
}
?>