<?php

	$subject = "Finance indicator";
	$message = $this->getTextContent ("finance_indicator2", "Percent completion - evaluation");
	
	$this->notify_finance_percent_complete($subject, $message,"2");

?>