<?php
	$subject = "Payment required";
	$message = $this->getTextContent ("generic", "returnApplication");

	$usr_setting = "usr_registry_payment";
	$user = $this->getValueFromTable("settings", "s_key", $usr_setting, "s_value");

	$this->changeProcessAndUser(198, $user, $subject, $message);
?>