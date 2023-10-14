<?php

class AvailableController extends OctoUsersAppModel {
	public $hasMany = array(
		'AvailablePermission' => array(
			'className' => 'OctoUsers.AvailablePermission'
		),
		'Permission' => array(
			'className' => 'OctoUsers.Permission',
			'foreignKey' => 'controller_id'
		)
	);
	
	public function edit($id = null, $data = null, $whitelist = array()) {
		$controller = $this->find('first', array(
			'conditions' => array(
				"{$this->alias}.{$this->primaryKey}" => $id,
				),
			'contain' => array(
				'AvailablePermission'
			)
		));

		if (empty($controller)) {
			throw new OutOfBoundsException(__('Invalid Controller', true));
		}
		$this->set($controller);

		if (!empty($data)) {
			$result = $this->saveAll($data);
			if ($result) {
				$this->data = $result;
				return true;
			} else {
				return $data;
			}
		} else {
			return $controller;
		}
	}		
	
}