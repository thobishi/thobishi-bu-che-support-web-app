<?php 
//		$nextWorkflowID = $this->wrk_getNextWorkFlow ($this->workFlowID);
		// becuase we are in a INIT the workflow id is one to old and we need 
		//   one more
//		$nextWorkflowID = $this->wrk_getNextWorkFlow ($nextWorkflowID);
		
		$nextWorkflowID = $this->getValueFromTable("work_flows", "template", "evalCheckForm1b", "work_flows_id");
		$this->addActiveProcesses($this->flowID, $this->getDBsettingsValue("usr_manager_priv"), $nextWorkflowID);
?>