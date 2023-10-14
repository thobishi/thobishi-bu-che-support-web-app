<?php 

if (isset($_POST["printed"]) && ($_POST["printed"] == 1)) {
	$this->setValueInTable ("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID, "reacc_applic_printed", 1);
}

?>
