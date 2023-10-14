<?php
	$subject = "Assign to HEQC Meeting";
	$message = $this->getTextContent ("generic", "returnApplication");

	$usr_setting = ("usr_heqc_meeting");	
	$new_user = $this->getValueFromTable("settings", "s_key", $usr_setting, "s_value");

	$this->changeProcessAndUser(167, $new_user, $subject, $message);
?>