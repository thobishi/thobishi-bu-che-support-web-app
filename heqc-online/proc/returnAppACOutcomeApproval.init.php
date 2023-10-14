<?php
	$subject = "Returned Application: AC Outcome Approval";
	$message = $this->getTextContent ("generic", "returnApplication");

	$this->changeProcessAndUser(173, $_POST["user_ref"], $subject, $message);
?>