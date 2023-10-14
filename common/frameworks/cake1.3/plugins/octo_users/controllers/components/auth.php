<?php
/**
 * Based on the Auth component by Felixge
 */
class AuthComponent extends Object{
	public $components = array(
		'Session',
		'Cookie',
		'RequestHandler',
	);

	public $settings = array();
	
	private $default = array(
		'plugin' => 'OctoUsers',
		'model' => 'User',
		'configureKey' => null,
		'sessionKey' => null,
		'cookieKey' => null,
		'allow' => array('login', 'register', 'request_password', 'resend', 'verify_account', 'change_password', 'logout', 'success'),
		'loginAction' => array('action' => 'login', 'controller' => 'users', 'plugin' => 'octo_users', 'admin' => false),
		'loginRedirect' => '/',
		'actionMap' => array(
			'create' => array('add'),
			'read' => array('index', 'view'),
			'update' => array('edit'),
			'delete' => array('delete')
		),
		'authError' => 'You do not have permissions to access that page.'
	);

	private $__controller;
	private $__userModel;
	private $__controllerMap = null;

	public function authException() {
		throw new Exception($this->settings['authError']);
	}
	
	public function initialize($controller, $settings = array()) {
		Auth::instance($this);
		$this->settings = Set::merge($this->default, $settings);

		$this->__controller = $controller;
		$this->__controller->helpers[] = 'OctoUsers.AuthLinks';
		
		// Use the model name as the key everywhere by default
		$keys = array('configure', 'session', 'cookie');
		foreach ($keys as $prefix) {
			$key = $prefix.'Key';
			if (empty($this->settings[$key])) {
				$this->settings[$key] = $this->settings['model'] . 'Auth';
			}
		}
	}
	
	public function startup() {
		if($this->settings['allow'] !== '*' && !in_array($this->__controller->params['action'], $this->settings['allow']) && !$this->get()) {
			$this->__controller->redirect($this->settings['loginAction']);
		}
		
		Configure::write('System.actionMap', $this->settings['actionMap']);
		
		if(!$this->userAllowed()) {
			$this->Session->setFlash($this->settings['authError']);
			$this->__controller->redirect($this->__controller->referer(), null, true);
		}
	}
	
	public function userPermission($permission) {
		extract($permission);

		$permissions = Auth::get('Permissions');
		
		if(!isset($permissions[$controller])) {
			return false;
		}
		


		if(isset($action)) {
			if(isset($action['prefix']) && (!isset($permissions[$controller][$action['prefix']]) || $permissions[$controller][$action['prefix']] == false )) {
				return false;
			}

			foreach($this->settings['actionMap'] as $crud => $actions) {
				if(in_array($action['action'], $actions) && $permissions[$controller][$crud] == true) {
					return true;
				}
			}
		}
		elseif(isset($crud)) {
			if($permissions[$controller][$crud] == true) {
				return true;
			}
		}
	
		return false;		
	}
	
	public function userAllowed() {
		if($this->settings['allow'] === '*' || in_array($this->__controller->action, $this->settings['allow'])) {
			return true;
		}

		if($this->__controllerMap == null) {
			$plugin = Inflector::camelize($this->__controller->plugin);
			$controller = !empty($plugin) ? $plugin : 'app';
			$controller .= '.' . $this->__controller->name;
		}
		else {
			$controller = $this->__controllerMap;
		}
		
		$action = $this->__splitAction($this->__controller->action);
		
		return $this->userPermission(compact('controller', 'action'));
	}

	public function user($field = null) {
		return $this->get($field);
	}
	
	public function get($field = null) {
		$user = $this->__getActiveUser();

		if (empty($field)) {
			return $user;
		}

		if (strpos($field, '.') === false) {
			if (in_array($field, array_keys($user))) {
				return $user[$field];
			}
			$field = $this->settings['model'].'.'.$field;
		}

		return Set::extract($user, $field);
	}

	public function login($type = 'credentials', $credentials = null) {
		$userModel = $this->__getUserModel();

		$args = func_get_args();
		if (!method_exists($userModel, 'authLogin')) {
			throw new Exception(
				$userModel->alias.'::authLogin() is not implemented!'
			);
		}

		if (!is_string($type) && is_null($credentials)) {
			$credentials = $type;
			$type = 'credentials';
		}

		$user = $userModel->authLogin($type, $credentials);

		Configure::write($this->settings['configureKey'], $user);
		$this->Session->write($this->settings['sessionKey'], $user);
		return $user;
	}

	public function logout() {
		Configure::write($this->settings['configureKey'], array());
		$this->Session->write($this->settings['sessionKey'], array());
		$this->Cookie->write($this->settings['cookieKey'], '');
	
		$userModel = $this->__getUserModel();
		$methods = $userModel->Behaviors->methods();
		$userModel->Session = $this->Session;
		if(isset($methods['logout'])) {
			$userModel->logout();
		}
	
		return true;
	}

