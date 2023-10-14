<?php 
	$this->formFields["application_ref"]->fieldValue = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->showField("application_ref");
	
	$this->formFields["institution_ref"]->fieldValue = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id");
	$this->showField("institution_ref");
	
	if (!($this->formFields["site_visit"]->fieldValue > "") || ($this->formFields["site_visit"]->fieldValue == "0")) {
		$this->formFields["site_visit"]->fieldValue = "No";
	}
	if (!($this->formFields["institution_type"]->fieldValue > "") || ($this->formFields["institution_type"]->fieldValue == "0")) {
		$this->formFields["institution_type"]->fieldOptions = " CHECKED";
	}
	if (!($this->formFields["adequate_QA"]->fieldValue > "") || ($this->formFields["adequate_QA"]->fieldValue == "0")) {
		$this->formFields["adequate_QA"]->fieldOptions = " CHECKED";
	}
?>
