<?php
	$subject = "Appoint evaluator";
	$message = $this->getTextContent ("generic", "returnApplication");

	$usr_setting = ($this->readTFV("InstitutionType") == 1)?("usr_eval_manage_priv"):("usr_eval_manage_pub");	
	$eval_user = $this->getValueFromTable("settings", "s_key", $usr_setting, "s_value");

	$this->changeProcessAndUser(106, $eval_user, $subject, $message);
?>