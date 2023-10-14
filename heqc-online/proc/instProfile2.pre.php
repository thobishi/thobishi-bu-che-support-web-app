<?php 
	$inst = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	$this->formFields["institution_ref"]->fieldValue = $inst;
	$this->formFields["main_site"]->fieldValue = 1;
?>