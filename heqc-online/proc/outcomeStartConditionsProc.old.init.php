<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

	$processed = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"proceeding_status_ind");

	if ($processed == 0){	// This proceeding has not been processed

		// 2011-08-30: This processing must only take place when phase 2 is completed.
		//$message = readPost('email_content');
		//$subject = "Accreditation decision";
		//$inst_adm = $this->getInstitutionAdministrator($app_id);
		//$inst_adm_id = $inst_adm[0];
		//$this->misMail ($inst_adm_id, $subject, $message, "", true);

		//Close current proceedings because it has been through all processes and meetings and reached a final outcome for the proceeding.
		$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "proceeding_status_date", date("Y-m-d"));
		$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "proceeding_status_ind", '1');
		$this->defaultOutcome("final",$app_proc_id);	

		// Create conditional proceedings
		//$proc_type = 4;
		//$new_proc_id = $this->create_proceedings($app_id, $proc_type, $app_proc_id);

		// Create active process for deferral proceedings
		//$this->workFlow_settings["LOGIC_SET"] = "";
		//$settings = $this->makeWorkFlowStringFromCurrent('ia_proceedings', 'ia_proceedings_id', $new_proc_id);

		//$this->addActiveProcesses (171, $inst_adm_id,0,0,false,$settings);

		//$this->completeActiveProcesses();
	}	

?>
