<?php

// Required contacts
if (! defined ('WRK_TABLE_SETTINGS') ) die ("ERROR: Settings table not defined.");

if (	!defined ('DB_SERVER') ||
			!defined ('DB_DATABASE') ||
			!defined ('DB_USER') ||
			!defined ('DB_PASSWD')
   ) die ("ERROR: Connection not defined.");

class dbConnect {
	var $conn, $DBserver, $DBname, $DBuser, $DBpassw;

	function dbConnect() {
		$this->DBserver = DB_SERVER;
		$this->DBname = DB_DATABASE;
 		$this->DBuser = DB_USER;
 		$this->DBpassw = DB_PASSWD;

		$this->conn = mysqli_connect($this->DBserver, $this->DBuser, $this->DBpassw);
	       if (!$this->conn) {
			$this->error_email ("ERROR: HEQC-online", "HEQC-online database down\n\nMySQL: ".mysqli_error () );
			die("Data Base Connection down");
		}

		$conectDB = mysqli_select_db($this->DBname);
	      	if (!$conectDB) {
			$this->error_email ("ERROR: HEQC-online", "HEQC-online database down\n\nMySQL: ".mysqli_error () );
			die("Data Base Connection down");
		}
	}

	function error_email ($subject, $message) {
		$mail = new PHPMailer();

		$mail->From = "heqc@che.ac.za";;
		$mail->FromName = "HEQC-online System";

		$mail->Subject = $subject;

		$mail->Host      = SMTP_SERVER;
		$mail->Mailer    = "smtp";
		$mail->WordWrap = 75;

		$mail->Body   = $message;

		$mail->IsSMTP (true);
		$mail->IsHTML (false);
		$mail->AddAddress (WRK_SYSTEM_EMAIL);
		$mail->Send();
		$mail->ClearAddresses();
		$mail->ClearAttachments();
	}

	function getDBsettingsValue($key) {
		// 20070616 (Diederik): There used to be a check if mayUseSettings
		$SQL = "SELECT `s_value` FROM `".WRK_TABLE_SETTINGS."` WHERE `s_key`='".$key."'";
		$RS = mysqli_query($SQL) or die($SQL);
		if ($ROW = mysqli_fetch_array($RS)) {
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
			$this->writeLogInfo(3, "SETTINGS", $error);
			die ("Setting not found: $error");
		}
	}

	function setDBsettingsValue($key, $value) {
		// 20070616 (Diederik): HERE WAS only if mayUseSettings TRUE else die with error message (from settings files)
		$SQL = "UPDATE `".WRK_TABLE_SETTINGS."` SET `s_value`='".$value."' WHERE `s_key`='".system_escape($key)."'";
		$RS = mysqli_query($SQL);
	}

	function makeArrayFromSQL($SQL, $key="", $val="") {

		$arr = array();
		$rs = mysqli_query($SQL);
		$k = 0;					 // yse first and second coloumn from result
		$v = 1;
		if ($key!="") {  // if key was spec, use the key and value
			$k = $key;
			$v = $val;
		}
		while ($row = mysqli_fetch_array($rs)) {
			$arr[$row[$k]] = $row[$v];
		}
		return($arr);
	}

	function getValueFromTable($table, $field, $key, $ret) {
		$SQL = "SELECT `$ret` FROM `$table` WHERE `$field` = \"$key\"";
//echo $SQL;
		$rs = mysqli_query ($SQL);
		if ($row = mysqli_fetch_row ($rs)) {
			return ($row[0]);
		}
		return ("");
	}

	function setValueInTable($table, $keyField, $keyValue, $chField, $chValue) {
		$chValue = system_escape ($chValue);

		$SQL = "UPDATE `$table` ".
					 "SET $chField = \"$chValue\" ".
					 "WHERE `$keyField` = \"$keyValue\"";
		if (! mysqli_query ($SQL) ) {
			$this->writeLogInfo(10, "SQL-SETVAL", $SQL."  --> ".mysqli_error(), true);
		}
	}

	function getValueFromCurrentTable($ret) {
		$table = $this->dbTableCurrent;
		$field = $this->dbTableInfoArray[$table]->dbTableKeyField;
		$key = $this->dbTableInfoArray[$table]->dbTableCurrentID;
		$r = $this->getValueFromTable($table, $field, $key, $ret);
		return ($r);
	}

	function getValueFromTableInActiveProcess ($table, $ret) {
		$field = $this->dbTableInfoArray[$table]->dbTableKeyField;
		$key = $this->dbTableInfoArray[$table]->dbTableCurrentID;
		$r = $this->getValueFromTable($table, $field, $key, $ret);
		return ($r);
	}

	function setValueInCurrentTable ($fldName, $fldValue) {
		$table = $this->dbTableCurrent;;
		$keyField = $this->dbTableInfoArray[$table]->dbTableKeyField;
		$keyValue = $this->dbTableInfoArray[$table]->dbTableCurrentID;
		$this->setValueInTable($table, $keyField, $keyValue, $fldName, $fldValue);
	}

	function setValueInTableInActiveProcess ($table, $fldName, $fldValue) {
		$keyField = $this->dbTableInfoArray[$table]->dbTableKeyField;
		$keyValue = $this->dbTableInfoArray[$table]->dbTableCurrentID;
		$this->setValueInTable($table, $keyField, $keyValue, $fldName, $fldValue);
	}

	public static function createConnection () {
		return new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	}
}

?>
