<?php 

	// Manager has rejected evaluator reports for this application and is sending back to the Manage evaluators user.

	$message = $this->getTextContent("evalApprove3", "Manager Evaluator Report Not Approved");

	// get userid and email address of Project Administrator
	//$usr = ($this->readTFV("InstitutionType") == 1)?("usr_eval_manage_priv"):("usr_eval_manage_pub");
	$user_ref =$_POST["user_ref"]; //$this->getDBsettingsValue($usr);
	$to_email = $this->getValueFromTable("users","user_id",$user_ref,"email");

	$subject = "Evaluator reports not approved ";

	$this->misMailByName ($to_email, $subject, $message, "", true);



	$Standardmessage = $this->getTextContent ("generic", "returnApplication");
	
		$this->misMailByName($to_email, "Application returned", $Standardmessage);

	// Send application back to the Manage evaluators user.
	$nextWorkflowID = $this->getValueFromTable("work_flows", "template", "evalSelect5", "work_flows_id");
	$this->addActiveProcesses($this->flowID, $user_ref, $nextWorkflowID);

?>