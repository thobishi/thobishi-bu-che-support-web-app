<?php

	// Set HEQC meeting as complete (set heqc_end_date to current date)
	$heqc_meeting_id = $this->dbTableInfoArray["HEQC_Meeting"]->dbTableCurrentID;

	//$SQL  = "UPDATE `HEQC_Meeting` SET `heqc_end_date` = '".date("Y-m-d")."' WHERE `HEQC_Meeting`.`heqc_id` =".$heqc_meeting_id;
	//$rs = mysqli_query($SQL);
	$this->setValueInTable("HEQC_Meeting", "heqc_id", $heqc_meeting_id, "heqc_end_date", date("Y-m-d"));

	//$SQL  = "UPDATE `ia_proceedings` SET `application_status_ref` = '7' WHERE `heqc_meeting_ref`=".$heqc_meeting_id;
	//$rs = mysqli_query($SQL);
	$this->setValueInTable("ia_proceedings", "heqc_meeting_ref", $heqc_meeting_id, "application_status_ref", '7');
?>