<?php

	$subject = "Site Directorate Recommendation Final Approval";
	$message = $this->getTextContent ("generic", "returnSiteApplication");
	
	$this->changeProcessAndUser(181, $_POST["user_ref"], $subject, $message);

?>