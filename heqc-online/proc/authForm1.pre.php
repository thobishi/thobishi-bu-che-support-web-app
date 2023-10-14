<?php 
	$user_id = $this->dbTableInfoArray["users"]->dbTableCurrentID;

	$inst_ref = $this->getValueFromTable("users", "user_id", $user_id, "institution_ref");

	if ($inst_ref == 0) {
		$this->formFields["new_inst"]->fieldOptions = " CHECKED";
	}
?>
