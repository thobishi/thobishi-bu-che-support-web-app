<?php
/* User Test cases generated on: 2010-09-22 10:09:52 : 1285144972*/
App::import('Model', 'OctoUsers.Role');

App::import('Lib', 'Templates.AppTestCase');
class RoleTestCase extends AppTestCase {
/**
 * Autoload entrypoint for fixtures dependecy solver
 *
 * @var string
 * @access public
 */
	public $plugin = 'octo_users';

/**
 * Test to run for the test case (e.g array('testFind', 'testView'))
 * If this attribute is not empty only the tests from the list will be executed
 *
 * @var array
 * @access protected
 */
	protected $_testsToRun = array();

/**
 * Start Test callback
 *
 * @param string $method
 * @return void
 * @access public
 */
	public function startTest($method) {
		parent::startTest($method);
		$this->Role = AppMock::getTestModel('Role');
		$fixture = new RoleFixture();
		$this->record = array('Role' => $fixture->records[0]);
	}

/**
 * End Test callback
 *
 * @param string $method
 * @return void
 * @access public
 */
	public function endTest($method) {
		parent::endTest($method);
		unset($this->Role);
		ClassRegistry::flush();
	}	
}