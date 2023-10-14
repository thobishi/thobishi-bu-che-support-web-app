<?php 
if (isset($this->prev_workFlowID) && ($this->prev_workFlowID > 0)) {
	$ID = $this->prev_workFlowID;
} else {
	if ( isset ($_POST["LAST_WORKFLOW_ID"]) ) {
		$ID = $_POST["LAST_WORKFLOW_ID"];
	} else {
		$ID = $_POST["FLOW_ID"];
	}
}

$this->formActions["previous"]->actionDest = "javascript:moveto(".$ID.");";
?>
