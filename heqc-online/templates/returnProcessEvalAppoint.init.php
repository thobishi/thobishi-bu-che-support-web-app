<?php
		
		$userRef = '';
		$processRef = '';
		$instType = $this->getValueFromTable("HEInstitution", "HEI_id", $this->dbTableInfoArray['HEInstitution']->dbTableCurrentID, "priv_publ");		
		$proceedingRef = isset ($this->dbTableInfoArray['ia_proceedings']->dbTableCurrentID) ? $this->getValueFromTable("ia_proceedings", "ia_proceedings_id", $this->dbTableInfoArray['ia_proceedings']->dbTableCurrentID, "lkp_proceedings_ref") : 1;		
		$application_ref=$this->getValueFromTable("ia_proceedings", "ia_proceedings_id", $proceedingRef, "application_ref");
		$app_version = $this->getValueFromTable("Institutions_application", "application_id", $application_ref, "app_version");

			if($app_version =5){
				
		$processRef = 221;
		if($instType == 1){
			$userRef = 'usr_project_admin_priv';
		}else{
			$userRef = 'usr_project_admin_pub';
		}

			
	}else{

		if($proceedingRef == 1){
			$processRef = 47;
			if($instType == 1){
				$userRef = 'usr_project_admin_priv';
			}else{
				$userRef = 'usr_project_admin_pub';
			}
		}else{
			$processRef = 203;
			$userRef = 'usr_background';
		}
	}
		
		$this->returnAppToProcess($processRef, $userRef);
	
?>