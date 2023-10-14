<?php 
	$invoice_sent = 0;
	
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$ins_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id");
?>