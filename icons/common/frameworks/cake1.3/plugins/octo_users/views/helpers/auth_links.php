<?php
class AuthLinksHelper extends AppHelper {
	public $helpers = array('Session', 'Html');
	
	public function checkPermission($check, $controller = null, $plugin = 'app') {
		$permissions = Auth::get('Permissions');
		
		if($controller === null) {
			$controller = Inflector::camelize($this->params['controller']);
		}
		
		$controller = $plugin . '.' . $controller;
		
		if(!isset($permissions[$controller])) {
			return false;
		}
		
		if(isset($permissions[$controller][$check]) && $permissions[$controller][$check]) {
			return true;
		}
		
		return false;
	}
	
	public function adminAllowed() {
		return true;
	}
	
	public function hasPermissions($url) {
		$url = $this->__normalize($url);
		$actionMap = Configure::read('System.actionMap');
		$allowed = Configure::read('System.allow');
		$controller = (isset($url['plugin']) && $url['plugin'] !== '' ? Inflector::camelize($url['plugin']) : 'app');
		$controller .= '.' . Inflector::camelize($url['controller']);
		$permissions = Auth::get('Permissions');
		
		if($allowed == '*' || (is_array($allowed) && in_array($url['action'], $allowed))) {
			return true;
		}
		
		if(!isset($permissions[$controller])) {
			return false;
		}
		
		if(isset($url['admin']) && $url['admin'] == true && $permissions[$controller]['admin'] == false) {
			return false;
		}
	
		foreach($actionMap as $crud => $actions) {
			if(in_array($url['action'], $actions) && $permissions[$controller][$crud] == true) {
				return true;
			}
		}
		
		return false;
	}
	
	public function admin() {
		$permissions = Auth::get('Permissions');
		
		foreach($permissions as $permission) {
			if(isset($permission['admin']) && $permission['admin'] == 1) {
				return true;
			}
		}
		
		return false;
	}
	
	public function recordActions($record, $options = array()) {
		$options = array_merge(
			array(
				'delete' => true,
				'edit' => true
			),
			$options
		);
		
		$output = '';
		if($options['edit']) {
			$output .= $this->permissionLink('Edit', array('action' => 'edit', $record['id']));
		}
		
		if($options['delete']) {
			$output .= $this->permissionLink('Delete', array('action' => 'delete', $record['id']));
		}
		
		unset($options['edit']);
		unset($options['delete']);
		
		foreach($options as $title => $link) {
			$output .= $this->permissionLink($title, $link);
		}
		
		return $output;
	}
	
	function link() {
		$args = func_get_args();
		
		return call_user_func_array(array($this, 'permissionLink'), $args);
	}
	
	function permissionLink() {
		$args = func_get_args();
		$url = $args[1];

		if(!is_array($url)) {
			return false;
		}

		if($this->hasPermissions($url)) {
			return call_user_func_array(array($this->Html, 'link'), $args);
		}
		else {
			return false;
		}
	}
	
	public function pageActions($options) {
		$options = array_merge(array(
			'model' => '',
			'wrapperTag' => 'li'
		), $options);
		
		extract($options);
		
		$pluralModel = Inflector::pluralize($model);
		
		$url = $this->__normalize();
		$action = $url['action'];
		
		$output = '';
		
		switch($action) {
			case 'add':
			case 'edit':
				$output .= $this->Html->tag($wrapperTag, $this->permissionLink(sprintf(__('Back to list of %s', true), strtolower($pluralModel)), array('action' => 'index')));
				break;
			case 'view':
				break;
			case 'index':
				$output .= $this->Html->tag($wrapperTag, $this->permissionLink(sprintf(__('Add new %s', true), strtolower($model)), array('action' => 'add')));
				break;
		}
		
		return $output;
	}
	
	private function __normalize($url = array()) {
		$currentUrl = array(
			'controller' => $this->params['controller'],
			'action' => $this->params['action'],
			'plugin' => $this->params['plugin'],
		);
		
		$prefixes = Configure::read('Routing.prefixes');
		$underPrefixes = array();
		
		foreach($prefixes as $prefix) {
			if(isset($this->params[$prefix])) {
				$currentUrl[$prefix] = $this->params[$prefix];
			}
			else {
				$currentUrl[$prefix] = false;
			}		
			
			$underPrefixes[] = $prefix . '_';
		}

		usort($underPrefixes, array($this, '__compareLength'));

		$currentUrl['action'] = str_replace($underPrefixes, '', $currentUrl['action']);

		$url = Set::merge($currentUrl, $url);
		return $url;
	}
	
	public function __compareLength($a, $b) {
		$lA = strlen($a);
		$lB = strlen($b);
		
		if($lA == $lB) {
			return 0;
		}
		
		return ($lA > $lB) ? -1 : 1;
	}
}