<?php 
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$d = $this->getValueFromTable("active_processes", "active_processes_id", $this->active_processes_id, "due_date");

	$user_arr = $this->getInstitutionAdministrator($app_id);
	if ($user_arr[0]==0){
		echo "Processing has been halted for the following reason: <br><br>";
		echo $user_arr[1];
		die();
	}
	
	$this->setValueInTable("payment", "payment_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "date_first_reminder", date("Y-m-d"));
	$this->setValueInTable("payment", "payment_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "received_confirmation", 0);
	$this->setValueInTable("active_processes", "active_processes_id", $this->active_processes_id, "due_date", date("Y-m-d", mktime(0,0,0, substr($d,5,2), substr($d,8,2)+5, substr($d,0,4))));

//  2009-09-01 I'm replacing user from application record with current active institutional administrator because the administrator may
//  have changed since the application was started.
//	$to = $this->getValueFromTable("users", "user_id", $app_id, "user_ref"), "email");
	$to = $this->getValueFromTable("users", "user_id", $user_arr[0], "email");

	$cc = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("settings", "s_key", "usr_registry_payment", "s_value"), "email");

	$message = $this->getTextContent ("payCheckForm6", "firstPaymentReminder");
	$this->misMailByName($to, "First payment reminder", $message, $cc);
?>