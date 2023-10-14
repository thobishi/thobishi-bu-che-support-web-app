<?php

App::import('Core', 'String');	

class User extends OctoUsersAppModel {
	public $name = "User";
	
	public $belongsTo = array(
		'Role' => array(
			'className' => 'OctoUsers.Role'
		)
	);
	
	private $__userOptions = array(
		'emailExpire' => '72',
		'passwordExpire' => '72'
	);
	
	public $order = 'last_name';
	
	public $virtualFields = array(
		'name' => 'concat(first_name, " ", last_name)'
	);
	
	public $displayField = 'name';	
	
	public function __construct( $id = false, $table = NULL, $ds = NULL ) {			
		parent::__construct($id, $table, $ds);		
		
		$this->virtualFields = array(
			'name' => 'CONCAT('.$this->alias.'.first_name, " ", ' . $this->alias . '.last_name)'
		);
		
		$this->validate = array(
			'email_address' => array(
				'required' => array(
					'rule' => 'notempty',
					'last' => true,
					'message' => __d('octo-users', 'You need to enter an email address.', true)
				),
				'email' => array(
					'rule' => 'email',
					'message' => __d('octo-users', 'You need to enter a valid email address.', true)
				),
				'unique' => array(
					'rule' => 'isUnique',
					'message' => __d('octo-users', 'This email address has already been registered. If you have forgotten your password, please use the "forgot password" functionality.', true)
				)
			),
			'current_password' => array(
				'current' => array('rule' => array('matchCurrent'), 'message' => __('Password does not match your current password', true))
			),			
			'clean_password' => array(
				'required' => array(
					'rule' => 'notempty',
					'last' => true,
					'message' => __d('octo-users', 'Please enter a password for your account', true)
				),
				'minlen' => array(
					'rule' => array('minlength', 6),
					'message' => __d('octo-users', 'Your password needs to be at least 6 characters long', true)
				)
			),
			'confirm_password' => array(
				'required' => array(
					'rule' => array('compareField', 'clean_password'),
					'message' => __d('octo-users', 'The password and confirm password fields do not match', true)
				)
			),
			'first_name' => array(
				'required' => array(
					'rule' => 'notempty',
					'last' => true,
					'message' => __d('octo-users', 'You need to enter your first name.', true)
				),
			),	
			'last_name' => array(
				'required' => array(
					'rule' => 'notempty',
					'last' => true,
					'message' => __d('octo-users', 'You need to enter your first name.', true)
				),
			),
		);
		
		if(Configure::read('User')) {
			$this->__userOptions = array_merge($this->__userOptions, Configure::read('User'));
			if(!empty($this->__userOptions['Fields'])) {
				foreach($this->__userOptions['Fields'] as $fieldName => $options) {
					if(!empty($options['validate'])) {
						$this->validate[$fieldName] = $options['validate'];
					}

					if(strpos($fieldName, '_id') !== false && (!isset($options['relationship']) || $options['relationship'] !== false)) {
						$relatedModel = Inflector::camelize(str_replace('_id', '', $fieldName));
						$relOptions = array();
						if(isset($options['plugin'])) {
							$relOptions['className'] = $options['plugin'] . '.'.$relatedModel;
						}
						
						if(isset($options['relationship'])) {
							$relOptions = array_merge($relOptions, $options['relationship']);
						}
						
						$this->bindModel(array('belongsTo' => array($relatedModel => $relOptions)), false);
					}

					if(isset($options['virtualField'])) {
						$this->virtualFields[$fieldName] = $options['virtualField'];
					}
				}
			}

			if(!empty($this->__userOptions['Relations'])) {
				$this->bindModel($this->__userOptions['Relations'], false);
			}

			if(!empty($this->__userOptions['Behaviour'])) {
				if(!isset($this->__userOptions['Behaviour'][0])) {
					$this->__userOptions['Behaviour'] = array($this->__userOptions['Behaviour']);
				}
				
				foreach($this->__userOptions['Behaviour'] as $behaviour) {
					$this->Behaviors->attach($behaviour['name'], $behaviour['options']);
					
					if(isset($behaviour['variables'])) {
						foreach($behaviour['variables'] as $variable => $value) {
							$this->{$variable} = $value;
						}
					}
				}
			}
		}
	}
	
