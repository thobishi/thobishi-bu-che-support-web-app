<?php 

$prevUser = $_POST["prev_user_ref"];
$newUser = $_POST["FLD_user_ref"];
$subject = "Change of MIS process";

if ($prevUser != $newUser) {
	// BUG: the system does not know anything about the process
	// $this->misMail ($prevUser, $subject, $this->getTextContent ($this->template, "ProcessRemoved"));
	//$this->misMail ($newUser, $subject, $this->getTextContent ($this->template, "ProcessGiven"));
} else {
	$altMessage = "You have not changed the user, thus the process will stay with the same user.";
}

?>
