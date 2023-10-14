<?php

	$user_ref = readPost("user_ref");
	$s_key = $this->dbTableInfoArray['settings']->dbTableCurrentID;
	$active_processes_id = readPost("active_processes_id");
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if (!$conn) {
                $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".mysqli_error ($conn) ,$this->DBname);
                die("getDatabaseConnection -> Data Base Connection down"." DBserver : ".$DB_SERVER.", DBname : ".$DB_DATABASE.", DBuser : ".$DB_USER.", DBpassw : ".$DB_PASSWD);
        }
	
	if ($user_ref > 0) {
		$sql = "UPDATE `settings` SET `s_value` = '$user_ref' WHERE `s_key` = ?";
		
		$sm = $conn->prepare($sql);
		$sm->bind_param("s", $s_key);
		$sm->execute();
		$rs = $sm->get_result();
		
		$errorMail = false;
		if(!$rs)
                    $errorMail = true;
		$this->writeLogInfo(10, "SQL-UPDREC", $sql."  --> ".mysqli_error($conn), $errorMail);
	}	
?>