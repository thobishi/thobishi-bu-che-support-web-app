<?php 
	$this->formFields["user_ref"]->fieldValue =  $this->currentUserID;
	$this->formFields["application_ref"]->fieldValue =  $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->formFields["comment_type"]->fieldValue =  "General";
	$this->formFields["date_added"]->fieldValue =  $this->getCurrentDate();
?>