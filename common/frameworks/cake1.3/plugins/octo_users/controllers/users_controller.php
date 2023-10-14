<?php
class UsersController extends OctoUsersAppController {
	public $name = 'Users';
	public $uses = 'OctoUsers.User';
	public $components = array('Email');
	public $helpers = array('Time');
	
	protected function _setupAuth() {
		parent::_setupAuth();
		
		$this->Auth->mapAction('toggle_state', 'delete');
	}
	
	private function setFlash($message, $type = 'flash') {
		 $this->Session->setFlash(
				$message,
				'default',
				array(),
				$type
		);
	}
	
	public function login() {
		if(Configure::read('User.Login.layout')) {
			$this->layout = Configure::read('User.Login.layout');
		}

		if (empty($this->data)) {
            return;
        }
		
		if(isset($this->data['UserLogin'])) {
			$this->data['User'] = $this->data['UserLogin'];
		}
		
        $user = Auth::login($this->data['User']);

        if (!$user) {
			$this->setFlash(__d('octo-user', 'Could not log you in. Either your account is not active, or you entered an incorrect username or password.', true), 'error');
            return;
        }
		else {
			$this->redirect(Configure::read('System.loginRedirect'));
		}
	}
	
	public function logout() {
		Auth::logout();
		$this->redirect('/');
	}
	
	public function index() {
		
	}
	
	public function register() {
		try {			
			$result = $this->User->register($this->data);
			
			if($result == true) {
				$this->__sendUserEmail($this->User->data, 'registration', __d('octo-user', 'Your account has been registered', true));
				
				$this->redirect(array('action' => 'success'));
			}
		}
		catch(OutOfBoundsException $e) {
			$this->setFlash($e->getMessage(), 'error');
		}
		catch (Exception $e) {
			$this->setFlash($e->getMessage(), 'error');
			$this->redirect(array('action' => 'login'));
		}
		
		$this->set($this->User->fetchLookups());
	}
	
	public function success() {
		
	}
	
	public function verify_account($token = null) {
		if(Configure::read('User.Login.layout')) {
			$this->layout = Configure::read('User.Login.layout');
		}
		
		try {
			$this->User->verifyAccount($token);
			
			$this->setFlash(__d('octo-user', 'Your account has been activated, you may now login.', true));
			$this->redirect(array('action' => 'login'));			
		}
		catch(OutOfRangeException $e) {
			$this->setFlash($e->getMessage(), 'error');
			$this->redirect(array('action' => 'login'));
		}
	}
	
	public function resend() {
		if(Configure::read('User.Login.layout')) {
			$this->layout = Configure::read('User.Login.layout');
		}
		
		try {
			$result = $this->User->resendVerification($this->data);

			if($result !== false) {				
				$this->__sendUserEmail($result, 'registration', __d('octo-user', 'Information required to activate your account.', true));
				
				$this->set('sent', true);
			}
		}
		catch(OutOfBoundsException $e) {
			$this->setFlash($e->getMessage(), 'error');
		}
	}
	
	public function request_password() {
		if(Configure::read('User.Login.layout')) {
			$this->layout = Configure::read('User.Login.layout');
		}
		
		try {
			$result = $this->User->generatePasswordToken($this->data);
			
			if($result !== false) {
				$this->__sendUserEmail($this->User->data, 'password', __d('octo-user', 'Password reset request.', true));
				
				$this->set('sent', true);				
			}
		}
		catch(OutOfBoundsException $e) {
			$this->setFlash($e->getMessage(), 'error');
		}
	}	
	
