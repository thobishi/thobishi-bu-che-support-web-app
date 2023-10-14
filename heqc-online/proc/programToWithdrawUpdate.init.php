<?php	
	$app_id = readPost("FLD_application_ref");
	$active_processes_id = readPost("active_processes_id");
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	
	if ($app_id > 0 && (isset($_POST['withdrawConfirm']))){	
		$sql = <<<UPDCOND
			UPDATE Institutions_application 
			SET withdrawn_ind = '1', application_status = '-1'
			WHERE application_id = {$app_id}
UPDCOND;
                //$sm = $conn->prepare($sql);
                //$sm->bind_param("s", $app_id);
                //$sm->execute();
                
                
		$errorMail = false;
		mysqli_query($conn, $sql) or $errorMail = true;
		$this->writeLogInfo(10, "SQL-UPDREC", $sql."  --> ".mysqli_error($conn), $errorMail);

		if($active_processes_id > ''){
			$sqlProcess = <<<process
				UPDATE active_processes 
				SET status = '1'
				WHERE active_processes_id = {$active_processes_id}
process;
			mysqli_query($conn, $sqlProcess) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-UPDREC", $sqlProcess."  --> ".mysqli_error($conn), $errorMail);
		}

	}


	//$ins_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id");
	//$user_arr = $this->getInstitutionAdministrator(0,$ins_id);
	//if ($user_arr[0]==0):
	//	echo $user_arr[1];
	//	die();
	//endif;
	//$new_user = $user_arr[0];
	//$new_user = $this->getDBsettingsValue("usr_finance_indicator_emails");
	$finance_users = $this->getDBsettingsValue("usr_finance_indicator_emails");
	$finance_user_arr = explode(',',$finance_users);

foreach($finance_user_arr as $u){
$finance_users_emails= $finance_users_emails ."".	$this->getValueFromTable("users", "user_id", $u, "email") .", ";

}


	$fin_usr = $this->getDBsettingsValue("usr_registry_payment");
	
	$to = $finance_users_emails ." ".$this->getValueFromTable("users", "user_id", $fin_usr, "email");
	
	$cc = $this->getValueFromTable("users", "user_id", $this->currentUserID, "email");
	$message = $this->getTextContent ("finance_indicator_cancel", "cancellation financial indicator");
	//$message = $this->getTextContent ("checkFormReturnInstitution_V5", "returntoinstitution");
			
	

	
	$files = "";

//	$ia_withdrawals_id = $this->dbTableInfoArray["ia_withdrawals"]->dbTableCurrentID;
	$doc_id  = $this->getValueFromTable("ia_withdrawals", "application_ref", $app_id, "reason_doc");
	if ($doc_id > ""){

	$doc_url = $this->getValueFromTable("documents", "document_id", $doc_id,"document_url");
	$doc_name = $this->getValueFromTable("documents", "document_id", $doc_id,"document_name");

	$files = array();
	array_push($files,array(OCTODOC_DIR.$doc_url,$doc_name));
	//$this->misMailByName($to, "Return the application to the institution ", $message, $cc,$files);
	$this->misMailByName($to, "Cancellation financial indicator ", $message, $cc,true,$files);
	}else{
		$this->misMailByName($to, "Cancellation financial indicator ", $message, $cc,true);
	}
	//$this->misMailByName($to, "Cancellation financial indicator ", $message, $cc,true,$files);
	//$this->misMailByName ($to, $subject, $message, $cc, true ,$files);

//$this->misMailByName($to, "Cancellation financial indicator ", $message, $cc);

?>