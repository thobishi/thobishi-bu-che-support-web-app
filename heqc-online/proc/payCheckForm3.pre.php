<?php 
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$pay_id = $this->dbTableInfoArray["payment"]->dbTableCurrentID;
	$ins_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id");
	$first_date = $this->getValueFromTable("payment", "payment_id", $pay_id, "date_first_reminder");
	$final_date = $this->getValueFromTable("payment", "payment_id", $pay_id, "date_final_reminder");
	$due_date = $this->getValueFromTable("active_processes", "active_processes_id", $this->active_processes_id, "due_date");
	$expiry_date = $this->getValueFromTable("active_processes", "active_processes_id", $this->active_processes_id, "expiry_date");
?>
