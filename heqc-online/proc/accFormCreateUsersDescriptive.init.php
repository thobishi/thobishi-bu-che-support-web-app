<?php 

if ( isset ($_POST["user_ref"]) ) {
	if ($_POST["user_ref"] != $this->currentUserID) {
		$message = $this->getTextContent("accFormCreateUsers", "distributeApplicationInternal");
		$this->misMail ($_POST["user_ref"], "Application for Accreditation", $message);
		$nextWorkflowID = $this->wrk_getNextWorkFlow ($this->workFlowID);
		// because we are in a INIT the workflow id is one to old and we need
		//   one more
		$nextWorkflowID = $this->wrk_getNextWorkFlow ($nextWorkflowID);
		$this->addActiveProcesses($this->flowID, $_POST["user_ref"], $nextWorkflowID);
	}
}

?>
