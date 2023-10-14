<?php 
$app_id = $this->dbTableInfoArray['Institutions_application']->dbTableCurrentID;
// Commented out by Robin: HEQC not yet using the system to schedule meetings. Using AC_Meeting_date instead.
//if (isset($_POST['FLD_AC_Meeting_ref']) && ($_POST['FLD_AC_Meeting_ref'] > '')) {

	//get date of meeting that you've passed through
//	$ac_meeting_date = $this->getValueFromTable("AC_Meeting", "ac_id", $_POST['FLD_AC_Meeting_ref'], "ac_start_date");

	//write this date to the Institutions_application table as the AC_Meeting_date
//	$this->setValueInTable ("Institutions_application", "application_id", $app_id, "AC_Meeting_date", $ac_meeting_date);
//}
?>