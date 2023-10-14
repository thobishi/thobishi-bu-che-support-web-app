<?php
	$user_ref = readPost("user_ref");
	$prev_user_id = readPost("data");
	$process_id = $this->dbTableInfoArray['processes']->dbTableCurrentID;
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	if ($user_ref > 0 && $prev_user_id > 0 && $process_id > 0) {
		$sql = "UPDATE active_processes
			SET `user_ref` = $user_ref
			WHERE `user_ref` = $prev_user_id
			AND processes_ref = $process_id
			AND status = 0";

		$errorMail = false;
		mysqli_query($conn, $sql) or $errorMail = true;
		$no_affected_rows = mysqli_affected_rows();
		$this->writeLogInfo(10, "SQL-UPDREC", $sql."  --> ".mysqli_error($conn), $errorMail);
	}	
?>
