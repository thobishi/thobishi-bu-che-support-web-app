<?php
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	
	// Update conditions for the application with the outcome of met or not met.
	$this->defaultOutcome("CONDITIONS",$app_proc_id);	
?>
