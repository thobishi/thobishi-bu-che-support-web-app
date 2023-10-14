<?php
/* User Test cases generated on: 2010-09-22 10:09:52 : 1285144972*/
App::import('Model', 'OctoUsers.User');
App::import('Component', 'OctoUsers.Auth');

App::import('Lib', 'Templates.AppTestCase');
class UserTestCase extends AppTestCase {
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
		$this->User = AppMock::getTestModel('User');
		$fixture = new UserFixture();
		$this->record = array('User' => $fixture->records[0]);
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
		unset($this->User);
		ClassRegistry::flush();
	}
	
	public function testRegister() {
		//Test if no data submitted
		$this->assertFalse($this->User->register(null));
		
		//Check validation exception
		try {
			$data = $this->record;
			unset($data['User']['id']);
			$data['User']['email_address'] = '';
		
			$this->User->register($data);
			$this->fail('No exception');
		}
		catch(OutOfBoundsException $e) {
			$this->pass('Correct exception thrown');
		}
		
		//Duplicate email test
		try {
			$data = $this->record;
			unset($data['User']['id']);
		
			$this->User->register($data);
			$this->fail('No exception');
		}
		catch(OutOfBoundsException $e) {
			$this->pass('Correct exception thrown');
		}
		
		//Successful registration
		$data = $this->record;
		unset($data['User']['id']);
		$data['User']['email_address'] = 'test@test.com';
		$data['User']['clean_password'] = '123456';
		$data['User']['confirm_password'] = '123456';
		
		$this->assertTrue($this->User->register($data));
		
		$register = $this->User->findById($this->User->id);
		$this->assertNotEqual($register['User']['email_token'], null);
	}
	
	public function testValidateToken() {
		$this->__addUser('user-2');
		$this->__addUser('user-3', 'test1@test.com', 'expired-token', true);
		
		//Test empty token
		$this->assertEqual($this->User->validateToken(null), false);
		$this->assertEqual($this->User->validateToken(''), false);

		//Test invalid token
		$this->assertEqual($this->User->validateToken('invalid-token'), false);

		//Test expired token
		$this->assertEqual($this->User->validateToken('expired-token'), false);

		//Test valid token
		$this->assertEqual($this->User->validateToken('valid-token'), 'user-2');
	}
	
	public function testVerifyAccount() {
		$this->__addUser('user-2');
		
		try {
			$this->User->verifyAccount('invalid-token');
			$this->fail('No exception');
		} catch(OutOfRangeException $e) {
			$this->pass('Correct exception');
		}
		
		$this->User->verifyAccount('valid-token');
		$item = $this->User->read(null, 'user-2');
		
		$this->assertNull($item['User']['email_token']);
		$this->assertTrue($item['User']['email_authenticated']);
	}
	
	public function testCompareField() {
		$this->User->data['User']['clean_password'] = 'password';
		
		$this->assertFalse($this->User->compareField(array('confirm_password' => 'wrong'), 'clean_password'));
	
		$this->User->data['User']['clean_password'] = 'password';
		
		$this->assertTrue($this->User->compareField(array('confirm_password' => 'password'), 'clean_password'));
	}
	
	public function testResendVerification() {
		try {
			$this->User->resendVerification(array());
			$this->pass('No exception');
		}
		catch(OutOfBoundsException $e) {
			$this->fail('Exception thrown');
		}	
		
		try {
			$postData = array(
				'User' => array());
			$this->User->resendVerification($postData);
			$this->fail('No exception');
		}
		catch(OutOfBoundsException $e) {
			$this->pass('Exception thrown');
			$this->assertEqual($this->User->validationErrors, array('email_address' => 'Please enter your email address.'));
		}
		

		try {
			$postData = array(
				'User' => array(
					'email_address' => 'doesnotexist!'));
			$this->User->resendVerification($postData);
			$this->fail('No exception');
		}
		catch(OutOfBoundsException $e) {
			$this->pass('Exception thrown');
			$this->assertEqual($this->User->validationErrors, array('email_address' => 'The email address does not exist in the system.'));
		}

		try {
			$postData = array(
				'User' => array(
					'email_address' => 'valid@email.com'));
			$this->User->resendVerification($postData);
			$this->fail('No exception');
		}
		catch(OutOfBoundsException $e) {
			$this->pass('Exception thrown');
			$this->assertEqual($this->User->validationErrors, array('email_address' => 'Your account is already authenticated.'));			
		}	
		
		$this->__addUser('user-2');
		
		$postData = array(
			'User' => array(
				'email_address' => 'test@test.com'));
		$result = $this->User->resendVerification($postData);
		
		$this->assertTrue(is_array($result));		
	}
	
	public function testGeneratePasswordToken() {
		try {
			$postData = array(
				'User' => array(
					'email_address' => ''));
			$this->User->generatePasswordToken($postData);
			$this->fail('No exception');
		}
		catch (OutOfBoundsException $e) {
			$this->pass('Correct exception');
		}
		
		try {
			$postData = array(
				'User' => array(
					'email_address' => 'doesnotexist!'));			
			$this->User->generatePasswordToken($postData);
			$this->fail('No exception');
		}
		catch (OutOfBoundsException $e) {
			$this->pass('Correct exception');
		}
		
		$postData = array(
			'User' => array(
				'email_address' => 'valid@email.com'));
		$result = $this->User->generatePasswordToken($postData);
		$this->assertTrue(!empty($result));
		
		$user = $this->User->findById('user-1');
		$this->assertNotEqual($user['User']['password_token'], null);		
	}
	
	public function testChangePassword() {
		try {
			$this->User->changePassword(array(), 'invalid-token');
			$this->fail('No exception');
		}
		catch (OutOfRangeException $e) {
			$this->pass('Correct exception');
		}
		
		try {
			$this->__addUser('user-2', 'expired@test.com', 'expired-token', true);
			$this->User->changePassword(array(), 'expired-token');
			$this->fail('No exception');
		}
		catch (OutOfRangeException $e) {
			$this->pass('Correct exception');
		}			
		
		$this->__addUser('user-3');
		
		$postData = array(
			'User' => array(
				'clean_password' => 'short',
				'confirm_password' => 'different'
			)
		);
		try {
			$this->User->changePassword($postData, 'valid-token');
			$this->fail('No exception');
		}
		catch(OutOfBoundsException $e) {
			$this->pass('Correct exception');
		}
		
		$postData = array(
			'User' => array(
				'clean_password' => 'password',
				'confirm_password' => 'password'
			)
		);
		App::import('Core', 'Security');
		$securePassword = Security::hash('password', null, true);
		
		$result = $this->User->changePassword($postData, 'valid-token');
		$item = $this->User->read(null, 'user-3');
		
		$this->assertTrue($result);
		$this->assertNull($item['User']['password_token']);
		$this->assertEqual($item['User']['password'], $securePassword);
	}
	
	public function testAdd() {
		$this->assertEqual($this->User->add(), false);
		
		//Check validation exception
		try {
			$data = $this->record;
			unset($data['User']['id']);
			$data['User']['email_address'] = '';
		
			$this->User->add($data);
			$this->fail('No exception');
		}
		catch(OutOfBoundsException $e) {
			$this->pass('Correct exception thrown');
		}
		
		//Successful registration
		$data = $this->record;
		unset($data['User']['id']);
		$data['User']['email_address'] = 'test@test.com';
		$data['User']['clean_password'] = '123456';
		$data['User']['confirm_password'] = '123456';
		
		$this->assertTrue($this->User->add($data));
	}
	
	private function __addUser($id, $email = 'test@test.com', $token = 'valid-token', $expired = false) {
		$data = $this->record;
		$data['User']['id'] = $id;
		$data['User']['email_address'] = $email;
		$data['User']['email_authenticated'] = 0;
		$data['User']['email_token'] = $token;
		$data['User']['password_token'] = $token;
		if($expired) {
			$data['User']['token_expiration'] = date('Y-m-d H:i:s', strtotime('-1 hours'));			
		}
		else {
			$data['User']['token_expiration'] = date('Y-m-d H:i:s', strtotime('+1 hours'));
		}
		
		$this->User->create();
		$this->User->save($data);		
	}
}
?>