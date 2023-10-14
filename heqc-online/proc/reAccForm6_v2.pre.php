<?php

	// set $inst to 0;
	$inst = 0;

	// check if we have a current user if else we should not do the next
    // line as it might report wrong info
	if ($this->currentUserID > "") {
		$inst = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	}

	// if we do not have a inst id by now, we are VIEWing the profile
        // and  not in the template itself...
	if (! (($inst > "") || ($inst > 0)) ) {
		$inst = $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID;
	}
	
	$this->formFields["reaccreditationVersion"]->fieldValue = 3;

	$this->formFields["menu_or_app"]->fieldValue = "menu";
	if (array_key_exists("DBINF_HEInstitution___HEI_id", $this->workFlow_settings)){
		$this->formFields["menu_or_app"]->fieldValue = ("app");
	}
?>
