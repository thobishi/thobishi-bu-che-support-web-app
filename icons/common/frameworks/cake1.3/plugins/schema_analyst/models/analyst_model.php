<?php
/**
 * AnalystModel
 *
 * Part of the SchemaAnalyst plugin.
 *
 * @author Frank de Graaf (Phally)
 * @license MIT license
 * @link http://github.com/phally
 */
class AnalystModel extends SchemaAnalystAppModel {

/**
 * Find types to override.
 * @var array
 * @access protected
 */
	protected $methods = array('all');

/**
 * AnalystModel::findAll()
 *
 * Overrides find('all') behavior.
 *
 * Returns all models available to the application.
 *
 * @return	array		List of models.
 * @access	protected
 */
	protected function findAll() {
		$models = array();
		
		foreach (App::objects('model', null, false) as $model) {
			$models[]  = array($this->alias => array('name' => $model,'plugin' => false));
		}
		
		$plugins = App::objects('plugin');
		$pluginpaths = App::path('plugins');
		
		foreach ($plugins as $plugin) {
			$paths = array();
			
			foreach($pluginpaths as $pluginpath) {
				$paths[] = $pluginpath . Inflector::underscore($plugin) . DS . 'models' . DS;
			}
			
			foreach(App::objects('model', $paths, false) as $model) {
				$models[] = array($this->alias => array('name' => $model, 'plugin' => $plugin));
			}
		}
		return $models;
	}

}
?>