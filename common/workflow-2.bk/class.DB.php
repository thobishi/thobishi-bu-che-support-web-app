<?php

class DB {

	protected static $_instance = null;

	private $__dbSettings = array();

	private $__Connection = null;

	private $__AuditLog = null;

	private $__sqlLog = array();

	private $__fieldCache = array();

	private $__queryCache = array();

	public $lastError = array(0 => '', 1 => '', 2 => '');

	public static function getInstance() {
		if (empty(self::$_instance)) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}

/**
 * @throws Exception
 */
	protected function __construct() {
		if (!empty($this->_instance)) {
			throw new Exception('Thou shalt not construct that which is unconstructable!');
		}

		$this->__dbSettings['server'] = DB_SERVER;
		$this->__dbSettings['database'] = DB_DATABASE;
		$this->__dbSettings['user'] = DB_USER;
		$this->__dbSettings['password'] = DB_PASSWD;

		$this->__connect();

		$this->__AuditLog = AuditLog::getInstance($this);
	}

	protected function __clone() {
		//Me not like clones! Me smash clones!
	}

	private function __connect() {
		$dqn = "mysql:host={$this->__dbSettings['server']};dbname={$this->__dbSettings['database']}";

		try {
			$this->__Connection = new PDO($dqn, $this->__dbSettings['user'], $this->__dbSettings['password']);
		}
		catch (PDOException $e) {
			$this->__errorEmail("ERROR: {$this->__dbSettings['database']}", "{$this->__dbSettings['database']} database down\n\nMySQL: " . $e->getMessage(), $this->__dbSettings['database']);
			die("Data Base Connection down");
		}
	}

	private function __errorEmail($subject, $message, $sender) {
		$mail = new PHPMailer();

		$mail->From = WRK_SYSTEM_EMAIL;
		$mail->FromName = "$sender System";

		$mail->Subject = $subject;

		$mail->Host = SMTP_SERVER;
		$mail->Mailer = "smtp";
		$mail->WordWrap = 75;

		$mail->Body = $message;

		$mail->IsSMTP(true);
		$mail->IsHTML(false);
		$mail->AddAddress(WRK_SYSTEM_EMAIL);
		$mail->Send();
		$mail->ClearAddresses();
		$mail->ClearAttachments();
	}

	public function __call($name, $arguments) {
		return call_user_func_array(array($this->__Connection, $name), $arguments);
	}

	public function query($sql, $parameters = array(), $ignoreCache = false) {
		$sqlLog = array(
			'sql' => $sql,
			'parameters' => $parameters,
			'rows' => 0,
			'error' => array(0 => '', 1 => '', 2 => ''),
			'cached' => false
		);
// print_r($sqlLog);exit;
		if (empty($this->__queryCache[$sql]) || $ignoreCache) {
			$statement = $this->__Connection->prepare($sql);

			if (!$ignoreCache) {
				$this->__queryCache[$sql] = $statement;
			}
		} else {
			$statement = $this->__queryCache[$sql];
			$sqlLog['cached'] = true;
			$statement->closeCursor();
		}
		
		if (!$statement->execute($parameters)) {
			$this->lastError = $statement->errorInfo();
			$sqlLog['error'] = $this->lastError;
			$this->__sqlLog[] = $sqlLog;
			return false;
		}
// echo 'statement';print_r($statement);exit;
		$this->lastError = array(0 => '', 1 => '', 2 => '');

		$sqlLog['rows'] = $statement->rowCount();
		$this->__sqlLog[] = $sqlLog;

		return $statement;
	}

	public function getValueFromTable($table, $field, $key, $ret){
		$SQL = "SELECT $ret FROM `$table` WHERE `$field` = :key";
		$rs = $this->query($SQL, compact('key'));

		if (!$rs) {
			$this->__AuditLog->writeLogInfo(10, "SQL-GETVAL", $SQL."  --> " . $this->lastError[2], true);
		} else {
			if ($row = $rs->fetch()) {
				return ($row[0]);
			}
		}
		return '';
	}

