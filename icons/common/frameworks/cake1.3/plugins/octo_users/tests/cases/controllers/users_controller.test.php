<?php
App::import('Controller', 'OctoUsers.Users');

App::import('Component', array('Email'));

Mock::generate('EmailComponent', 'UsersControllerTestEmailComponent');

App::import('Lib', 'Templates.AppTestCase');
class UsersControllerTestCase extends AppTestCase {
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
		$this->Users = AppMock::getTestController('UsersController');
		$this->Users->constructClasses();

		$this->Users->Email = new UsersControllerTestEmailComponent();
		$this->Users->Email->enabled = true;
		
		$this->Users->params = array(
			'named' => array(),
			'pass' => array(),
			'url' => array());
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
		unset($this->Users);
		ClassRegistry::flush();
	}
	
/**
 * Convenience method to assert Flash messages
 *
 * @return void
 * @access public
 */
	public function assertFlash($message) {
		$flash = $this->Users->Session->read('Message.flash');
		$this->assertEqual($flash['message'], $message);
		$this->Users->Session->delete('Message.flash');
	}
	
	public function assertErrorFlash($message) {
		$flash = $this->Users->Session->read('Message.error');
		$this->assertEqual($flash['message'], $message);
		$this->Users->Session->delete('Message.error');
	}
	
	public function testRegister() {		
		$this->Users->data = $this->record;
		
		unset($this->Users->data['User']['id']);
		$this->Users->data['User']['email_address'] = 'test@test.com';
		$this->Users->data['User']['clean_passwd'] = 'password';
		$this->Users->data['User']['tmppasswd'] = 'password';
		
		$this->Users->expectRedirect(array('action' => 'login'));
		$this->Users->Email->expectOnce('send');

		$this->Users->Email->setReturnValue('send', true);			
		$this->Users->register();
		$this->assertEqual($this->Users->Email->template, 'OctoUsers.registration');
		$this->assertFlash('Your account has been successfully created. Please check your email for details on how to activate your account.');
		$this->Users->expectExactRedirectCount();
	}
	
	public function testVerifyAccount() {
		$data = $this->record;
		$data['User']['id'] = 'user-2';
		$data['User']['email_address'] = 'test@test.com';
		$data['User']['email_authenticated'] = 0;
		$data['User']['email_token'] = 'valid-token';
		$data['User']['token_expiration'] = date('Y-m-d H:i:s', strtotime('+1 hours'));
		
		$this->Users->User->create();
		$this->Users->User->save($data);
		
		$this->Users->expectRedirect(array('action' => 'login'));
		$this->Users->verify_account('invalid-token');
		$this->assertErrorFlash('The validation key does not exist or is outdated.');

		$this->Users->expectRedirect(array('action' => 'login'));
		$this->Users->verify_account('valid-token');
		$this->assertFlash('Your account has been activated, you may now login.');

		$this->Users->expectExactRedirectCount();
	}
	
	public function testResend() {
		$data = $this->record;
		$data['User']['id'] = 'user-2';
		$data['User']['email_address'] = 'test@test.com';
		$data['User']['email_authenticated'] = 0;
		$data['User']['email_token'] = 'valid-token';
		$data['User']['token_expiration'] = date('Y-m-d H:i:s', strtotime('+1 hours'));
		
		$this->Users->User->create();
		$this->Users->User->save($data);
		
		$this->Users->data = array('User' => array('email_address' => 'invalid@test.com'));
		$this->Users->resend();
		$this->assertErrorFlash('Please fix the validation errors highlighted below.');
		
		$this->Users->data = array('User' => array('email_address' => 'test@test.com'));
		$this->Users->expectRedirect(array('action' => 'login'));
		$this->Users->Email->expectOnce('send');
		$this->Users->Email->setReturnValue('send', true);	
		
		$this->Users->resend();
		
		$this->assertEqual($this->Users->Email->template, 'OctoUsers.activation');
		$this->assertFlash('The activation email has been resent to your email address.');

		$this->Users->expectExactRedirectCount();
	}
	
	public function testRequestPassword() {
		$data = $this->record;
		$data['User']['id'] = 'user-2';
		$data['User']['email_address'] = 'test@test.com';
		
		$this->Users->User->create();
		$this->Users->User->save($data);		

		$this->Users->data = array('User' => array('email_address' => 'invalid@test.com'));
		$this->Users->request_password();
		$this->assertErrorFlash('Please fix the validation errors highlighted below.');
		
		$this->Users->data = array('User' => array('email_address' => 'valid@email.com'));
		$this->Users->expectRedirect(array('action' => 'login'));
		$this->Users->Email->expectOnce('send');
		$this->Users->Email->setReturnValue('send', true);	
		
		$this->Users->request_password();
		
		$this->assertEqual($this->Users->Email->template, 'OctoUsers.password');
		$this->assertFlash('An email with details on how to reset your password has been sent to you.');

		$this->Users->expectExactRedirectCount();		
	}
	
	public function testChangePassword() {
		$data = $this->record;
		$data['User']['id'] = 'user-2';
		$data['User']['email_address'] = 'test@test.com';
		$data['User']['email_authenticated'] = 0;
		$data['User']['password_token'] = 'valid-token';
		$data['User']['token_expiration'] = date('Y-m-d H:i:s', strtotime('+1 hours'));
		
		$this->Users->User->create();
		$this->Users->User->save($data);		
		
		$this->Users->data = array('User' => array('clean_password' => 'short', 'confirm_password' => 'different'));
		$this->Users->change_password('valid-token');
		$this->assertErrorFlash('Please fix the validation errors highlighted below.');
		
		$this->Users->data = array('User' => array('clean_password' => 'password', 'confirm_password' => 'password'));
		$this->Users->expectRedirect(array('action' => 'login'));
		
		$this->Users->change_password('valid-token');
		
		$this->assertFlash('Thank you. Your password has been changed.');

		$this->Users->expectExactRedirectCount();		
	}
}