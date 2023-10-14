<?php

	$subject = "Directorate Recommendation Intermediate Approval";
	$message = $this->getTextContent ("generic", "returnApplication");
	
	$this->changeProcessAndUser(161, $_POST["user_ref"], $subject, $message)

?>