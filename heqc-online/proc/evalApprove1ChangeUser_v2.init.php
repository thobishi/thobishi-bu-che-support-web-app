<?php
$subject = "Finance indicator";
$message = $this->getTextContent ("finance_indicator2", "Percent completion - evaluation");
	
//$this->changeProcessAndUser(159 , $_POST["user_ref"], $subject,$message);

$subject = "Directorate Recommendation - Appoint";
$message = $this->getTextContent ("generic", "sendApplication");
$new_user = $_POST["user_ref"];

    $to = $this->getValueFromTable("users", "user_id", $new_user, "email");

    $this->misMailByName($to, $subject, $message);

$id = $this->addActiveProcesses (159, $new_user, 0);

$this->completeActiveProcesses();

$this->notify_finance_percent_complete($subject, $message,"2");	
?>
