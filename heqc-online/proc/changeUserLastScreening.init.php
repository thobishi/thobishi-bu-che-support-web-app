<?php 
//		$nextWorkflowID = $this->wrk_getNextWorkFlow ($this->workFlowID);
		// becuase we are in a INIT the workflow id is one to old and we need 
		//   one more
//		$nextWorkflowID = $this->wrk_getNextWorkFlow ($nextWorkflowID);
		
		//$user_ref = $this->functionSettings ($this->getValueFromTable("processes", "processes_id", $this->flowID, "proscess_supervisor"));
		
		$usr = ($this->readTFV("InstitutionType") == 1)?("usr_project_admin_priv"):("usr_project_admin_pub");
		$user_ref = $this->getDBsettingsValue($usr);
		
		$nextWorkflowID = $this->getValueFromTable("work_flows", "template", "checkForm24", "work_flows_id");
		$this->addActiveProcesses($this->flowID, $user_ref, $nextWorkflowID);
?>