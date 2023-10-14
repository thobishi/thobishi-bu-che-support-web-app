<?php 
//Why don't we use $this->prev_workFlowID?
if (isset($this->prev_workFlowID) && ($this->prev_workFlowID > 0)) {
	$ID = $this->prev_workFlowID;
} else {
	// We do not think we need this, but we did use this instead of
	// the top if
	if ( isset ($_POST["LAST_WORKFLOW_ID"]) ) {
		$ID = $_POST["LAST_WORKFLOW_ID"];
	} else {
		$ID = $_POST["FLOW_ID"];
	}
}

$this->formActions["previous"]->actionDest = "javascript:moveto(".$ID.");";


?>
