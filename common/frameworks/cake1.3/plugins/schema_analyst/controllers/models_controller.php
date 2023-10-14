<?php
/**
 * ModelsController
 *
 * Part of the SchemaAnalyst plugin.
 *
 * @author Frank de Graaf (Phally)
 * @license MIT license
 * @link http://github.com/phally
 */
class ModelsController extends SchemaAnalystAppController {

/**
 * This controller uses a different model.
 * @var array
 * @access public
 */
	public $uses = array('SchemaAnalyst.AnalystModel');

/**
 * ModelsController::admin_index()
 *
 * Simple overview with the available models.
 *
 * @return	void
 * @access	public
 */
	public function admin_index() {
		$this->set('models', $this->AnalystModel->find('all'));
	}

/**
 * ModelsController::admin_check()
 *
 * Analysis overview for a single model.
 *
 * @param	string	$model	Name of the model to analyse.
 * @param	string	$plugin	Name of the plugin that contains the model.	
 * @return	void
 * @access	public
 */
	public function admin_check($model = null, $plugin = null) {
		$plugin = ($plugin) ? Inflector::camelize($plugin) . '.' : '';
		$name = $plugin . Inflector::camelize($model);
		$model = ClassRegistry::init($name);

		if ($model && $model instanceof Model && $model->useTable) {
			$model->Behaviors->attach('SchemaAnalyst.Analyzer');
			if ($analysis = $model->analyse()) {
				$table = $model->table;
				$this->set('analysis', $analysis);
				$this->set(compact('name', 'table', 'analysis'));
				return null;
			}
		}

		$this->Session->setFlash(__('Can\'t read table.', true));
		$this->redirect(array('action' => 'index'));
	}

}
?>