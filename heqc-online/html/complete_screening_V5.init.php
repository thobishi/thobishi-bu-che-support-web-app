<?php


	$proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
    
	
	$today = date("Y-m-d");
	$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $proc_id, "screened_date", $today);

	$subject = "Finance indicator";
	$message = $this->getTextContent ("finance_indicator1", "Percent completion - screening");


	$usr_eval = $this->getDBsettingsValue("usr_eval_appoint_accred");


	$this->changeProcessAndUser(106,$usr_eval, $subject, $message);

	//$this->addActiveProcesses (221, $reg_usr, 11726, 0, false, "<<EXISTING>>", false);
		
?>
