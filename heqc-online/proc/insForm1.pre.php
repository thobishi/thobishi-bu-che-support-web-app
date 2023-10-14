<?php 
	$inst = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	$filled_in = 0;
	$filled_in = $this->getValueFromTable("HEInstitution", "HEI_id", $inst, "priv_publ");
	if (!$filled_in) {
		$this->formFields["priv_publ"]->fieldStatus = 1;
	}
	
?>
