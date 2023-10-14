<?php 
$this->formFields["active_processes_ref"]->fieldValue = $this->active_processes_id;
if (isset($_POST["CMT_ID"]) && $_POST["CMT_ID"] > ""){
	$this->formFields["active_processes_ref"]->fieldValue = $_POST["CMT_ID"];
}
$this->formFields["user_ref"]->fieldValue = $this->currentUserID;
?>