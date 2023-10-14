<?php

	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_version = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version");

	$this->setValueInTable("screening", "application_ref", $app_id, "proc_to_manager", 0);

	$user = readPost("user_ref");

	$subject = "Background request";
	$message = $this->getTextContent ("generic", "colleagueRequest");


	if ($app_version==5){
		$flow = '11722';
		$this->changeProcessAndUser(221, $user, $subject, $message,$flow);

	}else{
		$flow = '10978';

		$this->changeProcessAndUser(7, $user, $subject, $message,$flow);
	}

?>