	public function persist($duration = '2 weeks') {
		$userModel = $this->__getUserModel();

		if (!method_exists($userModel, 'authPersist')) {
			throw new Exception(
				$userModel->alias.'::authPersist() is not implemented!'
			);
		}

		$token = $userModel->authPersist(Auth::get(), $duration);
		$token = $token.':'.$duration;

		return $this->Cookie->write(
			$this->settings['cookieKey'],
			$token,
			true, // encrypt = true
			$duration
		);
	}

	public function hash($password) {
		return Auth::hash($password);
	}
	
	public function buildControllers() {
		if (!Configure::read('debug')) {
			die();
		}
		$log = array();

		$Controller = ClassRegistry::init('OctoUsers.AvailableController');

		App::import('Core', 'File');
		$plugins = App::objects('plugin', null, true);
		$controllers = array('app' => App::objects('controller', null, false));
		
		foreach($plugins as $plugin) {
			$path = App::pluginPath($plugin) . 'controllers' . DS;
			$pluginControllers = App::objects('controller', $path, false);
			if(!empty($pluginControllers)) {
				$controllers[$plugin] = $pluginControllers;
			}
		}
		
		foreach($controllers as $plugin => $pluginControllers) {
			foreach($pluginControllers as $controller) {
				$existingItem = $Controller->find('first', array(
					'conditions' => array(
						'AvailableController.plugin' => $plugin,
						'AvailableController.controller' => $controller
					)
				));
							
				if(empty($existingItem['AvailableController']['id'])) {
					$Controller->create();
					$log[] = 'Added ' . $plugin . '.'.$controller;
					$Controller->save(array(
						'AvailableController' => array(
							'plugin' => $plugin,
							'controller' => $controller
						)
					));
				}
			}
		}
		
		debug($log);
		exit;
	}

	public function allow() {
		$args = func_get_args();
	
		if($args[0] == '*') {
			$this->settings['allow'] = '*';
		}
		else {
			if(!is_array($this->settings['allow'])) {
				$this->settings['allow'] = $this->default['allow'];
			}
			$this->settings['allow'] = array_merge($this->settings['allow'], $args);
		}
		
		Configure::write('System.allow', $this->settings['allow']);
	}
	
	public function mapAction($action, $crud) {
		$this->settings['actionMap'][$crud][] = $action;
	}
	
	public function mapController($controller, $plugin = 'app') {
		$this->__controllerMap = $plugin . '.' . $controller;
	}
	
	private function __splitAction($action) {	
		$prefixes = Configure::read('Routing.prefixes');

		foreach($prefixes as $prefix) {
			if(strpos($action, $prefix) === 0) {
				$action = str_replace($prefix .'_', '', $action);
				
				return array('action' => $action, 'prefix' => $prefix);
			}
		}
		
		return array('action' => $action);
	}	
	
	private function __getUserModel() {
		if ($this->__userModel) {
			return $this->__userModel;
		}

		if($this->settings['plugin'] !== false) {
			$this->__userModel = ClassRegistry::init(
				$this->settings['plugin'] . '.' . $this->settings['model']
			);
		}
		else {
			$this->__userModel = ClassRegistry::init(
				$this->settings['model']
			);			
		}
		
		return $this->__userModel;
	}

	private function __getActiveUser() {
		$user = Configure::read($this->settings['configureKey']);
		if (!empty($user)) {
			return $user;
		}

		$this->__useSession() ||
		$this->__useCookieToken() ||
		$this->__useGuestAccount();

		$user = Configure::read($this->settings['configureKey']);
		if (is_null($user)) {
			throw new Exception(
				'Unable to initilize user'
			);
		}

		return $user;
	}

	private function __useSession() {
		$user = $this->Session->read($this->settings['sessionKey']);
		if (!$user) {
			return false;
		}

		Configure::write($this->settings['configureKey'], $user);
		return true;
	}

	private function __useCookieToken() {
		$token = $this->Cookie->read($this->settings['cookieKey']);
		if (!$token || !is_string($token)) {
			return false;
		}

		// Extract the duration appendix from the token
		$tokenParts = split(':', $token);
		$duration = array_pop($tokenParts);
		$token = join(':', $tokenParts);

		$user = $this->login('cookie', compact('token', 'duration'));

		// Delete the cookie once its been used
		$this->Cookie->delete($this->settings['cookieKey']);

		if (!$user) {
			return;
		}

		$this->persist($duration);

		return (bool)$user;
	}

	private function __useGuestAccount() {
		return $this->login('guest');
	}
}

// Static Authness
class Auth{
	static function instance($setInstance = null) {
		static $instance;

		if ($setInstance) {
			$instance = $setInstance;
		}

		if (!$instance) {
			throw new Exception(
				'AuthComponent not initialized properly!'
			);
		}

		return $instance;
	}

	public static function get($field = null) {
		return self::instance()->get($field);
	}

	public static function login($type = 'credentials', $credentials = null) {
		return self::instance()->login($type, $credentials);
	}

	public static function logout() {
		return self::instance()->logout();
	}

	public static function persist($duration = '2 weeks') {
		return self::instance()->persist($duration);
	}

	public static function hash($password, $method = null, $salt = true) {
		return Security::hash($password, $method, $salt);
	}
}
?>
