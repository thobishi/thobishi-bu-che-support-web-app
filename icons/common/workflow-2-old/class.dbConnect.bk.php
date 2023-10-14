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

		$this->conn = mysqli_connect($this->DBserver, $this->DBuser, $this->DBpassw, $this->DBname);
	    if (!$this->conn) {
			$this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".mysqli_error ($this->conn) ,$this->DBname);
			die("Data Base Connection down");
		}

		$conectDB = mysqli_select_db($this->conn, $this->DBname);
	      if (!$conectDB) {
			$this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".mysqli_error ($this->conn), $this->DBname);
			die("Data Base Connection down");
		}
	}

	function error_email ($subject, $message, $sender) {
		$mail = new PHPMailer();

		$mail->From = WRK_SYSTEM_EMAIL;
		$mail->FromName = "$sender System";

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
		$this->pr($SQL);
		$RS = $this->db->query($SQL) or die($SQL);
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
			$this->AuditLog->writeLogInfo(3, "SETTINGS", $error);
			die ("Setting not found: $error");
		}
	}

	function setDBsettingsValue($key, $value){
		// 20070616 (Diederik): HERE WAS only if mayUseSettings TRUE else die with error message (from settings files)
		$SQL = "UPDATE `".WRK_TABLE_SETTINGS."` SET `s_value`='".$value."' WHERE `s_key`='".system_escape_i($key, $this->conn)."'";
		$RS = $this->db->query($SQL);
	}

	/*function getValueFromTable($table, $field, $key, $ret){
		$SQL = "SELECT $ret FROM `$table` WHERE `$field` = \"$key\"";
		$rs = $this->db->query($SQL);
		if (!$rs){
			$this->AuditLog->writeLogInfo(10, "SQL-GETVAL", $SQL."  --> ".mysqli_error($this->conn), true);
		} else {
			if ($row = $rs->fetch()) {
				return ($row[0]);
			}
		}
		return ("");
	}
	*/

	// function setValueInTable($table, $keyField, $keyValue, $chField, $chValue){
		// W are not allowed to change whe we are in view mode
		// if ($this->view == 1) return;

		// $chValue = system_escape_i ($chValue, $this->conn);

		// $SQL = "UPDATE `$table` ".
					 // "SET $chField = \"$chValue\" ".
					 // "WHERE `$keyField` = \"$keyValue\"";
					 
		// if(! $this->db->query($SQL) ){
			// $this->AuditLog->writeLogInfo(10, "SQL-SETVAL", $SQL."  --> ".mysqli_error($this->conn), true);
		// }
	// }

	function getValueFromCurrentTable($ret) {
		$table = $this->dbTableCurrent;
		$field = $this->dbTableInfoArray[$table]->dbTableKeyField;
		$key = $this->dbTableInfoArray[$table]->dbTableCurrentID;
		$r = $this->db->getValueFromTable($table, $field, $key, $ret);
		return ($r);
	}

	function getValueFromTableInActiveProcess ($table, $ret) {
		$field = $this->dbTableInfoArray[$table]->dbTableKeyField;
		$key = $this->dbTableInfoArray[$table]->dbTableCurrentID;
		$r = $this->db->getValueFromTable($table, $field, $key, $ret);
		return ($r);
	}

	function setValueInCurrentTable ($fldName, $fldValue) {
		$table = $this->dbTableCurrent;;
		$keyField = $this->dbTableInfoArray[$table]->dbTableKeyField;
		$keyValue = $this->dbTableInfoArray[$table]->dbTableCurrentID;
		$this->db->setValueInTable($table, $keyField, $keyValue, $fldName, $fldValue);
	}

	function setValueInTableInActiveProcess ($table, $fldName, $fldValue) {
		$keyField = $this->dbTableInfoArray[$table]->dbTableKeyField;
		$keyValue = $this->dbTableInfoArray[$table]->dbTableCurrentID;
		$this->db->setValueInTable($table, $keyField, $keyValue, $fldName, $fldValue);
	}

}