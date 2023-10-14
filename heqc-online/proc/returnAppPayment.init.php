<?php
	$user = readPost("user_ref");

	$subject = "Payment request";
	$message = $this->getTextContent ("generic", "colleagueRequest");

	// 
	$flow = '11476';
	if (isset($_POST["LAST_WORKFLOW_ID"])) {
		$flow = $_POST["LAST_WORKFLOW_ID"];
	} else {
		$prevArr = explode ('|', $_POST["PREV_WORKFLOW"]);
		$flow  = $prevArr[1];
	}

	$this->changeProcessAndUser(198, $user, $subject, $message,$flow);
?>