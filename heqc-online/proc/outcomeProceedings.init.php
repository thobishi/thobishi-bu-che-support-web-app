<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$reacc_id = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"reaccreditation_application_ref");

	$sql = <<<SQL
			SELECT lkp_proceedings_ref, proceeding_status_ind, heqc_board_decision_ref
			FROM ia_proceedings
			WHERE ia_proceedings_id = $app_proc_id
SQL;
	$rs = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($rs);

	//$processed = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"proceeding_status_ind");
	$processed = $row['proceeding_status_ind'];
	$proc_outcome = $row['heqc_board_decision_ref'];
	$prev_proc_type =  $row['lkp_proceedings_ref'];
	//echo "Previous proceeding type: " . $prev_proc_type;

	if ($processed == 0){	// This proceeding has not been processed

		//$proc_outcome = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"heqc_board_decision_ref");
		switch ($proc_outcome){
		case 1: // Accredited
		case 5: // reaccredited
			//Close current proceedings because it has been through all processes and meetings and reached a final outcome for the proceeding.
			$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "proceeding_status_date", date("Y-m-d"));
			$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "proceeding_status_ind", '1');	
			$this->defaultOutcome("final",$app_proc_id);
			break;
		case 2: //Conditions
		case 6: // Reaccreditation conditions
			$cond_term = readPost('cond_term');
			$is_condition = readPost('FLD_condition_doc');
			if ($is_condition > 0){

				// Create conditional proceedings
				$proc_type = ($proc_outcome == 2) ? 4 : 6;  //conditional proceedings
				$new_proc_id = $this->create_proceedings($app_id, $proc_type, $app_proc_id, $reacc_id);

				// Create active process for conditional proceedings
				$this->workFlow_settings["LOGIC_SET"] = $this->logicToString();
				if (isset($this->workFlow_settings["DBINF_evalReport___evalReport_id"])){
					unset($this->workFlow_settings["DBINF_evalReport___evalReport_id"]);
				}
				if (isset($this->workFlow_settings["DBINF_payment___payment_id"])){
					unset($this->workFlow_settings["DBINF_payment___payment_id"]);
				}

				$proc_user_id = $this->getDBsettingsValue("usr_gatekeeper_proceeding");
				$settings = $this->makeWorkFlowStringFromCurrent('ia_proceedings', 'ia_proceedings_id', $new_proc_id);
				$this->addActiveProcesses (193, $proc_user_id,0,0,false,$settings);
				//$this->completeActiveProcesses();

				//if ($prev_proc_type != 4){ 
				//2017-11-09 Richard: Updated for conditional re-accreditiation
				if (($prev_proc_type != 4) && ($prev_proc_type != 6)){ 
					// Store the conditions for the application.  This should occur once only per application.  An outcome of Provisional 
					// accreditiation with conditions can occur for a candidacy, representation or deferral proceeding.
					// These conditions have been approved by the HEQC committee and will not change.  
					// These conditions are processed by type (short, prior or long) and indicated as being met or not by evaluators 
					// and HEQC recommendation users.  
					// Conditions proceedings do not follow the regular flow that all the other proceedings follow as it does not get 
					// a recommendation.
					// NB NB This insert must occur on this proceedings before the next proceedings is created in order to 
					// copy the data from this proceedings to the next proceedings
					$ins = <<<INSSQL
						INSERT INTO ia_conditions (ia_conditions_id, application_ref,  decision_reason_condition,  condition_term_ref,  criterion_min_standard )
						SELECT NULL, $app_id, decision_reason_condition,  condition_term_ref,  criterion_min_standard 
						FROM ia_proceedings_heqc_decision
						WHERE ia_proceedings_ref = $app_proc_id
INSSQL;
//echo $ins;
					$errorMail = false;
					$rs = mysqli_query($conn, $ins) or $errorMail = true;
					$this->writeLogInfo(10, "SQL-INSREC", $ins."  --> ".mysqli_error($conn), $errorMail);


				}
				if (count($cond_term) > 0){
					foreach($cond_term as $c){
						// Update the conditions with the proceeding in which they'll be processed. Don't overwrite any previous proceedings references.
						$upd = <<<UPDSQL
							INSERT INTO ia_conditions_proceedings (ia_conditions_proceedings_id, ia_conditions_ref, ia_proceedings_ref, decision_reason_condition,  condition_term_ref,  criterion_min_standard )
							SELECT NULL, ia_conditions_id, $new_proc_id, decision_reason_condition,  condition_term_ref,  criterion_min_standard 
							FROM ia_conditions
							WHERE application_ref = $app_id
							AND condition_term_ref = '{$c}'
							AND condition_met_yn_ref != 2
UPDSQL;
						$errorMail = false;
						$rs = mysqli_query($conn, $upd) or $errorMail = true;
						$this->writeLogInfo(10, "SQL-UPDREC", $upd."  --> ".mysqli_error($conn), $errorMail);
					}
				}

				//Close current proceedings because it has been through all processes and meetings and reached a final outcome for the proceeding.
				$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "proceeding_status_date", date("Y-m-d"));
				$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "proceeding_status_ind", '1');	
				$this->defaultOutcome("final",$app_proc_id);

			} else {
				echo "The conditions document has not been uploaded.  This process cannot continue.  Please contact system support.";
				die();
			}

			break;
		case 3:
		case 7:
			$is_representation = readPost('FLD_representation_doc');
			if ($is_representation > 0){
				// Create representation proceedings
				$proc_type = ($proc_outcome == 3) ? 3 : 7;  //representation proceedings
				$new_proc_id = $this->create_proceedings($app_id, $proc_type, $app_proc_id, $reacc_id);

				// Create active process for representation proceedings
				$this->workFlow_settings["LOGIC_SET"] = $this->logicToString();
				if (isset($this->workFlow_settings["DBINF_evalReport___evalReport_id"])){
					unset($this->workFlow_settings["DBINF_evalReport___evalReport_id"]);
				}
				if (isset($this->workFlow_settings["DBINF_payment___payment_id"])){
					unset($this->workFlow_settings["DBINF_payment___payment_id"]);
				}

				$proc_user_id = $this->getDBsettingsValue("usr_gatekeeper_proceeding");
				$settings = $this->makeWorkFlowStringFromCurrent('ia_proceedings', 'ia_proceedings_id', $new_proc_id);
				$this->addActiveProcesses (193, $proc_user_id,0,0,false,$settings);
				
			}
			//Close current proceedings because it has been through all processes and meetings and reached a final outcome for the proceeding.
			$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "proceeding_status_date", date("Y-m-d"));
			$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "proceeding_status_ind", '1');	
			$this->defaultOutcome("final",$app_proc_id);

			break;
		case 4: //Deferred
		case 8: // Deferred reaccreditation
			// 2016-02-12 Robin: Proceedings with an outcome of deferred should not be closed.  Deferrals do not always require documents 
			// from the institution.
			//$is_deferral = readPost('FLD_deferral_doc');
			//if ($is_deferral > 0){
				// Create deferral proceedings
				$proc_type = ($proc_outcome == 4) ? 2 : 8;  //deferraL proceedings
				$new_proc_id = $this->create_proceedings($app_id, $proc_type, $app_proc_id, $reacc_id);

				// Create active process for deferral proceedings
				$this->workFlow_settings["LOGIC_SET"] = $this->logicToString();
				if (isset($this->workFlow_settings["DBINF_evalReport___evalReport_id"])){
					unset($this->workFlow_settings["DBINF_evalReport___evalReport_id"]);
				}
				if (isset($this->workFlow_settings["DBINF_payment___payment_id"])){
					unset($this->workFlow_settings["DBINF_payment___payment_id"]);
				}

				$proc_user_id = $this->getDBsettingsValue("usr_gatekeeper_proceeding");
				$settings = $this->makeWorkFlowStringFromCurrent('ia_proceedings', 'ia_proceedings_id', $new_proc_id);
				$this->addActiveProcesses (193, $proc_user_id,0,0,false,$settings);
				//$this->completeActiveProcesses();
						//Close current proceedings because it has been through all processes and meetings and reached a final outcome for the proceeding.
				$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "proceeding_status_date", date("Y-m-d"));
				$this->setValueInTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "proceeding_status_ind", '1');	
				$this->defaultOutcome("final",$app_proc_id);

			//}
			break;
		default:
		}
	
	}
?>
