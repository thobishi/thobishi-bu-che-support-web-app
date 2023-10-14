<?php

	$subject = "Finance indicator";
	$message = $this->getTextContent ("finance_indicator1", "Percent completion - screening");
	
	$this->notify_finance_percent_complete($subject, $message,"1");

?>