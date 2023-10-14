<?php
ini_set('memory_limit', '-1');
// Required contacts
if (! defined ('WRK_TABLE_SETTINGS') ) die ("ERROR: Settings table not defined.");

if (	!defined ('DB_SERVER') ||
			!defined ('DB_DATABASE') ||
			!defined ('DB_USER') ||
			!defined ('DB_PASSWD')
   ) die ("ERROR: Connection not defined.");

class dbConnect {
	public $conn,$DBserver, $DBname, $DBuser, $DBpassw;

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
	
        /* check if server is alive */
        if ($conn->ping()) {
            //printf ("Database connection has been established.\n");
        } else {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
      mysqli_query($conn, "SET SESSION sql_mode = ''");
       
        
        mysqli_set_charset($conn,"utf8");	
	}
	
	function dbConnect() {
		self::__construct();
	}
	
	public function getDatabaseConnection() {
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
      mysqli_query($conn, "SET SESSION sql_mode = ''");
            	mysqli_set_charset($conn,"utf8");	
            return $conn;
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
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
               // $sm = $conn->prepare($SQL);
                //$sm->bind_param("s", $key);
                //$sm->execute();
                
               // $RS = $sm->get_result();
                
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL) or die($SQL);
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
                        file_put_contents('php://stderr', print_r("SQL : ".$SQL.", key : ".$key, TRUE));
			$error = "The following settings value could not be found: '".$key."'";
			$this->writeLogInfo(3, "SETTINGS", $error);
			die ("Setting not found: $error");
		}
	}

	function setDBsettingsValue($key, $value) {
		// 20070616 (Diederik): HERE WAS only if mayUseSettings TRUE else die with error message (from settings files)
		$SQL = "UPDATE `".WRK_TABLE_SETTINGS."` SET `s_value`='".$value."' WHERE `s_key`='".system_escape($key)."'";
		
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
		if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
                
              //  $val = system_escape_i($key, $conn);
                
              //  $sm = $conn->prepare($SQL);
               // $sm->bind_param("s", $val);
               // $sm->execute();
                
               // $RS = $sm->get_result();
		$RS = mysqli_query($this->getDatabaseConnection(), $SQL);
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
	//		var_dump($SQL);
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
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
			
				
		//var_dump($key); 
		$SQL = "SELECT $ret FROM `$table` WHERE `$field` = \"$key\"";

		//var_dump($SQL);
		//echo $SQL;
       mysqli_query($conn, "SET SESSION sql_mode = ''");
        
       // echo $ret;
       // echo $table;
        //echo $key;
        
        mysqli_set_charset($conn,"utf8");	
		$rs = mysqli_query ($conn, $SQL);
		  
		if (!$rs){
			$this->writeLogInfo(10, "SQL-GETVAL", $SQL."  --> ".mysqli_error($conn), true);
		} else {
			if ($row = mysqli_fetch_row ($rs)) {
				return ($row[0]);
			}
		}
		
		return ("");
	}

	function setValueInTable($table, $keyField, $keyValue, $chField, $chValue) {
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
		// W are not allowed to change whe we are in view mode
		if ($this->view == 1) return;

		$chValue = system_escape_i ($chValue, $conn);

		$SQL = "UPDATE `$table` ".
					 "SET $chField = \"$chValue\" ".
					 "WHERE `$keyField` = \"$keyValue\"";
		if (! mysqli_query ($conn, $SQL) ) {
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
