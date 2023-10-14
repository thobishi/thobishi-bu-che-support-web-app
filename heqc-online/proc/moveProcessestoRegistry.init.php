<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$actproc_id  = $this->dbTableInfoArray["active_processes"]->dbTableCurrentID;
	$reg_usr_id = $this->getValueFromTable("settings","s_key",'che_registry_user_id',"s_value");
	$reg_user_email = $this->getValueFromTable("users", "user_id", $reg_usr_id, "email");
	$to = $reg_user_email;

	// Get registry fields based on whether its accreditation or re-accreditation.
	$k = readPost('acc_or_reacc');
	$areg = $this->getRegistryProcessInfo($k);
	$message = $this->getTextContent ("moveRegistryForOutcome", "application transferred for outcomes");

	$this->misMailByName($to, "Request for application outcome", $message);

	$asql = <<<ADDPROCESS
	INSERT INTO active_processes (active_processes_id, processes_ref, work_flow_ref, user_ref,
		  workflow_settings, status, last_updated, active_date, due_date, expiry_date)
	SELECT null, $areg[process], $areg[flow], $reg_usr_id, 
		workflow_settings, status, last_updated, active_date, due_date, expiry_date
	FROM active_processes 
	WHERE active_processes_id in ($actproc_id)
ADDPROCESS;

	$errorMail1 = false;
	mysqli_query($conn, $asql) or $errorMail1 = true;
	$this->writeLogInfo(10, "SQL-INSREC", $asql."  --> ".mysqli_error($conn), $errorMail1);

	// Only update if the insert was successful else we close the process completely.
	
	if (!$errorMail1){
		$usql = <<<COMPLETEPROCESS
		UPDATE active_processes set status = 1 
		WHERE active_processes_id in ($actproc_id)
COMPLETEPROCESS;

		$errorMail = false;
		mysqli_query($conn, $usql) or $errorMail = true;
		$this->writeLogInfo(10, "SQL-UPDREC", $usql."  --> ".mysqli_error($conn), $errorMail);
	}
?>
