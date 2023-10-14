<?php
/**
 * Swiss army component
 *
 * Various bits of common functionality wrapped up in a single component
 *
 * PHP version 5
 *
 * Copyright (c) 2008, Andy Dawson
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2008, Andy Dawson
 * @link          www.ad7six.com
 * @package       mi
 * @subpackage    mi.controllers.components
 * @since         v 1.0
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * SwissArmyComponent class
 *
 * @uses          AppComponent
 * @package       mi
 * @subpackage    mi.controllers.components
 */
class SwissArmyComponent extends Object {

/**
 * name property
 *
 * @var string 'SwissArmy'
 * @access public
 */
	public $name = 'SwissArmy';

/**
 * components property
 *
 * @var array
 * @access public
 */
	public $components = array('Session', 'RequestHandler');

/**
 * settings property
 *
 * @var array
 * @access public
 */
	public $settings = array(
		'autoLanguage' => false,
		'autoLayout' => false,
		'authAutoFields' => false,
		'authAutoLoginUrl' => true,
		'authExtraInfo' => array(),
		'authLoginSessionToken' => true,
		'storeHistory' => true,
		'browseHistory' => 50,
		'filterIgnore' => array('limit', 'show', 'sort', 'page', 'direction', 'step'),
		'filterOperators' => array(
			'equal' => '= ',
			'greaterThan' => '> ',
			'greaterThanOrEqual' => '>= ',
			'lessThan' => '< ',
			'lessThanOrEqual' => '<= ',
			'notEqual' => '!= ',
			'like' => 'LIKE ',
			'notLike' => 'NOT LIKE ',
			'null' => 'NULL',
			'notNull' => 'NOT NULL',
			'between' => 'BETWEEN ',
			'in' => 'in'
		),
		'redirectOnError' => false, // a Url
		'sessionReferer' => true,
		'usingSubdomains' => null
	);

