<?php
/**
 * AnalyzerBehavior
 *
 * Part of the SchemaAnalyst plugin.
 *
 * @author Frank de Graaf (Phally)
 * @license MIT license
 * @link http://github.com/phally
 */
class AnalyzerBehavior extends ModelBehavior {

/**
 * AnalyzerBehavior::analyse()
 *
 * Returns detailed information about a table.
 *
 * @param	object	$model	Model instance, passed automatically by CakePHP.
 * @return	array			Information about the field lengths, charset and indexes.
 * @access	public
 */
	public function analyse($model) {
		if (!$lengths = $this->lengths($model)) {
			return false;
		}
		$charset = $this->charset($model);
		return compact('lengths', 'charset');
	}

/**
 * AnalyzerBehavior::lengths()
 *
 * Returns information about table field lengths.
 *
 * @param	object	$model	Model instance.
 * @return	array			Information about the field lengths.
 * @access	private
 */
	private function lengths($model) {
		$schema = $model->schema();

		$model->virtualFields = Set::combine(
			array_map(
				array($this, 'assembleLengthsField'), 
				array_keys($schema)
			), 
			'/field', 
			'/query'
		);

		$data = array_combine(
			array_keys($schema),
			array_values(current(
				$model->find('first', array(
					'fields' => array_keys($model->virtualFields),
					'recursive' => -1,
					'callbacks' => false
				))
			))
		);

		$lengths = array();
		foreach ($schema as $field => $properties) {
			$entry = array(
				'name' => $field,
				'primary' => (isset($properties['key']) && $properties['key'] == 'primary'),
				'type' => $properties['type'],
				'data' => $data[$field] ? $data[$field] : 0,
				'limit' => $properties['length'] ? $properties['length'] : 0,
				'status' => 'ok',
				'message' => false
			);

			if (!in_array($entry['type'], array('datetime', 'text', 'boolean'))) {
				if ($entry['data'] < $entry['limit'] - 10 && !$entry['primary']) {
					$entry['status'] = 'big';
					$entry['message'] = __('This field is too big for what it contains.', true);
				} elseif($entry['data'] == $entry['limit']) {
					$entry['status'] = 'caution';
					$entry['message'] = __('The values fit exactly in this field. Check if you don\'t loose data.', true);
				}
			} else {
				$entry['limit'] = false;
			}

			$lengths[] = $entry;
		}

		return $lengths;
	}

/**
 * AnalyzerBehavior::assembleLengthsField()
 *
 * Callback function which returns a formatted
 * entry for Model::$virtualFields.
 *
 * @param	string	$field	Name of the field to get the maxlength of.
 * @return	array			Model::$virtualFields entry.
 * @access	private
 */
	private function assembleLengthsField($field) {
		return array(
			'field' => 'max_length_' . $field, 
			'query' => 'MAX(LENGTH(' . $field . '))'
		);
	}

/**
 * AnalyzerBehavior::charset()
 *
 * Returns information about table and connection charset.
 *
 * @param	object	$model	Model instance.
 * @return	array			Information about the used charsets.
 * @access	private
 */
	private function charset($model) {
		$app = strtolower(str_replace('-', '', Configure::read('App.encoding')));
		$table = $model->getDataSource()->readTableParameters($model->table);
		$connection = $model->getDataSource()->getEncoding();

		$results = array(
			'app' => $app,
			'table' => $table,
			'connection' => $connection,
			'table_status' => ($app != $table['charset']) ? 'caution' : 'ok',
			'table_message' => false,
			'connection_status' => ($app != $connection) ? 'caution' : 'ok',
			'connection_message' => false
		);

		$message = __('Charset is different from the App.encoding, double check your values.', true);
		if ($results['table_status'] == 'caution') {
			$results['table_message'] = $message;
		}
		if ($results['connection_status'] == 'caution') {
			$results['connection_message'] = $message;
		}

		return $results;	
	}
}
?>