	public function matchCurrent() {
		$password = trim($this->data[$this->alias]['current_password']);
		if(!empty($password)) {
			$password = Auth::hash($password);
			
			if ($this->find('count', array('conditions' => array('User.id' => $this->data[$this->alias]['id'], 'User.password' => $password)))) {
				return true;
			}
			
			return false;
		}
		else {
			return true;
		}
	}	
	
	public function compareField($value, $options) {
		$field1 = reset($value);
		$field2 = $this->data[$this->alias][$options];

		if($field1 == $field2) {
			return true;
		}
		return false;
	}

	public function beforeSave() {
		if(!empty($this->data[$this->alias]['clean_password'])) {
			$this->data[$this->alias]['password'] = Auth::hash($this->data[$this->alias]['clean_password']);
		}
				
		return true;
	}
	
    public function authLogin($type, $credentials = array()) {
		$methods = $this->Behaviors->methods();
		
        switch ($type) {
            case 'guest':
                return array();
            case 'credentials':
                $password = Auth::hash($credentials['password']);

                $conditions = array(
                    'User.email_address' => $credentials['email_address'],
                    'User.password' => $password,
					'User.active' => 1,
					'User.email_authenticated' => 1
                );
				
				if(!empty($this->__userOptions['Behaviour']) && isset($methods['authLoginConditions'])) {
					$conditions = $this->authLoginConditions($conditions);
				}
				
				$contain = array('Role');
				if(!empty($this->__userOptions['Behaviour']) && isset($methods['authLoginContain'])) {
					$contain = $this->authLoginContain($contain);
				}				
				
				$user = $this->find('first', compact('conditions', 'contain'));
				
				if($user === false && !empty($this->__userOptions['Behaviour']) && isset($methods['alternativeAuth'])) {
					$user = $this->alternativeAuth($type, $credentials);
				}

				if($user !== false) {
					$user['Permissions'] = $this->Role->Permission->userPermissions($user['User']['id']);
				}
				
				return $user;
				
                break;
            default:
                return null;
        }
    }
		
	/**
	 * Handles registering of a new user. Also creates the required email validation token.
	 * 
	 * @param mixed $data Submited data from registration form
	 * @return mixed 
	 */
	public function register($postData) {
		if(Configure::read('User.registration') === false) {
			throw new Exception(__d('octo-users', 'You are not allowed to register an account on this system.', true));
		}
		
		if(empty($postData)) {
			return false;
		}
		
		$postData[$this->alias]['email_token'] = $this->__generateToken();
		$postData[$this->alias]['token_expiration'] = date('Y-m-d H:i:s', strtotime('+'.$this->__userOptions['emailExpire'].' hours'));
		$postData[$this->alias]['active'] = 1;
		$postData[$this->alias]['email_authenticated'] = 0;
		$defaultRole = $this->Role->findByDefault(1);
		$postData[$this->alias]['role_id'] = $defaultRole['Role']['id'];
		
		$this->create();
		$this->set($postData);
		if($this->save(null, true)) {
			$this->read();
			return true;
		}
		else {
			$this->__throwValidationException();
		}
	}
	
	public function verifyAccount($token) {
		if($id = $this->validateToken($token)) {
			$this->save(array(
				'User' => array(
					'id' => $id,
					'email_token' => null,
					'token_expiration' => null,
					'email_authenticated' => 1
				)
			));
		}
		else {
			throw new OutOfRangeException(__d('octo-user', 'The validation key does not exist or is outdated.', true));
		}
	}
	
