<?php
/* AvailableController Fixture generated on: 2011-01-25 14:01:51 : 1295957571 */
class AvailableControllerFixture extends CakeTestFixture {
/**
 * Name
 *
 * @var string
 * @access public
 */
	public $name = 'AvailableController';

/**
 * Fields
 *
 * @var array
 * @access public
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'plugin' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'controller' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 * @access public
 */
	public $records = array(
		array(
			'id' => 'controller-1',
			'plugin' => 'app',
			'controller' => 'Controller1'
		),
		array(
			'id' => 'controller-2',
			'plugin' => 'app',
			'controller' => 'Controller2'
		),
		array(
			'id' => 'controller-3',
			'plugin' => 'app',
			'controller' => 'Controller3'
		),		
	);

}
?>