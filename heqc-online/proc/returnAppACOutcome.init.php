<?php
	$subject = "AC Meeting and Outcome";
	$message = $this->getTextContent ("generic", "returnApplication");

	$this->changeProcessAndUser(165, $_POST["user_ref"], $subject, $message)
?>