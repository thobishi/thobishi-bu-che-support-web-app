<?php
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	
	// AC decision has been confirmed. Default the HEQC decision to the recommendation. It will then be edited in 
	// the HEQC Meeting.
	$this->defaultOutcome("HEQC",$app_proc_id);	
?>
