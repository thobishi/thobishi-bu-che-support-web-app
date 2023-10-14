<?php

class OctoLogsAppModel extends AppModel {
	public function afterFind($results, $primary = true) {
		if($primary == true) {
			foreach($results as &$result) {
				foreach($result as &$data) {
					array_walk($data, array($this, 'jsonDecode'));
				}
			}
		}
		
		return parent::afterFind($results);
	}
	
	protected function jsonDecode(&$value) {
		if(is_array($value)) {
			array_walk($value, array($this, 'jsonDecode'));
		}
		else {
			$jsonValue = json_decode($value, true);
			if(is_array($jsonValue)) {
				$value = $jsonValue;
			}
		}
	}
}