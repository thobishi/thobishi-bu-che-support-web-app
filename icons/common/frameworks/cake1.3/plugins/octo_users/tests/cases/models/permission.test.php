<?php
/* User Test cases generated on: 2010-09-22 10:09:52 : 1285144972*/
App::import('Model', 'OctoUsers.Permission');

App::import('Lib', 'Templates.AppTestCase');
class PermissionTestCase extends AppTestCase {
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
		$this->Permission = AppMock::getTestModel('Permission');
		$fixture = new PermissionFixture();
		$this->record = array('Permission' => $fixture->records[0]);
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
		unset($this->Permission);
		ClassRegistry::flush();
	}
	
	public function testUserPermissions() {
		$expected = array(
			'app.Controller1' => array(
				'create' => 1,
				'read' => 1,
				'update' => 1,
				'delete' => 1,
				'admin' => 1
			),
			'app.Controller2' => array(
				'create' => 1,
				'read' => 1,
				'update' => 1,
				'delete' => 1,
				'admin' => 0
			)
		);
		$result = $this->Permission->userPermissions('user-1');
		
		$this->assertEqual($result, $expected);
	}
	
	public function testPermissionList() {
		$expected = array(
			'app.Controller1' => array(
				'id' => 'controller-1',
				'role-1' => array(
					'name' => 'Role 1',
					'create' => 1,
					'read' => 1,
					'update' => 1,
					'delete' => 1,
					'admin' => 1
				)
			),
			'app.Controller2' => array(
				'id' => 'controller-2',
				'role-1' => array(
					'name' => 'Role 1',
					'create' => 1,
					'read' => 1,
					'update' => 1,
					'delete' => 1,
					'admin' => 0
				)
			),
			'app.Controller3' => array(
				'id' => 'controller-3',
			)
		);
		
		$result = $this->Permission->find('permissionList');
		
		$this->assertEqual($result, $expected);		
	}
	
	public function testAvailablePermissions() {
		$expected = array(
			'create', 'read', 'update', 'delete', 'admin'
		);
		
		$this->assertEqual($this->Permission->availablePermissions(), $expected);
	}
	
	public function testSavePermissions() {
		$postData = array(
			'controller-2' => array(
				'role-1' => array(
					'admin' => 1
				)
			)
		);
		
		$expected = $this->Permission->find('permissionList');
		$expected['app.Controller2']['role-1']['admin'] = 1;
		
		$this->Permission->savePermissions($postData);
		
		$afterSave = $this->Permission->find('permissionList');
		
		$this->assertEqual($afterSave, $expected);
	}
}