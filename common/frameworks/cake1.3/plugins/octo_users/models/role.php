<?php
class Role extends OctoUsersAppModel {
	public $name = 'Role';
	
	public $hasMany = array(
		'User' => array(
			'className' => 'OctoUsers.User'
		),
		'Permission' => array(
			'className' => 'OctoUsers.Permission'
		)
	);
	
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
			$result = $this->save($data);
			if ($result !== false) {
				$this->data = array_merge($data, $result);
				return true;
			} else {
				throw new OutOfBoundsException(__('Could not save the role, please check your inputs.', true));
			}
			return $return;
		}
	}

/**
 * Edits an existing Role.
 *
 * @param string $id, role id 
 * @param array $data, controller post data usually $this->data
 * @return mixed True on successfully save else post data as array
 * @throws OutOfBoundsException If the element does not exists
 * @access public
 */
	public function edit($id = null, $data = null) {
		$role = $this->find('first', array(
			'conditions' => array(
				"{$this->alias}.{$this->primaryKey}" => $id,
				)));

		if (empty($role)) {
			throw new OutOfBoundsException(__('Invalid Role', true));
		}
		$this->set($role);

		if (!empty($data)) {
			$this->set($data);
			$result = $this->save(null, true);
			if ($result) {
				$this->data = $result;
				return true;
			} else {
				return $data;
			}
		} else {
			return $role;
		}
	}

/**
 * Returns the record of a Role.
 *
 * @param string $id, role id.
 * @return array
 * @throws OutOfBoundsException If the element does not exists
 * @access public
 */
	public function view($id = null) {
		$role = $this->find('first', array(
			'conditions' => array(
				"{$this->alias}.{$this->primaryKey}" => $id)));

		if (empty($role)) {
			throw new OutOfBoundsException(__('Invalid Role', true));
		}

		return $role;
	}

/**
 * Validates the deletion
 *
 * @param string $id, role id 
 * @param array $data, controller post data usually $this->data
 * @return boolean True on success
 * @throws OutOfBoundsException If the element does not exists
 * @access public
 */
	public function validateAndDelete($id = null, $data = array()) {
		$role = $this->find('first', array(
			'conditions' => array(
				"{$this->alias}.{$this->primaryKey}" => $id,
				)));

		if (empty($role)) {
			throw new OutOfBoundsException(__('Invalid Role', true));
		}

		$this->data['role'] = $role;
		if (!empty($data)) {
			$data['Role']['id'] = $id;
			$tmp = $this->validate;
			$this->validate = array(
				'id' => array('rule' => 'notEmpty'),
				'confirm' => array('rule' => '[1]'));

			$this->set($data);
			if ($this->validates()) {
				if ($this->delete($data['Role']['id'])) {
					return true;
				}
			}
			$this->validate = $tmp;
			throw new Exception(__('You need to confirm to delete this Role', true));
		}
	}	
}