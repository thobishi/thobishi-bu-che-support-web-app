<?php
	$subject = "Returned Application: AC Outcome Approval";
	$message = $this->getTextContent ("generic", "returnSiteApplication");

	$this->changeProcessAndUser(185, $_POST["user_ref"], $subject, $message);
?>