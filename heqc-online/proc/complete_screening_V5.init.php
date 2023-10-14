<?php

$proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
    
	
	$today = date("Y-m-d");
	$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $proc_id, "screened_date", $today);

	$mail_subject = "Finance indicator";
	$mail_content = $this->getTextContent ("finance_indicator1", "Percent completion - screening");


	$usr_eval = $this->getDBsettingsValue("usr_eval_appoint_accred");


	

	
	$subject = "Process Application";
	$message = $this->getTextContent ("generic", "sendApplication");
	
	//$this->changeProcessAndUser(106,$usr_eval, $subject, $message);
	
//$this->changeProcessAndUser(106, $_POST["user_ref"], $subject, $message);


	$new_user=$_POST["user_ref"];
	// If an email text is provided then email the user
	
		$sendtouser = $this->getValueFromTable("users", "user_id", $usr_eval, "email");
		$to = $this->getValueFromTable("users", "user_id", $new_user, "email");

		$this->misMailByName($sendtouser, $mail_subject, $mail_content);

		$this->misMailByName($to, $subject, $message);

	
	$id = $this->addActiveProcesses (106, $new_user, 0);

	$this->completeActiveProcesses();


		
?>
