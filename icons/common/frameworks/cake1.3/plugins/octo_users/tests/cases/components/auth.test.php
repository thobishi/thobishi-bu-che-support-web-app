<?php
App::import('Controller', 'Controller', false);
App::import('Component', 'OctoUsers.Auth');

/**
 * Post-Redirect-Get: Transfers POST Requests to GET Requests tests
 *
 * @package search
 * @subpackage search.tests.cases.components
 */
class User extends CakeTestModel {

/**
 * Name
 *
 * @var string
 */
	public $name = 'User';
	
    public function authLogin($type, $credentials = array()) {
        switch ($type) {
            case 'guest':
                return array();
            case 'credentials':
				$conditions = array(
                    'User.email_address' => $credentials['email_address'],
                    'User.password' => $credentials['password']
                );	
				
				break;			
            default:
                return null;
        }
		
		 return $this->find('first', compact('conditions'));
    }
}

/**
 * PostsTest Controller
 *
 * @package search
 * @subpackage search.tests.cases.components
 */
class UsersTestController extends Controller {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'UsersTest';

/**
 * Models to use
 *
 * @var array
 */
	public $uses = array('User');

/**
 * Components
 *
 * @var array
 */
	public $components = array('OctoUsers.Auth' => array(
		'sessionKey' => 'Test',
		'configureKey' => 'Test',
		'cookieKey' => 'Test',
	), 'Session');

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
	}

/**
 * Overwrite redirect
 *
 * @param string $url 
 * @param string $status 
 * @param string $exit 
 * @return void
 */
	public function redirect($url, $status = NULL, $exit = true) {
		$this->redirectUrl = $url;
	}
}

App::import('Lib', 'Templates.AppTestCase');
class AuthTestCase extends AppTestCase {
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
		$this->Controller = AppMock::getTestController('UsersTestController');
		$this->Controller->constructClasses();
		$this->Controller->params = array(
			'named' => array(),
			'pass' => array(),
			'url' => array()); 

		$this->Controller->Component->init($this->Controller);
		$this->Controller->Component->initialize($this->Controller);
		$this->Controller->beforeFilter();
		ClassRegistry::addObject('view', new View($this->Controller));	
		
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
		$this->Controller->Session->destroy();
		unset($this->Controller);
		ClassRegistry::flush();
	}
	
	public function testGuestUser() {
		$this->assertEqual($this->Controller->Auth->get(), array());
	}
	
	public function testLogin()  {
		$credentials['email_address'] = $this->record['User']['email_address'];
		$credentials['password'] = 'invalid';
		
		$this->assertEqual($this->Controller->Auth->login('credentials', $credentials), array());

		$credentials['email_address'] = $this->record['User']['email_address'];
		$credentials['password'] = $this->record['User']['password'];
		
		$this->assertEqual($this->Controller->Auth->login('credentials', $credentials), $this->record);
	
	}
}