	public function validateToken($token, $type = 'email') {
		if(empty($token)) {
			return false;
		}
		
		$conditions = array(
			$this->alias . '.token_expiration >=' => date('Y-m-d H:i:s')
		);
				
		if($type == 'email') {
			$conditions[$this->alias . '.email_token'] = $token;
		}
		else {
			$conditions[$this->alias . '.password_token'] = $token;
		}
	
		$tokened = $this->find('first', array('conditions' => $conditions, 'fields' => 'id'));
		
		if(!empty($tokened)) {
			return $tokened[$this->alias]['id'];
		}
		else {
			return false;
		}
	}
	
/**
 * Resends the verification if the user is not already validated or invalid
 *
 * @param array $postData Post data from controller
 * @return mixed False or user data array on success
 */
	public function resendVerification($postData = array()) {
		if(empty($postData)) {
			return false;
		}
		
		if (!isset($postData[$this->alias]['email_address']) || empty($postData[$this->alias]['email_address'])) {
			$this->invalidate('email_address', __d('octo-user', 'Please enter your email address.', true));
			$this->__throwValidationException();
		}

		$user = $this->find('first', array(
			'conditions' => array(
				$this->alias . '.email_address' => $postData[$this->alias]['email_address']
			)
		));

		if (empty($user)) {
			$this->invalidate('email_address', __d('octo-user', 'The email address does not exist in the system.', true));
			$this->__throwValidationException();
		}

		if ($user[$this->alias]['email_authenticated'] == 1) {
			$this->invalidate('email_address', __d('octo-user', 'Your account is already authenticated.', true));
			$this->__throwValidationException();
		}

		if ($user[$this->alias]['active'] == 0) {
			$this->invalidate('email_address', __d('octo-user', 'Your account is disabled.', true));
			$this->__throwValidationException();
		}

		$user[$this->alias]['email_token'] = $this->__generateToken();
		$user[$this->alias]['token_expiration'] = date('Y-m-d H:i:s', strtotime('+'.$this->__userOptions['emailExpire'].' hours'));

		return $this->save($user, false);
	}
	
	public function generatePasswordToken($postData = array()) {
		if(empty($postData)) {
			return false;
		}
		
		if (!isset($postData[$this->alias]['email_address']) || empty($postData[$this->alias]['email_address'])) {
			$this->invalidate('email_address', __d('octo-user', 'Please enter your email address.', true));
			$this->__throwValidationException();
		}

		$user = $this->find('first', array(
			'conditions' => array(
				$this->alias . '.email_address' => $postData[$this->alias]['email_address']
			)
		));
		
		if (empty($user)) {
			$this->invalidate('email_address', __d('octo-user', 'The email address does not exist in the system.', true));
			$this->__throwValidationException();
		}

		if ($user[$this->alias]['active'] == 0) {
			$this->invalidate('email_address', __d('octo-user', 'Your account is disabled.', true));
			$this->__throwValidationException();
		}		
		
		$user[$this->alias]['password_token'] = $this->__generateToken();
		$user[$this->alias]['token_expiration'] = date('Y-m-d H:i:s', strtotime('+'.$this->__userOptions['passwordExpire'].' hours'));
		
		$saved = $this->save($user, false);		
		$this->set($user);
		
		return $saved;
	}
	
