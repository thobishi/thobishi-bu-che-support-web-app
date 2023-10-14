<?php
//$version = $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id",$app_id, "reaccreditationVersion");
	
	$reacc_app_id  = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;

	if (isset($_POST["cancelSubmissionFlag"]) && ($_POST["cancelSubmissionFlag"] == 1)) {
	
		$this->returnReaccAppToInstBeforePayment($reacc_app_id);
	}

?>