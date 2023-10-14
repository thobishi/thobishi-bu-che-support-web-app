<?php 

	// switch the previous oof if we are not the Administrator of the Program
	if ($this->currentUserID != $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref")) {
		$this->formActions["previous"]->actionMayShow = false;
	}

	
?>
