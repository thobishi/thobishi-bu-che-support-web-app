<?php 
	if (!(array_key_exists ( "DBINF_HEInstitution___HEI_id", $this->workFlow_settings))) {
		$this->workFlow_settings["DBINF_HEInstitution___HEI_id"] = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	}
?>