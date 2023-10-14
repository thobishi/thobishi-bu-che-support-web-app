<?php

class AvailableControllersController extends OctoUsersAppController {
	public function admin_index() {
		$this->AvailableController->recursive = 1;
		
		$this->set('available_controllers', $this->paginate());
	}
	
	public function admin_edit($id = null) {
		try {
			$result = $this->AvailableController->edit($id, $this->data);
			if ($result === true) {
				$this->Session->setFlash(__('Controller saved', true));
				$this->redirect(array('action' => 'index'));
				
			} else {
				$this->data = $result;
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}

		$this->set('availablePermissionFields', $this->AvailableController->Permission->availablePermissions());
		
		$this->render('admin_form');
	}
}
