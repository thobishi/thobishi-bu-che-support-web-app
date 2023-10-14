<?php

class AuditLogBehavior extends ModelBehavior {
	public $settings = array();
	private $defaults = array(
		'UserModel' => 'User'	,
		'order' => array('AuditLog.created' => 'ASC'),
		'history' => array()
	);
	private $currentValue = array();
	private $AuditLog = null;
	private $userId = null;
	
	public function setUser(&$Model, $userId) {
		$this->userId = $userId;
	}
	
	public function setup(&$Model, $settings) {
		if(!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = $this->defaults;
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $settings);
		
		$Model->bindModel(array(
			'hasMany' => array(
				'AuditLog' => array(
					'className' => 'OctoLogs.AuditLog',
					'foreignKey' => 'foreign_key',
					'conditions' => array(
						'AuditLog.model' => $Model->name
					),
					'order' => $this->settings[$Model->alias]['order']
				)
			)
		), true);
		
		if($this->AuditLog === null) {
			$this->AuditLog = ClassRegistry::init('OctoLogs.AuditLog');
			$this->AuditLog->bindModel(array(
				'belongsTo' => array(
					'CreatedBy' => array(
						'className' => $this->settings[$Model->alias]['UserModel'],
						'foreignKey' => 'created_by'
					)
				)
			), true);
		}
	}
	
	public function beforeSave(&$Model) {
		if(!empty($Model->data[$Model->alias][$Model->primaryKey])) {
			$this->currentValue[$Model->alias] = end($Model->find('first', array(
				'conditions' => array(
					$Model->alias . '.' . $Model->primaryKey => $Model->data[$Model->alias][$Model->primaryKey]
				)
			)));
		}
		elseif(isset($this->currentValue[$Model->alias])) {
			unset($this->currentValue[$Model->alias]);
		}
	}
	
	public function afterSave(&$Model, $created = false) {
		if($created === false && !empty($this->currentValue[$Model->alias])) {
			$saveData['AuditLog'] = array(
				'model' => $Model->name,
				'foreign_key' => $this->currentValue[$Model->alias][$Model->primaryKey],
				'data' => json_encode($this->currentValue[$Model->alias]),
				'created_by' => $this->userId
			);
			
			$this->AuditLog->create();
			$this->AuditLog->save($saveData);
		}
		
		unset($this->currentValue[$Model->alias]);
	}
	
	public function history(&$Model, $id) {
		$options = $this->settings[$Model->alias]['history'];
		
		$options['contain']['AuditLog'] = array('CreatedBy', 'order' => array('AuditLog.created' => 'ASC'));
		$options['conditions'][$Model->alias.'.'.$Model->primaryKey] = $id;
		
		$item = $Model->find('first', $options);
		
		if($item === false) {
			throw new OutOfBoundsException(__($this->settings[$Model->alias]['history']['exceptionMessage'], true));
		}
		
		
		$lastUpdate = $item[$Model->alias];
		$history = array();
		$item['AuditLog'][] = array(
			'data' => $lastUpdate
		);
		
		foreach($item['AuditLog'] as $key => $logItem) {
			if(isset($item['AuditLog'][$key+1])) {
				$nextItem = $item['AuditLog'][$key+1];
				$arrayDiff = Set::diff($logItem['data'], $nextItem['data']);
				
				$history[strtotime($logItem['created'])] = array(
					'CreatedBy' => $logItem['CreatedBy'],
					'changes' => array_keys($arrayDiff),
					'this' => $logItem['data'],
					'next' => $nextItem['data']
				);
			}
		}
		
		return array(
			'current' => $item,
			'history' => $history
		);
	}
}