	public function makeArrayFromSQL($SQL, $key="", $val=""){
		$arr = array();
		$rs = $this->query($SQL);
		$k = 0;					 // yse first and second coloumn from result
		$v = 1;
		if ($key!="") {  // if key was spec, use the key and value
			$k = $key;
			$v = $val;
		}
		while ($row = $rs->fetch()) {
			$arr[$row[$k]] = $row[$v];
		}
		return $arr;
	}

	public function getDBsettingsValue($key) {
		// 20070616 (Diederik): There used to be a check if mayUseSettings
		$SQL = "SELECT `s_value` FROM `".WRK_TABLE_SETTINGS."` WHERE `s_key`=:key";

		$RS = $this->query($SQL, compact('key')) or die($SQL);

		if ($ROW = $RS->fetch()) {
			$ret = $ROW[0];
			$strFunc = "@SEL:";
			// Diederik 20041019 - Swapping should only be with fields starting in
			//   $strFunc and not every field that contains a comma
			if (!strncmp($ROW[0], $strFunc, strlen($strFunc))) {
				$r_user = explode(",", substr($ROW[0],strlen($strFunc)));
				$ret = $r_user[0];
				if (count($r_user) > 1) {
					$new_user = array_shift($r_user);
					array_push($r_user, $new_user);
					$this->setDBsettingsValue($key, $strFunc.implode(",", $r_user));
				}
			}
			return ($ret);
		} else {
			$error = "The following settings value could not be found: '".$key."'";
			$this->__AuditLog->writeLogInfo(3, "SETTINGS", $error);
			die ("Setting not found: $error");
		}
	}

	public function showSqlLog() {
		echo '<p>' . count($this->__sqlLog) . ' queries run</p>';
		echo '<table class="table table-stripped">';
		echo '<thead><tr><th>Number</th><th>Query</th><th>Affected</th><th>Error</th></tr></thead>';
		echo '<tbody>';
		foreach ($this->__sqlLog as $key => $entry) {
			echo '<tr' . ($entry['cached'] ? ' class="success"' : '') . '>';
			echo '<td>' . ($key + 1) . '</td>';
			echo '<td>' . $entry['sql'] . '</td>';
			echo '<td>' . $entry['rows'] . '</td>';
			echo '<td>' . $entry['error'][2] . '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
	}

	public function getFieldDetails($tableName) {
		if (isset($this->__fieldCache[$tableName])) {
			return $this->__fieldCache[$tableName];
		}

		$SQL = 'SHOW FULL COLUMNS FROM ' . $tableName;
		$fields = $this->query($SQL)->fetchAll();
		$this->__fieldCache[$tableName] = $fields;

		return $fields;
	}

	public function setValueInTable($table, $keyField, $keyValue, $chField, $chValue, $view = 0){
		// W are not allowed to change whe we are in view mode
		if ($view == 1) return;

		$SQL = "UPDATE `$table` ".
					 "SET $chField = :chValue ".
					 "WHERE `$keyField` = :keyValue";

		if(! $this->query($SQL, compact('chValue', 'keyValue')) ){
			$this->__AuditLog->writeLogInfo(10, "SQL-SETVAL", $SQL."  --> ".$this->lastError[2], true);
		}
	}

	public function createSQL ($cols, $table, $order="", $where="", $limit="", $left="") {
		$SQL = "SELECT $cols FROM $table";
		$SQL.= (($left>"")?(" LEFT JOIN ".$left):(""));
		$SQL.= (($where>"")?(" WHERE ".$where):(""));
		$SQL.= (($order>"")?(" ORDER BY ".$order):(""));
		$SQL.= (($limit>"")?(" LIMIT 0,".$limit):(""));
		return ($SQL);
	}
	
	public function customArrRequest($col,$table,$order="",$where){
		$sql= $this->createSQL($col,$table,$order,$where);
		$rs = $this->query($sql);
		$row = $rs->fetch();
		return $row;
	}
	
	function getMultipleFieldsFromTable ($table, $keyName, $keyVal, $options="") {
		$SQL = "SELECT * FROM ".$table." WHERE ".$keyName."='".$keyVal."'";
		if ($options > "") {
			$SQL .= $options;
		}
		$RS = $this->query($SQL);
		$fieldArr = array();
		while ($row = $RS->fetch()) {
			array_push($fieldArr, $row);
		}
		return $fieldArr;
	}
}