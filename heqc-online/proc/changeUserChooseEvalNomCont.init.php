<?php 
//		$nextWorkflowID = $this->wrk_getNextWorkFlow ($this->workFlowID);
		// becuase we are in a INIT the workflow id is one to old and we need 
		//   one more
//		$nextWorkflowID = $this->wrk_getNextWorkFlow ($nextWorkflowID);
		
		$nextWorkflowID = $this->getValueFromTable("work_flows", "template", "evalCheckForm1d", "work_flows_id");
		$this->addActiveProcesses($this->flowID, $this->getDBsettingsValue(($this->readTFV("InstitutionType") == 1)?("usr_project_admin_priv"):("usr_project_admin_pub")), $nextWorkflowID);
?>