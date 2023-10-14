<?php 
$app_id = $this->dbTableInfoArray['Institutions_application']->dbTableCurrentID;
// Commented out by Robin: HEQC not yet using the system to schedule meetings. Using AC_Meeting_date instead.
//if (isset($_POST['FLD_AC_Meeting_ref']) && ($_POST['FLD_AC_Meeting_ref'] > '')) {
if (($_POST['FLD_withdrawn_decision_date'] = '0000-00-00')) {
echo $_POST['FLD_withdrawn_decision_date'];
	//get date of meeting that you've passed through
	//$withdrawn_decision_date = $this->getValueFromTable("Institutions_application", "ac_id", $_POST['withdrawn_decision_date'], "");
$withdrawn_decision_date = null;

	//write this date to the Institutions_application table as the AC_Meeting_date
	$this->setValueInTable ("Institutions_application", "application_id", $app_id, "withdrawn_decision_date", $withdrawn_decision_date);
}
?>