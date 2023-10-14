<?php 
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

	$user_arr = $this->getInstitutionAdministrator($app_id);
	if ($user_arr[0]==0){
		echo "Processing has been halted for the following reason: <br><br>";
		echo $user_arr[1];
		die();
	}
	
	$date_final_reminder = $this->getValueFromTable("payment", "payment_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "date_final_reminder");

	$d = $this->getValueFromTable("active_processes", "active_processes_id", $this->active_processes_id, "due_date");
	$this->setValueInTable("payment", "payment_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "date_final_reminder", date("Y-m-d"));
	$this->setValueInTable("payment", "payment_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "received_confirmation", 0);
	$this->setValueInTable("active_processes", "active_processes_id", $this->active_processes_id, "due_date", date("Y-m-d", mktime(0,0,0, substr($d,5,2), substr($d,8,2)+5, substr($d,0,4))));

//  2009-09-01 I'm replacing user from application record with current active institutional administrator because the administrator may
//  have changed since the application was started.
//	$to = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref"), "email");
	$to = $this->getValueFromTable("users", "user_id", $user_arr[0], "email");
	
	$message = $this->getTextContent ("payCheckForm8", "finalPaymentReminder");

	if (($date_final_reminder == '1970-01-01') || ($date_final_reminder == '')) {
		$this->misMailByName($to, "Final payment reminder", $message);
	}
?>
