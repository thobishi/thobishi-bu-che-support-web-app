<?php
        $conn = $this->getDatabaseConnection();
        
	$user_reg_id = $this->dbTableInfoArray["user_registration"]->dbTableCurrentID;
//echo "<br>user_reg_id: ". $user_reg_id;
	$user_reg_processed = $this->getValueFromTable("user_registration", "user_reg_id",$user_reg_id, "is_processed");
	$user_reg_email = $this->getValueFromTable("user_registration", "user_reg_id",$user_reg_id, "email");

//echo "<br>user_reg_processed: ". $user_reg_processed;

	$reg_msg = "This user registration request has been processed.";
	if ($user_reg_processed == 0){ // User has not been processed yet.

		// INSERT record into the user table.
		$ins = <<<INSSQL
				INSERT INTO users (user_id, name, surname, title_ref, email, 
							password, contact_nr, institution_ref, institution_name, 
							registration_date, contact_cell_nr, registration_doc, public_nursing_college)
				SELECT NULL, name, surname, title_ref, email, 
							PASSWORD2(password), contact_nr, institution_ref, institution_name, 
							registration_date,	contact_cell_nr, registration_doc, public_nursing_college
 				FROM user_registration
				WHERE user_reg_id = $user_reg_id
INSSQL;
//echo "$ins";
				file_put_contents('php://stderr', print_r("\nSQL : ".$ins, TRUE));

		$errorMail = false;
		mysqli_query($conn, $ins) or $errorMail = true;
		$new_user_id = mysqli_insert_id($conn);

		$this->writeLogInfo(10, "SQL-INSREC", $ins."  --> ".mysqli_error($conn), $errorMail);

		if ($errorMail === false){  // Continue only if user was entered successfully.
			// Get user_id of newly inserted record

			// Start a new authorisation process on the user table. 
			// process 4 is the authorise user process and forms atart with auth
			$workflow = $this->makeWorkFlowStringFromCurrent ("users", "user_id", $new_user_id);
			$this->addActiveProcesses (4, $this->getDBsettingsValue("current_auth_user_id"), 0, 0, false,$workflow);	

			// Email the person who registered
			$to = $user_reg_email;
			$from = "";
			$subject = "Registration";
			$message = $this->getTextContent ("regForm3", "Thank you for registering");
			$this->misMailByName ($to, $subject, $message);
	
			// Email the HEQC
			$to = $this->getDBsettingsValue("current_auth_user_id");
			$from = "";
			$subject = "New User Registration Request";
			$message = $this->getTextContent ("regForm3", "NewRegAuth");
			$this->misMail ($to, $subject, $message);
	
			$this->setValueInTable ("user_registration", "user_reg_id", $user_reg_id, "is_processed", '1');
			
			$reg_msg = "Thank you for registering with the HEQC Accreditation System.<br><br>As soon as your login has been authorised you will be notified via e-mail.";
		} // end - insert successful
		
		if ($errorMail === true){
			$reg_msg = "A problem occurred with your registration.  An approval request has not been sent.  Please try and register again or contact CHE to report the problem.";
		}
	} // end - user processed
?>
