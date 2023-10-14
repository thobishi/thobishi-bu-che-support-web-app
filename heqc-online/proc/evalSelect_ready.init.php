<?php
	// Mark application as ready for the AC meeting
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }

	if (isset($_POST["readyForApproval"]))
	{
			$SQL = "UPDATE Institutions_application SET application_status = 4 WHERE application_id=".$app_id;
			$errorMail = false;
			mysqli_query($conn, $SQL) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-UPDREC", $SQL."  --> ".mysqli_error($conn), $errorMail);

			$SQL = "UPDATE ia_proceedings SET evaluator_access_end_date = now() WHERE ia_proceedings_id=".$app_proc_id;
			$errorMail = false;
			mysqli_query($conn, $SQL) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-UPDREC", $SQL."  --> ".mysqli_error($conn), $errorMail);
	}
	$reference =$this->getValueFromTable('Institutions_application', 'application_id', $app_id, "CHE_reference_code");
	$subject="HEQC: Approve Final Evaluator Report - ".$reference ;
	//$message = $this->getTextContent("generic", "returnApplication");


	//$subject = "Process Application";
	$message = $this->getTextContent ("generic", "sendApplication");
	$new_user = $_POST["user_ref"];
	
		$to = $this->getValueFromTable("users", "user_id", $new_user, "email");

		$this->misMailByName($to, $subject, $message);

	
	$id = $this->addActiveProcesses (163, $new_user, 0);

	$this->completeActiveProcesses();
	//$this->changeProcessAndUser(163, $_POST["user_ref"], $subject, $message );

?>
