<?php
	$proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

	$today = date("Y-m-d");
	$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $proc_id, "screened_date", $today);

	$subject = "Finance indicator";
	$message = $this->getTextContent ("finance_indicator1", "Percent completion - screening");
	$this->notify_finance_percent_complete($subject, $message,"1");
?>
