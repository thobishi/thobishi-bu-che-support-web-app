<?php
	$reacc_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
	if (isset($_POST['FLD_reacc_decision_ref']) && ($_POST['FLD_reacc_decision_ref'] > '')) {
		$this->setValueInTable ("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reacc_id, "reacc_active_ind", 1);
	}
?>