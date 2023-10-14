<?php 
	$inst = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	//$inst = $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID;
	$this->formFields["institution_ref"]->fieldValue = $inst;
?>