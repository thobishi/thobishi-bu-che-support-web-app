<?php

	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

	$heqc_decision = readPost('FLD_heqc_board_decision_ref');
	//$decision_doc = readPost('FLD_decision_doc');

	$heqc_meeting_ref = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"heqc_meeting_ref");
	if ($heqc_meeting_ref > 0){
		$decision_date = $this->getValueFromTable("HEQC_Meeting","heqc_id",$heqc_meeting_ref,"heqc_start_date");
	} else {
		$decision_date = date("Y-m-d");
	}
	
	// Default final outcome on application record
	$this->setValueInTable("Institutions_application", "application_id", $app_id, "AC_desision", $heqc_decision);
	$this->setValueInTable("Institutions_application", "application_id", $app_id, "AC_Meeting_date", $decision_date);
	// 2011-09-09 Robin: Don't think we should automatically override documents.  The documents will be on the proceeding records.
	//$this->setValueInTable("Institutions_application", "application_id", $app_id, "AC_conditions_doc", $decision_doc);
	

?>