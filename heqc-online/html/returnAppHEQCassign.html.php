<?php
	$subject = "HEQC Meeting: Indicate that application may be assigned";
	$message = $this->getTextContent ("generic", "returnApplication");
	
	$this->changeProcessAndUser(167, $_POST["user_ref"], $subject, $message);
?>