<?php
	$subject = "Ready for HEQC Meeting";
	$message = $this->getTextContent ("generic", "returnApplication");
	
	$this->changeProcessAndUser(167, $_POST["user_ref"], $subject, $message)
?>