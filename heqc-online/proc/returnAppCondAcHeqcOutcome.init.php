<?php
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	// 2012-05-25 Robin
	//  Using heqc_meeting_ref to determine if it must go back to the HEQC outcome process or the AC outcome process.
	$heqc_meeting_ref = $this->getValueFromTable("ia_proceedings","ia_proceedings_id", $app_proc_id, "heqc_meeting_ref");
	if ($heqc_meeting_ref > 0){
		$subject = "Returned Application: HEQC Outcome";
		$message = $this->getTextContent ("generic", "returnApplication");

		$this->changeProcessAndUser(168, $_POST["user_ref"], $subject, $message);
	} else {
		$subject = "Returned Application: AC Outcome Approval";
		$message = $this->getTextContent ("generic", "returnApplication");

		$this->changeProcessAndUser(173, $_POST["user_ref"], $subject, $message);
	}
?>