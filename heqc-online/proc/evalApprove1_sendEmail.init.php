<?php 

	// Manager has approved evaluator reports for this application
	// Email informs AC Meeting_CHE group that there is new application to assign.

	$message = $this->getTextContent("evalApprove1", "New applications ready for AC Meeting");

	// get userid and email address of those in AC meeting group - 13 - could be more than one of them
	//$usr = ($this->readTFV("InstitutionType") == 1)?("usr_project_admin_priv"):("usr_project_admin_pub");
	//$user_ref = $this->getDBsettingsValue($usr);
	//$to_email = $this->getValueFromTable("users","user_id",$user_ref,"email");

	$subject = "New Application ready for AC Meeting";
	//$this->misMailByName ($to_email, $subject, $message, "", true);
?>