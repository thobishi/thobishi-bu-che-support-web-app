<?php
class Permission extends OctoUsersAppModel {
	public $name = 'Permission';
	
	public $belongsTo = array(
		'Controller' => array(
			'className' => 'OctoUsers.AvailableController',
			'type' => 'right'
		),
		'Role' => array(
			'className' => 'OctoUsers.Role'
		)
	);
	
	public function userPermissions($userId) {
		$user = $this->Role->User->find('first', array(
			'conditions' => array('User.id' => $userId),
			'fields' => array('User.role_id')
		));
		
		$permissions = $this->find('all', array(
			'conditions' => array('Permission.role_id' => $user['User']['role_id']),
			'contain' => 'Controller'
		));

		$return = array();
		foreach($permissions as $permission) {
			$controller = $permission['Controller']['plugin'].'.'.$permission['Controller']['controller'];
			$return[$controller] = array();
			foreach($permission['Permission'] as $crud => $value) {
				if(strpos($crud, '_') === 0) {
					$return[$controller][ltrim($crud, '_')] = $value;
				}
			}
		}
		
		return $return;
	}
	
	public function _findPermissionList($state, $query, $result = array()) {
		if($state == 'before') {
			$query['contain'] = array('Role', 'Controller' => array('AvailablePermission'));
			
			return $query;
		}
		else {
			$return = array();
	
			foreach($result as $permission) {
				$roleId = $permission['Role']['id'];
				$controller = $permission['Controller']['plugin'].'.'.$permission['Controller']['controller'];
				
				if(empty($return[$controller])) {
					$availablePermissions = Set::extract('/AvailablePermission[active=1]', $permission['Controller']);
					$availablePermissions = Set::combine($availablePermissions, '{n}.AvailablePermission.permission', '{n}.AvailablePermission.title');
					
					$return[$controller] = array(
						'id' => $permission['Controller']['id'], 
						'title' => $permission['Controller']['title'],
						'availablePermissions' => $availablePermissions
					);
				}
				
				if($roleId !== null) {
					$return[$controller][$roleId]['name'] = $permission['Role']['name'];

					foreach($permission['Permission'] as $crud => $value) {
						if(strpos($crud, '_') === 0) {
							$return[$controller][$roleId][ltrim($crud, '_')] = $value;
						}
					}
				}
			}
			
			return $return;
		}
	}
	
	public function availablePermissions() {
		$return = array();
		
		foreach($this->_schema as $fieldName => $details) {
			if(strpos($fieldName, '_') === 0) {
				$return[] = ltrim($fieldName, '_');
			}			
		}
		
		return $return;
	}
	
	public function savePermissions($postData) {
		$saveArray = array();
		
		foreach($postData as $controllerId => $roles) {
			foreach($roles as $roleId => $permissions) {
				$existing = $this->find('first', array(
					'conditions' => array(
						'Permission.controller_id' => $controllerId,
						'Permission.role_id' => $roleId
					),
					'fields' => array('Permission.id')
				));
				$row = array(
					'controller_id' => $controllerId,
					'role_id' => $roleId
				);
				
				if($existing !== false) {
					$row['id'] = $existing['Permission']['id'];
				}
				
				foreach($permissions as $crud => $value) {
					$row['_' . $crud] = $value;
				}
				
				$saveArray[] = $row;
			}
		}
		
		return $this->saveAll($saveArray);
	}
}