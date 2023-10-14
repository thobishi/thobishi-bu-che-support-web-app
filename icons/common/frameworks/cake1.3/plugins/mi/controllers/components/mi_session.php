<?php
/**
 * Short description for mi_session.php
 *
 * Long description for mi_session.php
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
 * MiSessionComponent class
 *
 * @uses          Object
 * @package       mi
 * @subpackage    mi.controllers.components
 */
class MiSessionComponent extends Overloadable {

/**
 * name property
 *
 * Cheating
 *
 * @var string 'Session'
 * @access public
 */
	public $name = 'Session';

/**
 * components property
 *
 * @var array
 * @access public
 */
	public $components = array('Session');

/**
 * settings property
 *
 * @var array
 * @access public
 */
	public $settings = array();

/**
 * supressedFlashMessages
 *
 * flash messages generate whilst redirecting (or, due to processing order just after having been redirected)
 * are temporarily stored here. These messages are processed in beforeRender - or silently dropped
 *
 * @var array
 * @access public
 */
	public $supressedFlashMessages = array();

/**
 * initialize method
 *
 * Replace pointer for Session component to this component
 *
 * @param mixed $Controller
 * @param array $config
 * @access public
 * @return void
 */
	public function initialize(&$Controller, $config = array()) {
		$this->settings = array_merge($this->settings, $config);
		$this->Controller =& $Controller;
		$Controller->Session =& $this;
	}

/**
 * beforeRedirect method
 *
 * Write to the session that we are redirecting.
 *
 * @param mixed $Controller
 * @param mixed $url
 * @param mixed $status
 * @param mixed $exit
 * @return void
 * @access public
 */
	public function beforeRedirect(&$Controller, $url, $status, $exit) {
		$count = (int)$this->Session->read('MiSession.redirecting');
		if ($count === 0) {
			$this->Session->delete('MiSession.redirectUrls');
		}
		$this->Session->write('MiSession.redirectUrls.' . $count, Router::url($url));
		if ($count > 10) {
			$this->Session->delete('MiSession.redirecting');
			if (Configure::read()) {
				$redirects = "<br />" .  implode($this->Session->read('MiSession.redirectUrls'), "\n<br />");
				$this->Session->setFlash('A redirect loop was detected - aborting. Redirects:' . $redirects);
			} else {
				$redirects = "\n\t" .  implode($this->Session->read('MiSession.redirectUrls'), "\n\t");
				$this->log('A redirect loop was detected - aborting. Redirects:' . $redirects);
			}
			$this->Session->delete('MiSession.redirectUrls');
			return '/';
		}
		$this->Session->write('MiSession.redirecting',  $count + 1);
		return $url;
	}

/**
 * beforeRender method
 *
 * Delete the session redirect marker, so that flash messages are re-enabled
 *
 * @return void
 * @access public
 */
	public function beforeRender() {
		$this->Session->delete('MiSession.redirecting');
		if ($this->supressedFlashMessages) {
			foreach($this->supressedFlashMessages as $row) {
				$this->setFlash($row['message'], $row['element'], $row['params'], $row['key']);
			}
		}
	}

/**
 * setFlash method
 *
 * Allow multiple flash messages, by default using the message as a key to avoid duplicates
 *
 * Check for the MiSession.redirecting var to know if we are in the middle of a chain of redirects
 * and supress any generated flash messages from the intermediary pages. For example
 * /posts/index/page:2 (rendered)
 * /posts/view/1 (rendered)
 * /posts/delete/1 (user clicked, redirects)
 * /posts/view/1 (redirect)*
 * /posts/index/page:2 (rendered)
 *
 * The posts view call will (usually) generate a message "Post 2 could not be displayed" - if the session
 * variable is set this message is not displayed, the user only sees "Post 2 deleted"
 *
 * @param mixed $message
 * @param string $element 'default'
 * @param array $params array()
 * @param mixed $key null
 * @param bool $force false
 * @return void
 * @access public
 */
	public function setFlash($message, $element = 'default', $params = array(), $key = null, $force = false) {
		if (!$force && $this->Session->read('MiSession.redirecting')) {
			$this->supressedFlashMessages[] = compact('message', 'element', 'params', 'key');
			if (Configure::read()) {
				AppController::log('MiSession flash message supressed: ' . $message, 'redirect');
			}
			return;
		}
		if ($key == null) {
			$key = md5($message);
			if (is_numeric($key[0])) {
				$key[0] = 'x';
			}
		}
		$this->Session->setFlash($message, $element, $params, $key);
	}

/**
 * call__ method
 *
 * Pass any undefined method calls directly to the real Session component
 *
 * @param mixed $method
 * @param mixed $params
 * @access public
 * @return void
 */
	public function call__($method, $params) {
		return call_user_func_array(array(&$this->Session, $method), $params);
	}
}