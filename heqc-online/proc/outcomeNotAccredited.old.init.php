<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

	$processed = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"proceeding_status_ind");
	
	if ($processed == 0){	// This proceeding has not been processed

		$proc_outcome = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"heqc_board_decision_ref");
		switch ($proc_outcome){
		case 3:
			$is_representation = readPost('FLD_representation_doc');
			if ($is_representation > 0){
				// Create representation proceedings
				$proc_type = 3;  //representation proceedings
				$new_proc_id = $this->create_proceedings($app_id, $proc_type, $app_proc_id);

				// Create active process for representation proceedings
var_dump($this->workFlow_settings);
				//$this->workFlow_settings["LOGIC_SET"] = "";
				if (isset($this->workFlow_settings["DBINF_screening___screening_id"])){
					unset($this->workFlow_settings["DBINF_screening___screening_id"]);
				}
				if (isset($this->workFlow_settings["DBINF_evalReport___evalReport_id"])){
					unset($this->workFlow_settings["DBINF_evalReport___evalReport_id"]);
				}
				if (isset($this->workFlow_settings["LOGIC_SET"])){
					unset($this->workFlow_settings["LOGIC_SET"]);	
				}
var_dump($this->workFlow_settings);

				$proc_user_id = $this->getDBsettingsValue("usr_gatekeeper_proceeding");
echo "<br />".$proc_user_id;
				$settings = $this->makeWorkFlowStringFromCurrent('ia_proceedings', 'ia_proceedings_id', $new_proc_id);
echo "<br />".$settings;
				$this->addActiveProcesses (193, $proc_user_id,0,0,false,$settings);

				//$this->completeActiveProcesses();
			}
			break;
		default:
		}
die();		
		// 2011-08-30: This processing must only take place when phase 2 is completed.
		//$message = readPost('email_content');
		//$subject = "Accreditation recommendation";
		//$inst_adm = $this->getInstitutionAdministrator($app_id);
		//$inst_adm_id = $inst_adm[0];
		//$this->misMail ($inst_adm_id, $subject, $message, "", true);

		//Close current proceedings because it has been through all processes and meetings and reached a final outcome for the proceeding.
		$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "proceeding_status_date", date("Y-m-d"));
		$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "proceeding_status_ind", '1');	
		$this->defaultOutcome("final",$app_proc_id);
		
	}	
?>
