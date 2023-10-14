<?php
	$prev_adm_id = readPost('prev_adm_id');
	$new_adm_id = readPost('new_adm_id');
	$status = "new";
	
	// Change from old administrator to new administrator
	if ($prev_adm_id > 0 && $new_adm_id > 0){  // if valid administrator ids.
	

		$asql = <<<ADDPROCESS
			INSERT INTO active_processes (active_processes_id, processes_ref, work_flow_ref, user_ref,
				  workflow_settings, status, last_updated, active_date, due_date, expiry_date)
				SELECT null, processes_ref, work_flow_ref, $new_adm_id, 
				workflow_settings, status, last_updated, active_date, due_date, expiry_date
				FROM active_processes 
				WHERE status = 0 
				AND user_ref IN ($prev_adm_id)
ADDPROCESS;
		$errorMail1 = false;
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
		$conn->query($asql) or $errorMail1 = true;
		$this->writeLogInfo(10, "SQL-INSREC", $asql."  --> ".mysqli_error($conn), $errorMail1);

	// Only update if the insert was successful else we close the process completely.
	
		if (!$errorMail1){
			$usql3 = <<<COMPLETEPROCESS
				UPDATE active_processes
				SET status = 1
				WHERE user_ref IN ($prev_adm_id)
				AND status = 0
COMPLETEPROCESS;
			$errorMail = false;
			$conn->query($usql3) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-UPDREC", $usql3."  --> ".mysqli_error($conn), $errorMail);
		}
		
		$dsql = <<<DEL
			DELETE FROM sec_UserGroups  
			WHERE (sec_user_ref IN ($prev_adm_id) OR sec_user_ref = $new_adm_id)
			AND sec_group_ref = 4
DEL;
		$errorMail = false;
		$conn->query($dsql) or $errorMail = true;
		$this->writeLogInfo(10, "SQL-DELREC", $dsql."  --> ".mysqli_error($conn), $errorMail);

		$isql = <<<INS
			INSERT INTO sec_UserGroups (sec_UserGroups_id, sec_user_ref, sec_group_ref)
			VALUES(NULL, $new_adm_id, 4)
INS;
		$errorMail = false;
		$conn->query($isql) or $errorMail = true;
		$this->writeLogInfo(10, "SQL-INSREC", $isql."  --> ".mysqli_error($conn), $errorMail);

		$status = "complete";

	} // end valid administrator ids

	if ($prev_adm_id == 0 && $new_adm_id > 0){  // Only a new administrator. Please note that new administrators for new institutions should apply via the online registration process.
	
		$dsql = <<<DEL
			DELETE FROM sec_UserGroups  
			WHERE (sec_user_ref = $new_adm_id)
			AND sec_group_ref = 4
DEL;
		$errorMail = false;
		$conn->query($dsql) or $errorMail = true;
		$this->writeLogInfo(10, "SQL-DELREC", $dsql."  --> ".mysqli_error($conn), $errorMail);

		$isql = <<<INS
			INSERT INTO sec_UserGroups (sec_UserGroups_id, sec_user_ref, sec_group_ref)
			VALUES(NULL, $new_adm_id, 4)
INS;
		$errorMail = false;
		$conn->query($isql) or $errorMail = true;
		$this->writeLogInfo(10, "SQL-INSREC", $isql."  --> ".mysqli_error($conn), $errorMail);

		$status = "complete";

	} // end valid administrator ids

?>
