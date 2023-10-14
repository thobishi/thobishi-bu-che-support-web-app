<?php
	$subject = "Finance indicator";
	$message = $this->getTextContent ("finance_indicator4", "Percent completion - HEQC meeting");
	
	$this->notify_finance_percent_complete($subject, $message,"4");
?>