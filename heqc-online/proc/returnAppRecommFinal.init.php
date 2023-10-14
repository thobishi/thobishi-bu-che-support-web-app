<?php

	$subject = "Directorate Recommendation Final Approval";
	$message = $this->getTextContent ("generic", "returnApplication");
	
	$this->changeProcessAndUser(162, $_POST["user_ref"], $subject, $message)

?>