	public function changePassword($postData, $token) {
		if($id = $this->validateToken($token, 'password')) {
			if(!empty($postData)) {
				$postData[$this->alias]['id'] = $id;
				$postData[$this->alias]['password_token'] = null;
				$postData[$this->alias]['token_expiration'] = null;
				if($this->save($postData, true, array('id', 'password_token', 'token_expiration', 'password', 'clean_password', 'confirm_password'))) {
					return true;
				}
				else {
					$this->__throwValidationException();
				}
			}
			else {
				return $this->read(null, $id);
			}
		}
		else {
			throw new OutOfRangeException(__d('octo-user', 'The password token does not exist or is outdated.', true));
		}	
	}
	
/**
 * Adds a new record to the database
 *
 * @param array post data, should be Contoller->data
 * @return array
 * @access public
 */
	public function add($data = null) {
		if (!empty($data)) {
			$this->create();
			$data[$this->alias]['email_authenticated'] = 1;
			$result = $this->save($data);

			if ($result !== false) {
				$this->data = array_merge($data, $result);
				return true;
			} else {
				$this->__throwValidationException();
			}
			return $return;
		}
	}

/**
 * Edits an existing User.
 *
 * @param string $id, user id 
 * @param array $data, controller post data usually $this->data
 * @return mixed True on successfully save else post data as array
 * @throws OutOfBoundsException If the element does not exists
 * @access public
 */
	public function edit($id = null, $data = null, $whitelist = array(), $extraConditions = array()) {
		$conditions = array(
			"{$this->alias}.{$this->primaryKey}" => $id,
		);
			
		$conditions = array_merge($conditions, $extraConditions);
		
		$user = $this->find('first', array(
			'conditions' => $conditions
		));

		if (empty($user)) {
			throw new OutOfBoundsException(__('Invalid User', true));
		}
		$this->set($user);

		if (!empty($data)) {
			if(isset($data['User']['clean_password']) && empty($data['User']['clean_password'])) {
				unset($data['User']['clean_password']);
			}
			if(isset($data['User']['current_password']) && empty($data['User']['current_password'])) {
				unset($data['User']['current_password']);
				unset($data['User']['clean_password']);
				unset($data['User']['confirm_password']);
			}

			$result = $this->save($data, true, $whitelist);
			if ($result) {
				$this->data = $result;
				return true;
			} else {
				return $data;
			}
		} else {
			return $user;
		}
	}	
	
	public function toggle_state($id = null, $extraConditions = array()) {
		$conditions = array(
			"{$this->alias}.{$this->primaryKey}" => $id,
		);
			
		$conditions = array_merge($conditions, $extraConditions);
		
		$user = $this->find('first', array(
			'conditions' => $conditions
		));	
		if (empty($user)) {
			throw new OutOfBoundsException(__('Invalid User', true));
		}	
		
		$user['User']['active'] = !$user['User']['active'];
		
		if($this->save($user, false)) {
			return true;
		}
		else {
			return false;
		}
	}
	
/**
 * Validates the deletion
 *
 * @param string $id, user id 
 * @param array $data, controller post data usually $this->data
 * @return boolean True on success
 * @throws OutOfBoundsException If the element does not exists
 * @access public
 */
	public function validateAndDelete($id = null, $data = array()) {
		$user = $this->find('first', array(
			'conditions' => array(
				"{$this->alias}.{$this->primaryKey}" => $id,
				)));

		if (empty($user)) {
			throw new OutOfBoundsException(__('Invalid User', true));
		}

		$this->data['user'] = $user;
		if (!empty($data)) {
			$data[$this->alias]['id'] = $id;
			$tmp = $this->validate;
			$this->validate = array(
				'id' => array('rule' => 'notEmpty'),
				'confirm' => array('rule' => '[1]'));

			$this->set($data);
			if ($this->validates()) {
				if ($this->delete($data[$this->alias]['id'])) {
					return true;
				}
			}
			$this->validate = $tmp;
			throw new Exception(__('You need to confirm to delete this User', true));
		}
	}	
	
	public function fetchLookups() {
		$associated = $this->getAssociated('belongsTo');	
		
		$return = array();
		foreach($associated as $associate) {
			$return[Inflector::tableize($associate)] = $this->$associate->find('list');
		}
		
		return $return;
	}
	
	public function generateGroup(&$Model, &$query, $settings) {
		$Model->virtualFields['userName'] = 'concat(User.first_name, " ", User.last_name)';
		
		$query['group'][] = $this->alias . '.' . $this->primaryKey;
		$query['fields'][] = 'userName';
		$query['contain'][] = $this->alias;
	}
	
	public function groupResults(&$Model, $result, $groupBy) {
		return array(
			'label' => $result[$Model->alias]['userName'],
			'value' => $result[$Model->alias]['count'],
			'id' => $groupBy . '|' . $result[$this->alias]['id']
		);
	}	
	
	/**
	 * Generates a UUID for use as a token
	 * 
	 * @return string
	 */
	private function __generateToken() {
		return String::uuid();
	}
	
	private function __throwValidationException() {
		throw new OutOfBoundsException(__d('octo-user', 'Please fix the validation errors highlighted below.', true));
	}
}