	public function change_password($token) {
		if(Configure::read('User.Login.layout')) {
			$this->layout = Configure::read('User.Login.layout');
		}
		
		try {
			$result = $this->User->changePassword($this->data, $token);
			
			if($result === true) {
				$this->setFlash(__d('octo-user', 'Thank you. Your password has been changed.', true));
				$this->redirect(array('action' => 'login'));				
			}
			else {
				$this->set('user', $result);
				$this->set('token', $token);
			}
		}
		catch (OutOfRangeException $e) {
			$this->setFlash($e->getMessage(), 'error');
			$this->redirect(array('action' => 'login'));
		}
		catch (OutOfBoundsException $e) {
			$this->setFlash($e->getMessage(), 'error');			
		}
	}
	
/**
 * Edit logged in user.
 *
 * @param string $id, user id 
 * @access public
 */
	public function edit() {
		try {
			$result = $this->User->edit(Auth::get('id'), $this->data);
			if ($result === true) {
				$this->setFlash(__d('octo-user', 'Thank you. Your account has been updated.', true));
				$this->redirect('/');
			} else {
				$this->data = $result;
			}
		} catch (OutOfBoundsException $e) {
			$this->setFlash($e->getMessage(), 'error');
			$this->redirect('/');
		}
		$roles = $this->User->Role->find('list');
		
		$this->set($this->User->fetchLookups());
	}	
	
/**
 * Admin index for user.
 * 
 * @access public
 */
	public function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * Admin add for user.
 * 
 * @access public
 */
	public function admin_add() {
		try {
			$result = $this->User->add($this->data);
			if ($result === true) {
				$this->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			}
		} catch (OutOfBoundsException $e) {
			$this->setFlash($e->getMessage(), 'error');
		} catch (Exception $e) {
			$this->setFlash($e->getMessage(), 'error');
			$this->redirect(array('action' => 'index'));
		}
		$roles = $this->User->Role->find('list');
		
		$this->set(compact('roles'));
		$this->render('admin_form'); 
	}

/**
 * Admin edit for user.
 *
 * @param string $id, user id 
 * @access public
 */
	public function admin_edit($id = null) {
		try {
			$result = $this->User->edit($id, $this->data);
			if ($result === true) {
				$this->setFlash(__('User saved', true));
				$this->redirect(array('action' => 'index'));
				
			} else {
				$this->data = $result;
			}
		} catch (OutOfBoundsException $e) {
			$this->setFlash($e->getMessage(), 'error');
			$this->redirect('/');
		}
		$roles = $this->User->Role->find('list');
		
		$this->set(compact('roles'));		
		$this->render('admin_form');
	}

/**
 * Admin delete for user.
 *
 * @param string $id, user id 
 * @access public
 */
	public function admin_delete($id = null) {
		try {
			$result = $this->User->validateAndDelete($id, $this->data);
			if ($result === true) {
				$this->setFlash(__('User deleted', true));
				$this->redirect(array('action' => 'index'));
			}
		} catch (Exception $e) {
			$this->setFlash($e->getMessage(), 'error');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->User->data['user'])) {
			$this->set('user', $this->User->data['user']);
		}
	}	
	
	public function admin_toggle_state($id = null) {
		try {
			$result = $this->User->toggle_state($id);
			if ($result === true) {
				$this->setFlash(__('User state changed', true));
				$this->redirect(array('action' => 'index'));				
			} else {
				$this->setFlash(__('User state could not be changed', true), 'error');
				$this->redirect(array('action' => 'index'));
			}
		} catch (OutOfBoundsException $e) {
			$this->setFlash($e->getMessage(), 'error');
			$this->redirect(array('action' => 'index'));
		}
	}
	
	/**
	 * Configures email component
	 * 
	 * @param array $settings Array for email settings
	 */
	private function __configureEmail($settings) {
		$this->Email->reset();
		if(Configure::read('System.bccEmail')) {
			$this->Email->bcc = array(Configure::read('System.bccEmail'));
		}
		
		$this->Email->subject = Configure::read('System.email.subject_prefix'). ' ' . $settings['subject'];
		$this->Email->template = $settings['template'];
		$this->Email->layout =  $settings['layout'];
		$this->Email->sendAs =  $settings['sendAs'];
		$this->Email->replyTo = Configure::read('System.email.replyTo');
		$this->Email->from = Configure::read('System.email.from');
				
		if(Configure::read('System.smtp')) {
			$this->Email->smtpOptions = Configure::read('System.smtp');
			$this->Email->delivery = 'smtp';
		}		
	}
	
	/**
	 *
	 * @param type $userData 
	 */
	private function __sendUserEmail($userData, $template, $subject) {
		$emailSettings = array_merge(
			array(
				'subject' => $subject,
				'template' => $template,
				'layout' => 'default',
				'sendAs' => 'text'
			), 
			(array)Configure::read('User.registration.email')
		);
		
		$this->set('user', $userData);
		
		$this->__configureEmail($emailSettings);
		$this->Email->to = $userData['User']['email_address'];
		
		$this->Email->send();
	}
}