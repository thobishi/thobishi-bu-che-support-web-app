<?php 
$date_final_reminder = $this->getValueFromTable("payment", "payment_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "date_final_reminder");
$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
$ins_id = $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reaccred_id, "institution_ref");

$user_arr = $this->getInstitutionAdministrator(0,$ins_id);
if ($user_arr[0]==0){
	echo $user_arr[1];
	die();
}

$d = $this->getValueFromTable("active_processes", "active_processes_id", $this->active_processes_id, "due_date");
$this->setValueInTable("payment", "payment_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "date_final_reminder", date("Y-m-d"));
$this->setValueInTable("payment", "payment_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "received_confirmation", 0);
$this->setValueInTable("active_processes", "active_processes_id", $this->active_processes_id, "due_date", date("Y-m-d", mktime(0,0,0, substr($d,5,2), substr($d,8,2)+5, substr($d,0,4))));
//$to = $this->getValueFromTable("users", "user_id", $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref"), "email");

$to = $user_arr[0];

$message = $this->getTextContent ("reAccpayCheckForm8", "reAccfinalPaymentReminder");

if (($date_final_reminder == '1970-01-01') || ($date_final_reminder == '')) {
	$this->misMailByName($to, "Final payment reminder", $message);
}
?>
