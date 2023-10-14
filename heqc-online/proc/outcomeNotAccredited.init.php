<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

	$processed = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"proceeding_status_ind");
	
	if ($processed == 0){	// This proceeding has not been processed

		$message = readPost('email_content');
		$subject = "Accreditation recommendation";
		$inst_adm = $this->getInstitutionAdministrator($app_id);
		$inst_adm_id = $inst_adm[0];
		// 2011-08-30: This processing must only take place when phase 2 is completed.
		//$this->misMail ($inst_adm_id, $subject, $message, "", true);

		//Close current proceedings because it has been through all processes and meetings and reached a final outcome for the proceeding.
		$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "proceeding_status_date", date("Y-m-d"));
		$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "proceeding_status_ind", '1');	
		$this->defaultOutcome("final",$app_proc_id);	
	}	

?>
