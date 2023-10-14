<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

	$user = readPost("user_ref");

	$subject = "Background request";
	$message = $this->getTextContent ("generic", "colleagueRequest");

	$this->changeProcessAndUser(194, $user, $subject, $message);
?>