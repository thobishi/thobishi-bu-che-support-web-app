<?php
class RolesController extends OctoUsersAppController {
/**
 * Admin index for role.
 * 
 * @access public
 */
	public function admin_index() {
		$this->Role->recursive = 0;
		$this->set('roles', $this->paginate()); 
	}

/**
 * Admin add for role.
 * 
 * @access public
 */
	public function admin_add() {
		try {
			$result = $this->Role->add($this->data);
			if ($result === true) {
				$this->Session->setFlash(__('The role has been saved', true));
				$this->redirect(array('action' => 'index'));
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}

		$this->render('admin_form');
	}

/**
 * Admin edit for role.
 *
 * @param string $id, role id 
 * @access public
 */
	public function admin_edit($id = null) {
		try {
			$result = $this->Role->edit($id, $this->data);
			if ($result === true) {
				$this->Session->setFlash(__('Role saved', true));
				$this->redirect(array('action' => 'index'));
				
			} else {
				$this->data = $result;
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}

		$this->render('admin_form');
	}

/**
 * Admin delete for role.
 *
 * @param string $id, role id 
 * @access public
 */
	public function admin_delete($id = null) {
		try {
			$result = $this->Role->validateAndDelete($id, $this->data);
			if ($result === true) {
				$this->Session->setFlash(__('Role deleted', true));
				$this->redirect(array('action' => 'index'));
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->Role->data['role'])) {
			$this->set('role', $this->Role->data['role']);
		}
	}
}