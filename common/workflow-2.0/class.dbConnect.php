<?php

// Required contacts
if (! defined ('WRK_TABLE_SETTINGS') ) die ("ERROR: Settings table not defined.");

if (	!defined ('DB_SERVER') ||
			!defined ('DB_DATABASE') ||
			!defined ('DB_USER') ||
			!defined ('DB_PASSWD')
   ) die ("ERROR: Connection not defined.");

class dbConnect {
	var $conn,$DBserver, $DBname, $DBuser, $DBpassw;

	function dbConnect() {
		self::__construct();
	}
	
	function __construct() {
		$this->DBserver = DB_SERVER;
		$this->DBname = DB_DATABASE;
 		$this->DBuser = DB_USER;
 		$this->DBpassw = DB_PASSWD;

		$conn = new mysqli($this->DBserver, $this->DBuser, $this->DBpassw, $this->DBname);
		
		if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }

                if ($conn->ping()) {
                    
                } else {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
	}
	
	function getDatabaseConnection(){
            $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
            if ($conn->connect_errno) {
                $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                printf("Error: %s\n".$conn->error);
                exit();
            }

            /* check if server is alive */
            if (!$conn->ping()) {
                $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                printf("Error: %s\n".$conn->error);
                exit();
            }
            return $conn;
	}
	
	/*static function getConnection(){
            $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
            if ($conn->connect_errno) {
                $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                printf("Error: %s\n".$conn->error);
                exit();
            }

            if (!$conn->ping()) {
                $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                printf("Error: %s\n".$conn->error);
                exit();
            }
            return $conn; 
	}*/

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
		$SQL = "SELECT `s_value` FROM `".WRK_TABLE_SETTINGS."` WHERE `s_key`=?";
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $key);
		$sm->execute();
		$RS = $sm->get_result();
		
		//$RS = mysqli_query($SQL) or die($SQL);
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
		$SQL = "UPDATE `".WRK_TABLE_SETTINGS."` SET `s_value`='".$value."' WHERE `s_key`=?";
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
                
		$k = system_escape_i($key, $conn);
		
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $k);
		$sm->execute();
		$RS = $sm->get_result();
		//$RS = mysqli_query($SQL);
	}

	function makeArrayFromSQL($SQL, $key="", $val="") {

		$arr = array();
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
		$rs = mysqli_query($conn, $SQL);
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
		$SQL = "SELECT $ret FROM `$table` WHERE `$field` = ?";
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
                //var_dump($key);
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $key);
		$sm->execute();
		$rs = $sm->get_result();
		//$rs = mysqli_query ($SQL);
		if (!$rs){
			$this->writeLogInfo(10, "SQL-GETVAL", $SQL."  --> ".mysqli_error(), true);
		} else {
			if ($row = mysqli_fetch_row ($rs)) {
				return ($row[0]);
			}
		}
		return ("");
	}

	function setValueInTable($table, $keyField, $keyValue, $chField, $chValue) {
		// W are not allowed to change whe we are in view mode
		if ($this->view == 1) return;

		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
                
		$chValue = system_escape_i ($chValue, $conn);

		$SQL = "UPDATE `$table` ".
					 "SET $chField = \"$chValue\" ".
					 "WHERE `$keyField` = ?";

                
		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $keyValue);
		$sm->execute();
		$RS = $sm->get_result();
		
		if (! $RS ) {
			$this->writeLogInfo(10, "SQL-SETVAL", $SQL."  --> ".mysqli_error($conn), true);
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

}

?>
