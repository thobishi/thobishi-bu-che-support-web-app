<?php
	
//$this->changeProcessAndUser(160 , $_POST["user_ref"], " ", "");

$subject = "Directorate Recommendation Approval - Preliminary";
$message = $this->getTextContent ("generic", "sendApplication");
$new_user = $_POST["user_ref"];

    $to = $this->getValueFromTable("users", "user_id", $new_user, "email");

    $this->misMailByName($to, $subject, $message);


$id = $this->addActiveProcesses (160, $new_user, 0);

$this->completeActiveProcesses();

		
?>
