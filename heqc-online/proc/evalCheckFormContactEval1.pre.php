<?php 
	$this->formFields["date"]->fieldValue = $this->getCurrentDate("Y-m-d");
	$this->formFields["eval_ref"]->fieldValue = (isset($_POST["contact_eval"]) && ($_POST["contact_eval"] > 0))?($_POST["contact_eval"]):("");
	$this->formFields["application_ref"]->fieldValue = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
?>
