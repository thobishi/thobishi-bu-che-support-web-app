<?php 
	$this->formFields["application_ref"]->fieldValue = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->formFields["institution_ref"]->fieldValue = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id");
	$this->formFields["eval_ref"]->fieldValue = $this->currentUserID;
?>