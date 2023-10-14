<?php 
	$this->formFields["application_ref"]->fieldValue = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

	// ReSubmitted applications before 15 Oct 2006 may come through New Submission again.
	// Reset screening validation flag to Checklister so that correct menu items show.
	// If it is set to 1: Menu options for management display for the checklister.

	if (isset($this->dbTableInfoArray["screening"]->dbTableCurrentID) && $this->dbTableInfoArray["screening"]->dbTableCurrentID > 0){
		$this->setValueInTable("screening", "screening_id", $this->dbTableInfoArray["screening"]->dbTableCurrentID, "proc_to_manager", 0);
	}
?>