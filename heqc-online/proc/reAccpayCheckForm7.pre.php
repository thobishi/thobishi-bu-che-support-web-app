<?php 

	$d = $this->getValueFromTable("active_processes", "active_processes_id", $this->active_processes_id, "due_date");

	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
	$ins_id = $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reaccred_id, "institution_ref");

	$user_arr = $this->getInstitutionAdministrator(0,$ins_id);
	if ($user_arr[0]==0){
		echo $user_arr[1];
		die();
	}

	$this->setValueInTable("payment", "payment_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "date_first_reminder", date("Y-m-d"));
	$this->setValueInTable("payment", "payment_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "received_confirmation", 0);
	$this->setValueInTable("active_processes", "active_processes_id", $this->active_processes_id, "due_date", date("Y-m-d", mktime(0,0,0, substr($d,5,2), substr($d,8,2)+5, substr($d,0,4))));

//	$to = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref"), "email");


	$to = $user_arr[0];

	$cc = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("settings", "s_key", "usr_registry_payment", "s_value"), "email");

	$message = $this->getTextContent ("reAccpayCheckForm6", "reAccfirstPaymentReminder");
	$this->misMailByName($to, "First payment reminder", $message, $cc);
?>