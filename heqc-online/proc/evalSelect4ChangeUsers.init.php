<?php
	
//$this->changeProcessAndUser(112 , $_POST["user_ref"], " ", "");

/*

$new_user=$_POST["user_ref"];
	// If an email text is provided then email the user

		$cc = $this->getValueFromTable("users", "user_id", $usr_registry_payment, "email");
		$to = $this->getValueFromTable("users", "user_id", $new_user, "email");
echo "************* to: " . $cc . "**************<br><br>";
echo "************* cc: " . $to . "**************<br><br>";
		$this->misMailByName($to, $subject, $message, $cc);
	
	$id = $this->addActiveProcesses (106, $new_user, 0);

	$this->completeActiveProcesses();
    */

    $subject = "Process Application";
	$message = $this->getTextContent ("generic", "sendApplication");
	$new_user = $_POST["user_ref"];
	
		$to = $this->getValueFromTable("users", "user_id", $new_user, "email");

		$this->misMailByName($to, $subject, $message);

	
	$id = $this->addActiveProcesses (112, $new_user, 0);

	$this->completeActiveProcesses();
		
?>
