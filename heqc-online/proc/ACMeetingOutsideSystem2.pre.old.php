<?php 
$this->setValueInTable ("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "application_status", 9);
if (isset($_POST['AC_Meeting_date']) && ($_POST['AC_Meeting_date'] > '')) {
	//$this->setValueInTable ("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "AC_Meeting_date", date('Y-m-d'));
	$this->setValueInTable ("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "AC_Meeting_date", $_POST['AC_Meeting_date']);
}
?>