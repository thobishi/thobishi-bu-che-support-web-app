<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$nr_type = $this->db->getValueFromTable("nr_programmes","id",$prog_id,"nr_national_review_id");
	switch ($nr_type) {
	 	case 'BSW':
	 		$process_id = 8;
	 		$workflow_id = 24;
	 		break;
	 	case 'LLB':
	 		$process_id = 37;
	 		$workflow_id = 252;
	 		break;
	 	default:  // Always set to the latest national review SER process
	 		$process_id = 37;
	 		$workflow_id = 252;
	 }

	$message = $_POST["FLD_return_to_institution_email"];
	$settings = $this->makeWorkFlowStringFromCurrent('nr_programmes', 'id', $prog_id);
	$files = json_decode($_POST['files']);
	$attachment = ($files[0][1]>"") ? $files : "";
	$this->parseWorkFlowString($settings);
	$cc = $this->db->getValueFromTable("users", "user_id", Settings::get('currentUserID'), "email");
	$settingsArr = Settings::get('workFlow_settings');
	if(isset($settingsArr['DBINF_screening___screening_id'])){
		unset($settingsArr['DBINF_screening___screening_id']);
	}
	if(isset($settingsArr['LOGIC_SET'])){
		unset($settingsArr['LOGIC_SET']);
	}
	$TempSettings = $this->getStringWorkFlowSettings($settingsArr);
	
	$active_processes_id = Settings::get('active_processes_id');
	$current_process_status = -1;  // initialise
	$current_process_status = $this->db->getValueFromTable('active_processes','active_processes_id',$active_processes_id,'status');
	if ($current_process_status == 0){
	
		if (isset($_POST["id_admin"]) && (count($_POST["id_admin"] > 0))) {
			$admn_arr =  $_POST["id_admin"];
			foreach ($admn_arr as $admn_id){
				$to = $this->db->getValueFromTable("users","user_id",$admn_id,"email");					
				$this->Email->misMailByName ($to, "NR-Online SER submission incomplete", $message, $cc, true ,$attachment);
				$this->addActiveProcesses ($process_id, $admn_id,$workflow_id,0,false,$TempSettings);
				$this->completeActiveProcesses();
			}

		}else{
			$adminArr = $this->getProgrammeAdministrator($prog_id);
			$this->addActiveProcesses ($process_id, $adminArr[0],$workflow_id,0,false,$TempSettings);
			$this->completeActiveProcesses();
		}
	}


 ?>
