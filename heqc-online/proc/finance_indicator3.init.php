<?php

	$subject = "Finance indicator";
	$message = $this->getTextContent ("finance_indicator3", "Percent completion - AC meeting");
	
	$this->notify_finance_percent_complete($subject, $message,"3");

?>