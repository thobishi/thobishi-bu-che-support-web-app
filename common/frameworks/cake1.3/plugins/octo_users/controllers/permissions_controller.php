<?php
class PermissionsController extends OctoUsersAppController {
	public function admin_index() {
		$permissions = $this->Permission->find('permissionList');
		$availablePermissions = $this->Permission->availablePermissions();
		$roles = $this->Permission->Role->find('list');
		
		$this->set(compact('permissions', 'availablePermissions', 'roles'));		
	}
	
	public function admin_edit() {
		if(!empty($this->data)) {
			if($this->Permission->savePermissions($this->data)) {
				$this->Session->setFlash('Permissions saved');
				$this->redirect(array('action' => 'edit'));
			}
		}
		$permissions = $this->Permission->find('permissionList');
		$availablePermissions = $this->Permission->availablePermissions();
		$roles = $this->Permission->Role->find('list');
		
		$this->set(compact('permissions', 'availablePermissions', 'roles'));
	}
}