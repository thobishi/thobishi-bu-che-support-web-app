<?php

class AuditLog {

	protected static $_instance = null;

	private $__db = null;

	private $__email = null;

	private $__logStatements = array();

	public static function getInstance($db) {
		if (empty(self::$_instance)) {
			self::$_instance = new self($db);
		}
		return self::$_instance;
	}

/**
 * @throws Exception
 */
	protected function __construct($db) {
		if (!empty($this->_instance)) {
			throw new Exception('Thou shalt not construct that which is unconstructable!');
		}

		$this->__db = $db;
		$this->__email = Email::getInstance($db, $this);

		$this->__prepareLogStatements();
	}

	protected function __clone() {
		//Me not like clones! Me smash clones!
	}

	private function __prepareLogStatements() {
		$this->__logStatements['logInfo'] = $this->__prepareWriteLogInfoStatement();
		$this->__logStatements['auditLog'] = $this->__prepareWriteAuditLogStatement();
	}

	private function __prepareWriteLogInfoStatement() {
		$SQL = "INSERT INTO `workflow_log_file` VALUES (NULL, 100, 113, 192.168.1.2, 0, NOW(), 'home', 'SETTINGS', 'the following')";		

		return $this->__db->prepare($SQL);
	}

	private function __prepareWriteAuditLogStatement() {
		$SQL = <<<sqlInsert
					INSERT INTO `workflow_audit_trail`
					(`active_processes_ref`, `application_ref`, `institution_ref`,`user_ref`, `reacc_application_ref`,`session_id`, `ip_number`,
					`process_ref`,`process_desc`,`work_flows_ref`,`audit_subject`,`audit_text`,`audit_level`,
					`date_updated`,`workflow_settings`)
					VALUES (:id, :app_ref, :ins_ref, :usr_ref, :reacc_app_ref, :ses_id,:REMOTE_ADDR,
					:prc_ref, :prc_desc, :wkf_ref, :subject, :descr, :aud_lev,
					:aud_date,:wkf_set)
sqlInsert;
		return $this->__db->prepare($SQL);
	}

	/*
	 * Louwtjie: 2004-08-02
	 * Function for writing debug information (log file) into the DB.
	*/
	public function writeLogInfo($level, $subject, $log_var, $mail=false) {
		if (Settings::get('log_level') >= $level) {
			$proc = $this->__db->getValueFromTable("active_processes", "active_processes_id", Settings::get('active_processes_id'), "processes_ref");
// echo $proc;exit;
			$params = array(
				'level' => $level,
				'currentUserId' => Settings::get('currentUserID'),
				'remoteAddress' => $_SERVER['REMOTE_ADDR'],
				'proc' => empty($proc) ? 0 : $proc,
				'template' => Settings::get('template'),
				'subject' => $subject
			);

			if (is_string($log_var)) {
				$params['log_var'] = $log_var;
			}

			if (is_array($log_var)) {
				$params['log_var'] = '';
				foreach ($log_var as $key=>$value) {
					if ($key == "WORKFLOW_SETTINGS") {
						$workflows = array();
						$workflows = explode("&", $value);
						if (count($workflows) > 0) {
							foreach ($workflows AS $k=>$v) {
								if (!(stristr($v, "LOGIC_SET"))) {
									$params['log_var'] .= $k.": ".$v."\n";
								}
							}
						}
					} else {
						$params['log_var'] .= $key.": ".$value."\n";
					}
				}
			}
					// print_r($params);
		/* 	if (!$this->__logStatements['logInfo']->execute($params)) {
				die("<br><br>Cannot write to log file: " . $this->__logStatements['logInfo']->queryString);
			} */

			if ($mail) {
				$this->__email->misMailByName(WRK_SYSTEM_EMAIL, "(".CONFIG.") error report", "ID: " . $this->__db->lastInsertId() . "\n" . $log_var, "", "Error log");
			}
		}
	}

	// Robin: 2006-11-16
	// Audit Record written AFTER any insert or update to active processes. That's why getting info from table instead of $this.
	public function writeAuditTrail($id, $subject, $descr){
		if ($id != 0){
			$SQL = "SELECT * FROM active_processes WHERE active_processes_id = :id";
			$RS = $this->__db->query($SQL, compact('id'));
			$row = $RS->fetch();
			$params = compact('id', 'subject', 'descr');

			$params['prc_ref'] = isset($row["process_ref"]) ? $row["process_ref"] : Settings::get('flowID');
			$params['aud_lev'] = $this->__db->getValueFromTable("processes", "processes_id", $params['prc_ref'], "audit_level");

			if (Settings::get('audit_level') >= $params['aud_lev']) {
				$params['prc_desc'] = $this->__db->getValueFromTable("processes", "processes_id", $params['prc_ref'], "processes_desc");
				$params['wkf_ref'] = isset($row["work_flows_ref"]) ? $row["work_flows_ref"] : Settings::get('workFlowID');
				$params['usr_ref'] = isset($row["user_ref"]) ? $row["user_ref"] : Settings::get('currentUserID');
				$params['wkf_set'] = isset($row["workflow_settings"]) ? $row["workflow_settings"] : '';
				$params['aud_date'] = date("Y-m-d G:i:s");
				$workFlow_settings = Settings::get('workFlow_settings');
				$params['app_ref'] = isset($workFlow_settings['DBINF_Institutions_application___application_id']) ? $workFlow_settings['DBINF_Institutions_application___application_id'] :'';
				$params['reacc_app_ref'] = isset($workFlow_settings['DBINF_Institutions_application_reaccreditation___Institutions_application_reaccreditation_id']) ? $workFlow_settings['DBINF_Institutions_application_reaccreditation___Institutions_application_reaccreditation_id'] :'';
				$params['ins_ref'] = isset($workFlow_settings['DBINF_HEInstitution___HEI_id']) ? $workFlow_settings['DBINF_HEInstitution___HEI_id'] :'';

				if ($params['reacc_app_ref'] > 0){
					$params['ins_ref'] = $this->__db->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reacc_app_ref, "institution_ref");
				}

				$params['ses_id'] = session_id();
				$params['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
				
				$result = $this->__logStatements['auditLog']->execute($params);
			}
		}
	}
}