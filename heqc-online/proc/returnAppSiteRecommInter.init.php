<?php

	$subject = "Directorate Recommendation Intermediate Approval";
	$message = $this->getTextContent ("generic", "returnSiteApplication");
	
	$this->changeProcessAndUser(180, $_POST["user_ref"], $subject, $message)

?>