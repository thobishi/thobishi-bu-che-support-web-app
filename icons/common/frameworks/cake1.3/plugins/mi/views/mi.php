<?php
/**
 * Short description for mi.php
 *
 * Long description for mi.php
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
 * @subpackage    mi.views
 * @since         v 1.0
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * MiView class
 *
 * @uses          View
 * @package       mi
 * @subpackage    mi.views
 */
class MiView extends View {

/**
 * construct method
 *
 * @param mixed $controller
 * @return void
 * @access private
 */
	public function __construct(&$controller) {
		parent::__construct($controller);
		$this->theme =& $controller->theme;
	}

/**
 * Undo Mi logic when reporting an error for a missing view file
 *
 * @param mixed $name
 * @param array $params array()
 * @param bool $loadHelpers false
 * @return void
 * @access public
 */
	public function element($name, $params = array(), $loadHelpers = false) {
		$return = parent::element($name, $params, $loadHelpers);
		if (strpos($return, 'Not Found:') === 0) {
			$plugin = null;
			if (isset($params['plugin'])) {
				$plugin = $params['plugin'];
			}

			if (isset($this->plugin) && !$plugin) {
				$plugin = $this->plugin;
			}

			$paths = $this->_paths($plugin);
			$path = array_pop($paths);
			while ($paths && strpos($path, DS . 'locale' . DS)) {
				$path = array_pop($paths);
			}
			$file = $path . 'elements' . DS . $name . $this->ext;
			$trace = Debugger::trace();
			return "Not Found: " . $file . $trace;
		}
		return $return;
	}

/**
 * Overriden to permit multiple row same-model forms (admin_multi_edit) to work
 * Slightly none-DRY to prevent any changes to the cake method from affecting
 * normal form elements
 *
 * @return array An array containing the identity elements of an entity
 */
	public function entity() {
		$assoc = ($this->association) ? $this->association : $this->model;
		if (!empty($this->entityPath)) {
			$path = explode('.', $this->entityPath);
			$count = count($path);
			if  ($count !== 3) {
				return parent::entity();
			}
			return Set::filter($path);
		}
		return parent::entity();
	}

/**
 * Overriden to prevent view processing from being forced to the View class
 *
 * @param string $action Name of action to render for
 * @param string $layout Layout to use
 * @param string $file Custom filename for view
 * @return string Rendered Element
 */
	public function render($action = null, $layout = null, $file = null) {
		if ($this->hasRendered) {
			return true;
		}
		$out = null;

		if ($file != null) {
			$action = $file;
		}

		if ($action !== false && $viewFileName = $this->_getViewFileName($action)) {
			$out = $this->_render($viewFileName, $this->viewVars);
		}

		if ($layout === null) {
			$layout = $this->layout;
		}

		if ($out !== false) {
			if ($layout && $this->autoLayout) {
				$out = $this->renderLayout($out, $layout);

/* AD7six start
if (isset($this->loaded['cache']) && (($this->cacheAction != false)) && (Configure::read('Cache.check') === true)) {
 */
				if (isset($this->loaded['cache'])) {

/* AD7six end */
					$replace = array('<cake:nocache>', '</cake:nocache>');
					$out = str_replace($replace, '', $out);
				}
			}
			$this->hasRendered = true;
		} else {
			$out = $this->_render($viewFileName, $this->viewVars);
			trigger_error(sprintf(__d('mi', "Error in view %1$s, got: <blockquote>%2$s</blockquote>", true), $viewFileName, $out), E_USER_ERROR);
		}
		return $out;
	}

/**
 * loadHelpers method
 *
 * For any MiHelper (except miCache) make it available in the views as $helper
 * Reference: http://bin.cakephp.org/saved/40115 Thankye ADmad
 *
 * @param mixed $loaded
 * @param mixed $helpers
 * @param mixed $parent null
 * @return void
 * @access protected
 */
	public function &_loadHelpers(&$loaded, $helpers, $parent = null) {
		if (!$parent) {
			if (in_array('Paginator', $helpers)) {
				$helpers[] = 'Mi.MiPaginator';
			}
		}
		$loaded = parent::_loadHelpers($loaded, $helpers, $parent);
		if (!$parent) {
			foreach(array_keys($loaded) as $helper) {
				if ($helper === 'Mi.MiCache') {
					continue;
				}
				if (preg_match('/^Mi([A-Z].*)/', $helper, $match)) {
					$loaded[$match[1]] = $loaded[$helper];
					unset($loaded[$helper]);
				}
			}
		}
		return $loaded;
	}

/**
 * Return all possible paths to find view files in order
 *
 * Check for plugin/locale/<locale>/views/.../foo.ctp if Config.language has been set
 *
 * @TODO visibility
 * @param string $plugin ''
 * @param bool $cached true
 * @return array paths
 * @access protected
 */
	public function _paths($plugin = '', $cached = true) {
		if (!class_exists('MiCache')) {
			App::import('Vendor', 'Mi.MiCache');
		}
		$plugin = (string)$plugin;
		$theme = (string)$this->theme;
		return MiCache::mi('paths', 'view', compact('plugin', 'theme'));
	}
}