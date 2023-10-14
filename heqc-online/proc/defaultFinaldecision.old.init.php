<?php
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	
	// HEQC decision has been confirmed. Set the decision on the application record for historic reports. 

	$this->defaultOutcome("final",$app_proc_id);	
?>
