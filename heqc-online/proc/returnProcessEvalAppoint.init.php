<?php
		
		$userRef = '';
		$processRef = '';
		$instType = $this->getValueFromTable("HEInstitution", "HEI_id", $this->dbTableInfoArray['HEInstitution']->dbTableCurrentID, "priv_publ");		
		$proceedingRef = isset ($this->dbTableInfoArray['ia_proceedings']->dbTableCurrentID) ? $this->getValueFromTable("ia_proceedings", "ia_proceedings_id", $this->dbTableInfoArray['ia_proceedings']->dbTableCurrentID, "lkp_proceedings_ref") : 1;		
		$application_ref=$this->getValueFromTable("ia_proceedings", "ia_proceedings_id", $proceedingRef, "application_ref");
		$app_version = $this->getValueFromTable("Institutions_application", "application_id", $application_ref, "app_version");

			if($app_version =5){
				
		$processRef = 221;
		/*if($instType == 1){
			$userRef = 'usr_project_admin_priv';
		}else{
			$userRef = 'usr_project_admin_pub';
		}*/
		$userRef =$_POST["user_ref"];
		
			
	}else{

		if($proceedingRef == 1){
			$processRef = 47;
			if($instType == 1){
				
				$userRef=$this->getValueFromTable("settings", "s_key", 'usr_project_admin_priv', "s_value");
			}else{
			
				$userRef=$this->getValueFromTable("settings", "s_key", 'usr_project_admin_pub', "s_value");
			}
		}else{
			$processRef = 203;
			
			$userRef=$this->getValueFromTable("settings", "s_key", 'usr_background', "s_value");
		}
	}

	
		//echo $userRef;
		//$this->returnAppToProcess($processRef, $userRef);

		$current_process_status = $this->getValueFromTable('active_processes','active_processes_id',$this->active_processes_id,'status');
		if ($current_process_status == 0):
			$new_user = $userRef;
			$to = $this->getValueFromTable("users", "user_id", $new_user, "email");
			$message = $this->getTextContent ("generic", "returnApplication");
			//echo $message;
			//$message = $this->getTextContent ("generic", $email_text);
			$this->misMailByName($to, "Application returned", $message);
			$id = $this->addActiveProcesses ($processRef, $new_user);
			$this->completeActiveProcesses();
		endif;



	
?>