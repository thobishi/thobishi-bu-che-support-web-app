<?php


	
	
	$subject = "Application returned";


	$message = $this->getTextContent ("generic", "returnApplication");

	
	
	$this->changeProcessAndUser(221, $this->getDBsettingsValue("usr_registry_screener"), $subject, $message);



		
?>