	public $webroot = '/';
/**
 * fallBack property
 *
 * @var mixed null
 * @access protected
 */
	protected $_fallBack = null;

/**
 * here property
 *
 * @var mixed null
 * @access protected
 */
	protected $_here = null;

/**
 * history property
 *
 * @var array
 * @access protected
 */
	protected $_history = array();

/**
 * last property
 *
 * @var mixed null
 * @access protected
 */
	protected $_last = null;
/**
 * referer property
 *
 * @var mixed null
 * @access protected
 */
	protected $_referer = null;

/**
 * loadComponent method
 *
 * Add a component on the fly. Careful, doesn't handle a component's dependencies (originally
 * intended only for adding the Cookie which has a high overhead)
 *
 * @param string $name 'Cookie'
 * @param array $config array()
 * @return void
 * @access public
 */
	public function loadComponent($name = 'Cookie', $config = array()) {
		if (isset($this->$name)) {
			return;
		}
		$C =& $this->Controller;
		$init = false;
		if (!isset($C->$name)) {
			$init = true;
			App::import('Component', $name);
			if (strpos($name, '.')) {
				list($_, $name) = explode('.', $name);
			}
			$cName = $name . 'Component';
			if ($name === 'Session') {
				$C->$name = new $cName($C->_controllerVars['base']);
			} else {
				$C->$name = new $cName();
			}
		}
		$this->$name =& $C->$name;
		if($init && is_callable(array($this->$name, 'initialize'))) {
			$this->$name->initialize($C, $config);
		}
	}

/**
 * back method Refers to the referer if present, and the session if not, to determine where to go back to.
 *
 * An extensions to the controller referer method, using the session to store a browsing history.
 * Always uses the referer if present and the previous screen is the target.
 *
 * Designed with single step in mind but setup to allow extending to work with multiple threads (windows or tabs)
 * and allow browsing your history if desired (see users controller back method)
 *
 * Multiple threads are not tested. Add a named arg (/historyThread:x) to the url to allow 'thread-safe' and back
 * logic possibilities.
 *
 * Params:
 * 	$steps = number of steps to go back. 1 = previous screen, 2 = one before that etc.
 * 	$default = default url if no session/referer is found
 * 	$redirect = whether to redirect to the found url, or just to return it
 *
 * @param int $steps
 * @param string $default
 * @param bool $redirect
 * @param mixed $thread null
 * @return void
 * @access public
 */
	public function back($steps = 1, $default = null, $redirect = true, $thread = null) {
		$C =& $this->Controller;
		if (!$thread) {
			$thread = $this->_browseKey();
		}
		if (!array_key_exists($thread, $this->_history)) {
			$this->_history[$thread] = (array)$this->Session->read('history.' . $thread);
		}

		if (!empty($C->data['App']['referer']) && $this->_normalizeUrl($C->data['App']['referer']) !== $this->_here) {
			if ($redirect) {
				return $this->_redirect($C->data['App']['referer']);
			}
			return $C->data['App']['referer'];
		}

		if ($default === null) {
			$noDefault = true;
			$default = $this->_fallBack;
		}
		$normalizedDefault = $this->_normalizeUrl($default, true);
		$prev = null;
		do {
			if (!$prev) {
				if (in_array($this->_referer, $this->_history[$thread])) {
					$prev = $this->_referer;
				} elseif ($this->_last && $this->_last !== $this->_here) {
					$prev = $this->_last;
				} else {
					end($this->_history[$thread]);
					$prev = key($this->_history[$thread]);
				}
			} elseif (isset($this->_history[$thread][$prev])) {
				$prev = $this->_history[$thread][$prev];
			} else {
				$steps = 0; // break out
			}
			$steps--;
		} while ($steps);

		if (!$prev) {
			$prev = $this->_last;
			if (!$prev) {
				$prev = $default;
			}
		}
		if ($redirect) {
			if (!empty($noDefault) && $thread !== 'norm') {
				return $this->back($steps, $prev, $redirect, 'norm');
			}
			$this->_redirect($prev);
		}
		return $prev;
	}

/**
 * beforeRender method
 *
 * @return void
 * @access public
 */
	public function beforeRender() {
		$C =& $this->Controller;

		if (empty ($C) || !empty($C->params['requested'])) {
			return;
		}
		if ($this->_storeHistory() && $this->_here !== $this->_last) {
			$thread = $this->_browseKey();
			$this->_browseHistory($thread);
			$this->_history[$thread][$this->_here] = $this->_last;
			$this->Session->write('history.' . $thread, $this->_history[$thread]);
		}
		$this->Session->write('referer', $this->_last);
		if (!isset ($C->viewVars['data'])) {
			$C->set('data', $C->data);
		}
		if (!isset ($C->viewVars['modelClass'])) {
			$C->set('modelClass', $C->modelClass);
		}
		if (!empty($C->postActions)) {
			$C->set('postActions', array(Inflector::underscore($C->name) => $C->postActions));
		}
	}

/**
 * For a get request for a postAction method (cannot be run via get, must by POST|PUT|DELETE)
 *
 * If the reason is becuase it's a GET request
 * 	If debug it 0
 * 		Check that the hash of the url matches the passed $_GET['token'] and if not - bail
 * 	Always
 * 		If it's admin_delete and the item doesn't exist - bail
 * 		If it's a post request - check the user
 * 		Since this code runs before the security component would normally, generate a form token
 * 		Set the referer, check if the controller has implemented a (public) _confirmationView function
 * 		and render the do-you-want-to-do-that confirmation form
 * Otherwise it's a bogus form submission, or access denied - bail
 *
 * @param mixed $reason null
 * @return void
 * @access public
 */
	public function blackHole($reason = null) {
		$C =& $this->Controller;
		if ($reason == 'post') {
			if (empty($C->params['url']['token'])) {
				return $this->back();
			}

			$url = '/' . ltrim($C->params['url']['url'], '/');
			if ($this->settings['usingSubdomains']) {
			   $url = 'http://' . env('HTTP_HOST') . $url;
			}
			$hash = Security::hash($url, null, true);

			$submittedHash = preg_replace('@\..*$@', '', $C->params['url']['token']);
			if ($hash !== $submittedHash) {
				return $this->back();
			}
			if ($C->action === 'admin_delete') {
				if (!$C->{$C->modelClass}->id && !empty($C->params['pass'][0])) {
					$C->{$C->modelClass}->id = $C->params['pass'][0];
				}
				if (!$C->{$C->modelClass}->exists()) {
					return $this->back();
				}
			}
			if (isset($C->Security)) {
				$C->Security->_generateToken($C);
			}
			if (isset($C->params['pass'][0])) {
				$C->data = array(
					'id' => $C->params['pass'][0],
					'display' => $C->{$C->modelClass}->display($C->params['pass'][0]),
				);
			}
			$C->data['modelClass'] = $C->modelClass;
			$this->settings['storeHistory'] = false;
			$this->Session->write('referer', $this->back(1, null, false, 'norm', false));
			$C->params['isAjax'] = $C->RequestHandler->isAjax();
			$layout = null;
			if ($C->params['isAjax']) {
				$layout = 'ajax';
			}
			$view = '/elements/confirm_action';
			if (method_exists($C, '_confirmationView')) {
				if ($_view = $C->_confirmationView()) {
					$view = $_view;
				}
			}
			echo $C->render($view, $layout);
			return $C->_stop();
		}
		if ($reason == 'auth' && $C->data) {
			$C->Session->setFlash(__d('mi', 'Invalid form submission', true));
			$this->_redirect('/' . ltrim($C->params['url']['url'], '/'));
		}
		$code = 404;
		if ($reason == 'login') {
			$code = 401;
		} else {
			$C->Session->setFlash(__d('mi', 'Permission denied', true));
		}
		$this->_redirect(null, $code);
	}

/**
 * handlePostActions method
 *
 * Setup the security component if set, otherwise:
 *
 * if a GET request has been made for a POST/DELETE only action render a confirmation form
 * If a POST/DELETE request is received only continue if they clicked the submit button
 *
 * @return void
 * @access public
 */
	public function handlePostActions() {
		$C =& $this->Controller;
		if (empty($C->postActions)) {
			return;
		}
		if (isset($C->Security)) {
			if (in_array($C->action, $C->postActions)) {
				$C->Security->disabledFields[] = 'App.submit';
			}
			$C->Security->blackHoleCallback = '_blackHole';
			call_user_func_array(array(&$C->Security, 'requirePost'), $C->postActions);
		} elseif (in_array($C->action, $C->postActions)) {
			if (!$C->data) {
				return $C->_blackHole('post');
			} elseif (!$C->RequestHandler->isAjax() && $C->data['App']['submit'] !== $C->data['App']['continue']) {
				$C->Session->setFlash(__d('mi', 'No action taken', true));
				return $this->back();
			}
		}
	}

/**
 * initialize method
 *
 * Load default app settings (if configured to do so)
 * Set the referer info if it's not a requestAction call
 * Setup the user's language a
 *
 *
 * @param mixed $Controller
 * @param array $config
 * @return void
 * @access public
 */
	public function initialize(&$C, $config = array()) {
		if (!empty($C->params['requested'])) {
			return;
		}
		$this->Controller =& $C;
		$this->webroot = $this->Controller->webroot;

		$this->settings = array_merge($this->settings, $config);
		if ($this->settings['usingSubdomains'] === null) {
			$cookieDomain = ini_get('session.cookie_domain');
			if ($cookieDomain && $cookieDomain[0] === '.') {
				$this->settings['usingSubdomains'] = true;
			} else {
				$this->settings['usingSubdomains'] = false;
			}
		}

		$this->_storeHistory();
		$this->_autoLayout();

		if ($C->name === 'CakeError') {
			if ($this->settings['redirectOnError']) {
				if (Configure::read()) {
					$normalized = $this->_normalizeUrl($this->settings['redirectOnError']);
					$C->log('Request for ' . $C->here .
						' generated an error. redirecting to ' . $normalized, LOG_DEBUG);
				}
				$this->_redirect($this->settings['redirectOnError']);
			}
			return;
		}
		$this->_autoLanguage();
	}

/**
 * initializeBackData method
 *
 * @return void
 * @access public
 */
	public function initializeBackData() {
		$C = $this->Controller;
		$thread = $this->_browseKey();
		$this->_history[$thread] = (array)$this->Session->read('history.' . $thread);
		$this->_here = $this->_normalizeUrl($C->here);
		$this->_referer = $this->_normalizeUrl($C->referer());
		$this->_fallBack = $this->_normalizeUrl(array('action' => 'index'));
		if (isset($this->_history[$thread][$this->_here])) {
			$this->_last = $this->_history[$thread][$this->_here];
		} elseif ($this->_referer !== '/') {
			$this->_last = $this->_referer;
		} else {
			$this->_last = $this->_fallBack;
		}

		if (isset($C->Auth) && $C->action === 'login') {
			$referer = $this->back(1, $this->Session->read('Auth.redirect'), false);
			$this->Session->write('Auth.redirect', $referer);
		}
	}

/**
 * autoLanguage method
 *
 * Set $controller->params['lang'] language if appropriate
 *
 * @return void
 * @access protected
 */
	protected function _autoLanguage() {
		if (!$this->settings['autoLanguage']) {
			return;
		}
		$C =& $this->Controller;
		if (isset($C->params['lang'])) {
			Configure::write('Config.language', $C->params['lang']);
			$this->Session->write('Config.language', $C->params['lang']);
		} elseif (!$this->Session->check('Config.languageChecked')) {
			$this->loadComponent();
			$lang = $this->Cookie->read('lang');
			$this->Session->write('Config.languageChecked', true);
			if ($lang) {
				$this->Session->write('Config.language', $lang);
			}
		}
	}

/**
 * autoLayout method
 *
 * Set the admin layout automatically, if it's an error - set to the error layout
 *
 * @return void
 * @access protected
 */
	protected function _autoLayout() {
		$C =& $this->Controller;
		if (!empty($C->params['requested']) || !$this->settings['autoLayout'] || $C->layout != 'default') {
			return;
		}
		if ($this->RequestHandler->isAjax()) {
			$C->layout = isset($C->params['url']['layout'])?$C->params['url']['layout']:'ajax';
		} elseif (!empty($C->params['admin'])) {
			$C->layout = 'admin_default';
		} elseif ($C->name === 'CakeError' && $C->viewPath === 'errors') {
			$C->layout = 'error';
		}
	}

/**
 * browseHistory method
 *
 * Called during beforeRender. Any controller action which renders a view will appear in the browse history - only for
 * get requests.
 * Useful if you always want to go back to the previous url, rather than the first entry point such as for the auth
 * login action.
 * This session history is used by the back function (only when no referer is present).
 *
 * @param int $thread
 * @return void
 * @access protected
 */
	protected function _browseHistory($thread = null) {
		$C =& $this->Controller;
		if (!$this->settings['browseHistory'] || !empty($_FORM) && empty($C->params['requested'])) {
			return;
		}
		if (count($this->Session->read('history.' . $thread)) > $this->settings['browseHistory']) {
			array_shift($_SESSION['history'][$thread]);
		}
	}

/**
 * browseKey method
 *
 * Determines the key for the history log. Prevents an ajax request being considered the referer
 * for a none-ajax request.
 *
 * @return void
 * @access protected
 */
	protected function _browseKey() {
		/*
		if (empty($this->Controller->RequestHandler) || $this->Controller->RequestHandler->isAjax()) {
			return 'ajax';
		}
		 */
		return 'norm';
	}

/**
 * lookup method
 *
 * For use in ajax select boxes and the likes
 *
 * @TODO WIP
 * @param string $input ''
 * @return void
 * @access public
 */
	public function lookup($input = '') {
		$C =& $this->Controller;
		if (!$input) {
			$input = $C->params['url']['q'];
		}
		if (!$input) {
			$C->autoRender = false;
			$C->output = '0';
			return;
		}
		$fields = !empty($C->settings['lookupFields'])?$C->settings['lookupFields']:
			$C->modelClass . '.' . $C->{$C->modelClass}->displayField;
		foreach ($fields as $field) {
			$conditions[$field . ' LIKE'] = $input . '%';
		}
		if (!$C->data = $C->{$C->modelClass}->find('list', compact('conditions'))) {
			$C->autoRender = false;
			$C->output = '0';
			return;
		}
		return $this->render('/elements/lookup_results');
	}

/**
 * Determine the conditions to apply based on either the POSTed filter conditions or the session
 * stored filter conditions, and/or any additional named parameter filters
 *
 * @TODO Rewrite to Use, or update and ticket, postConditions
 * @param string $alias the model to base the filter on
 * @param string $mode
 * @param array $ignore additional named params to ignore
 * @param array $filter initial filter conditions
 * @access public
 * @return $conditions array of conditions to apply
 */
	public function parseSearchFilter($alias = null, $mode = 'both', $ignore = array(), $filter = array()) {
		if (is_array($mode)) {
			extract (array_merge(array('mode' => 'both'), $mode));
		}
		$C =& $this->Controller;
		$mode = low($mode);
		if ($mode === 'post' || $mode === 'both' && $C->action != 'admin_multi_edit') {
			$C->set('filterOptions', $this->settings['filterOperators']);
			$filter = array();
			if ($C->data) {
				$operator = false;
				foreach ($C->data as $alias => $fields) {
					if (isset($C->$alias)) {
						$inst = $C->$alias;
					} elseif(isset($inst->{$C->modelClass}->$alias)) {
						$inst = $inst->{$C->modelClass}->$alias;
					} else {
						if ($alias === 'App' || is_numeric($alias[0])) {
							continue;
						}
						$inst = ClassRegistry::init($alias);
					}
					$i = 0;
					foreach ($fields as $field => $value) {
						$value = $fields[$field];
						$i++;
						if ($i % 2) {
							$field = str_replace('_type', '', $field);
							if (!$value) {
								if (!$C->data[$alias][$field]) {
									continue;
								} else {
									$value = 'equal';
								}
							}
							$operator = $operators[$value];
							if ($value === 'null') {
								$filter[$alias . '.' . $field] = null;
								$fields[$field] =  null;
							} elseif ($value === 'notNull') {
								$filter[$alias . '.' . $field . ' !='] = null;
								$fields[$field] =  null;
							} elseif (in_array($value, array('like', 'notLike')) && strpos('%', $C->data[$alias][$field]) === false) {
								$fields[$field] =  $fields[$field] . '%';
							}
						} elseif (!in_array($value, array(null, '', 'NOT NULL'))) {
							if (!$operator) {
								$C->data[$alias][$field . '_type'] = 'equal';
								$operator = '= ';
							}
							if ($operator === 'in') {
								$filter[$alias . '.' . $field] = explode(',', $value);
								foreach ($filter[$alias . '.' . $field] as $key => $val) {
									$filter[$alias . '.' . $field][$key] = trim($val);
								}
							} elseif (is_array($value)) {
								$value = $inst->deconstruct($field, $value);
								if ($value) {
									$filter[$alias . '.' . $field . ' ' . $operator] = $value;
								}
							} else {
								$filter[$alias . '.' . $field . ' ' . $operator] = $value;
							}
						}
					}
				}
				$this->Session->write($C->modelClass . '.filter', $filter);
				$this->Session->write($C->modelClass . '.filterForm', $C->data);
			} elseif ($this->Session->check($C->modelClass . '.filter')) {
				$filter = $this->Session->read($C->modelClass . '.filter');
			}
		}
		if ($mode === 'named' || $mode === 'both') {
			$ignore = array_merge($this->settings['filterIgnore'], $ignore);
			$filter = am($filter, $C->params['named']);
			foreach ($ignore as $ignore) {
				unset ($filter[$ignore]);
			}
		}
		$alias = $alias?$alias:$C->modelClass;
		$return = array();
		foreach ($filter as $field => $condition) {
			if (preg_match('@^\(.*\)$@', $condition)) {
				$condition = explode(',', trim($condition, '()'));
			}
			if (strpos($field, '.')) {
				list($_alias, $field) = explode('.', $field);
			} else {
				$_alias = $alias;
			}
			$_alias = Inflector::camelize($_alias);
			$field = Inflector::underscore($field);
			$return[$_alias . '.' . $field] = $condition;
		}
		if ($return) {
			$this->setFilterFlash($return);
		}
		return $return;
	}

/**
 * redirect method
 *
 * If it's an ajax request and the force parameter is true - render a js redirect
 *
 * @param mixed $url
 * @param mixed $code
 * @param mixed $exist
 * @param mixed $force
 * @return void
 * @access public
 */
	public function redirect($url, $code, $exist, $force) {
		$C =& $this->Controller;
		if (!$C) {
			return false;
		}
		if (($force || in_array($C->action, $C->postActions)) && !empty($C->RequestHandler) && $C->RequestHandler->isAjax()) {
			$C->set(compact('url'));
			$C->output = '';
			echo $C->render(false, 'force_redirect');
			$C->_stop();
		}
		return false;
	}

/**
 * setDefaultPageTitle method
 *
 * Derive a title, and query the page_titles.po file
 *
 * @return void
 * @access public
 */
	public function setDefaultPageTitle() {
		$C =& $this->Controller;
		if (empty ($C) || !empty($C->params['requested'])) {
			return;
		}
		$action = Inflector::humanize(str_replace('admin_', '', $C->action));
		$bits = array('prefix', 'plugin', 'name', 'action');
		$prefix = 'admin_';
		foreach ($bits as $i => &$bit) {
			if ($bit === 'name') {
				$bit = $C->name;
			} elseif (empty($C->params[$bit])) {
				unset($bits[$i]);
				continue;
			} else {
				$bit = $C->params[$bit];
			}
			if ($bit === 'prefix') {
				$prefix = Inflector::underscore($bit) . '_';
			}
			$bit = Inflector::humanize(Inflector::underscore(str_replace($prefix, '', $bit)));
		}
		if (!empty($C->params['plugin'])) {
			$pluginDomain = $C->params['plugin'] . '_';
		} else {
			$pluginDomain = '';
		}
		$C->set('title_for_layout', __d($pluginDomain . 'page_titles', implode($bits, ' :: '), true));
	}

/**
 * setFilterFlash method
 *
 * @param mixed $filters
 * @return void
 * @access public
 */
	public function setFilterFlash($filters) {
		$C =& $this->Controller;
		if (!$filters) {
			$C->Session->setFlash('No filter set');
			return;
		}
		$out = 'Filtering for:<br />';
		$currentFilters = array();
		foreach ($filters as $field => $filter) {
			if (is_array($filter)) {
				$filter = 'In ' . implode(', ', $filter);
			} elseif ($filter === null) {
				$currentFilters[] = Inflector::humanize($field) . ' IS NULL';
				continue;
			}
			if (preg_match('@_id$@', $filter)) {
				//$fModel =
			}
			$currentFilters[] = Inflector::humanize($field) . ' ' . $filter;
		}
		$C->Session->setFlash($out . implode(', <br />', $currentFilters));
	}

/**
 * setSelects method
 *
 * Interogate the main model and populate vars for select boxes
 *
 * @param array $params stub
 * @return void
 * @access public
 */
	public function setSelects($params = array()) {
		static $run = false;
		if ($run) {
			return;
		}
		$C =& $this->Controller;
		$modelClass =& $C->modelClass;
		$sets = array();
		if (isset($C->{$modelClass}->actsAs['Tree']) ||
			$C->{$modelClass}->actsAs && in_array('Tree', $C->{$modelClass}->actsAs)) {
			$key = 'parents';
			if (!(array_key_exists($key, $C->viewVars) || ($params && !in_array($key, $params)))) {
				if ($C->{$modelClass}->hasField('depth')) {
					$rows = $C->{$modelClass}->find('all', array(
						'fields' => array('id', $C->{$modelClass}->displayField, 'depth'),
						'order' => 'lft',
						'recursive' => -1
					));
					$values = array();
					foreach ($rows as $i => $row) {
						$row = $row[$modelClass];
						$values[$row['id']] = str_repeat('...', $row['depth']) .
							$row[$C->{$modelClass}->displayField];
					}
				} else {
					$values = $C->{$modelClass}->generateTreeList();
				}
				$values = array(__d('mi', 'No Parent', true)) + $values;
				$sets[$key] = $values;
			}
		}
		if ($C->{$modelClass}->Behaviors->attached('Enum')) {
			foreach ($C->{$modelClass}->enumFields() as $enumeratedField) {
				$key = Inflector::variable(Inflector::pluralize(preg_replace('/_id$/', '', $enumeratedField)));
				if (!(array_key_exists($key, $C->viewVars) || ($params && !in_array($key, $params)))) {
					$values = $C->{$modelClass}->enumValues($enumeratedField);
					$sets[$key] = $values;
				}
			}
		}
		foreach (array('hasOne', 'hasMany', 'belongsTo', 'hasAndBelongsToMany') as $type) {
			foreach (array_keys($C->{$modelClass}->$type) as $model) {
				if ($type === 'hasAndBelongsToMany') {
					$key = $C->{$modelClass}->{$type}[$model]['associationForeignKey'];
				} else {
					$key = $C->{$modelClass}->{$type}[$model]['foreignKey'];
				}
				$key = Inflector::variable(Inflector::pluralize(preg_replace('/_id$/', '', $key)));
				if (array_key_exists($key, $C->viewVars) || ($params && !in_array($key, $params))) {
					continue;
				}
				if (isset($C->{$modelClass}->$model->actsAs['Tree']) ||
					$C->{$modelClass}->$model->actsAs && in_array('Tree', $C->{$modelClass}->$model->actsAs)) {
					$values = $C->{$modelClass}->$model->generateTreeList();
				} else {
					$order = $C->{$modelClass}->$model->alias . '.' . $C->{$modelClass}->$model->displayField;
					$values = $C->{$modelClass}->$model->find('list', compact('order'));
				}
				$sets[$key] = $values;
			}
		}
		$C->set($sets);
		$run = true;
	}

/**
 * Should the current url be stored in history
 *
 * @param mixed $C Controller instance
 * @return bool
 * @access protected
 */
	protected function _storeHistory($C = null) {
		if (!$C) {
			$C = $this->Controller;
		}
		if (!$C || !empty($C->params['requested'])) {
			return false;
		}
		if (method_exists($C, '_storeHistory')) {
			$return = $C->_storeHistory();
		} else {
			$return = $this->settings['storeHistory'];
		}
		if (!$return) {
			return false;
		}
		if (empty($this->_here)) {
			$this->initializeBackData();
		}
		return true;
	}

/**
 * redirect method
 *
 * @param mixed $url
 * @param mixed $code null
 * @param mixed $thread null
 * @return void
 * @access protected
 */
	protected function _redirect($url, $code = null,  $thread = null) {
		if (!$thread)  {
			$thread = $this->_browseKey();
		}
		if ($this->_history) {
			$this->Session->write('history.' . $thread, $this->_history[$thread]);
		}
		if (!$this->Controller) {
			$this->Controller = new Controller();
		}
		return $this->Controller->redirect($url, $code);
	}

/**
 * normalizeUrl method
 *
 * @param mixed $url null
 * @return void
 * @access protected
 */
	protected function _normalizeUrl($url = null, $key = false) {
		if (is_string($url) && $this->webroot !== '/') {
			$url = preg_replace('@^' . $this->webroot . '@', '/', $url);
		}
		if (is_string($url) && $url && $url[0] === '/' && $this->settings['usingSubdomains']) {
		   $url = 'http://' . env('HTTP_HOST') . $url;
		}

		if ($key && is_string($url)) {
			return preg_replace('@(?<!:)/+@', '/', $url);
		}
		if (class_exists('SeoComponent')) {
			$url = SeoComponent::url($url);
			if (!$key) {
				if ($this->webroot !== '/') {
					return preg_replace('@^' . $this->webroot . '@', '/', $url);
				}
				return preg_replace('@(?<!:)/+@', '/', $url);
			}
		}
		if ($key) {
			$url = Router::normalize($url);
		} elseif (is_array($url)) {
			$url = Router::url($url);
		}
		if ($this->webroot !== '/') {
			$url = preg_replace('@^' . $this->webroot . '@', '/', $url);
		}
		return preg_replace('@(?<!:)/+@', '/', $url);
	}
}