<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$app_id  = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$payment_id = $this->dbTableInfoArray["payment"]->dbTableCurrentID;
	
	// 2010/10/27 Robin: Return the application to the institutional administrator instead of the user who started the application.
	// $app_user_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id,"user_ref");
	$ins_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id");
	$user_arr = $this->getInstitutionAdministrator(0,$ins_id);
	if ($user_arr[0]==0){
		echo $user_arr[1];
		die();
	}
	$app_user_id = $user_arr[0];
	$app_user_email = $this->getValueFromTable("users", "user_id",$app_user_id, "email");
	$app_version = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version");

	if (isset($_POST["cancelPaymentgotoInst"]) && ($_POST["cancelPaymentgotoInst"] == 1)) {

		// Removed - will return app payment record and proceeding payment records.
		//$payment_id = $this->getValueFromTable("payment","application_ref",$app_id,"payment_id");

		if ($payment_id > 0){

			$to = $app_user_email;
			$message = $this->getTextContent ("checkForm1c", "cancelPayment");
			$this->misMailByName($to, "Returning application", $message);
			$this->setValueInTable("Institutions_application", "application_id", $app_id, "submission_date", "1970-01-01");
			$this->setValueInTable("Institutions_application", "application_id", $app_id, "application_printed", "0");

			$dsql = <<<DEL
				delete from payment
				where payment_id = $payment_id
DEL;
			$errorMail = false;
			mysqli_query($conn, $dsql) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-DELREC", $dsql."  --> ".mysqli_error($conn), $errorMail);

			$isql = <<<INS
				insert into payment (payment_id, application_ref)
				VALUES ($payment_id, $app_id);
INS;
			$errorMail = false;
			mysqli_query($conn, $isql) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-INSREC", $isql."  --> ".mysqli_error($conn), $errorMail);

			$applicationProcess = ($app_version == 1) ? "5" : "113";
			$id = $this->addActiveProcesses ($applicationProcess, $app_user_id);
			$this->completeActiveProcesses ();
		}
	}
?>
