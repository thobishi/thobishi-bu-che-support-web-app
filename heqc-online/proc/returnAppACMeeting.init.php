<?php
	$subject = "Assign to AC Meeting";
	$message = $this->getTextContent ("generic", "returnApplication");

	$usr_setting = ("usr_ac_meeting");	
	$new_user = $this->getValueFromTable("settings", "s_key", $usr_setting, "s_value");

	$this->changeProcessAndUser(165, $new_user, $subject, $message);
?>