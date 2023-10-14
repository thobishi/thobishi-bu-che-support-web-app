<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	if (isset($_POST["send_report_back"]) && ($_POST["send_report_back"] == 1)) {
		$this->setValueInTable("evalReport", "evalReport_id", $this->dbTableInfoArray["evalReport"]->dbTableCurrentID, "summary_done", 0);
		$this->addActiveProcesses (73, $this->getValueFromTable("users", "email", $this->getValueFromTable("Eval_Auditors", "Persnr", $this->getValueFromTable("evalReport", "evalReport_id", $this->dbTableInfoArray["evalReport"]->dbTableCurrentID, "Persnr_ref"), "E_mail"), "user_id"));
		$SQL = "SELECT active_process_ref FROM `evalReport` WHERE evalReport_status_confirm=1 AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
		$RS = mysqli_query($conn, $SQL);
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			$this->setValueInTable("active_processes", "active_processes_id", $row["active_process_ref"], "status", 0);
			$this->setValueInTable("active_processes", "active_processes_id", $row["active_process_ref"], "work_flow_ref", 0);
		}
	}else {
		if ($this->getValueFromTable("application_summery_comments_internal", "application_sum_id", $this->dbTableInfoArray["application_summery_comments_internal"]->dbTableCurrentID, "is_at_manager") == 0) {
			$usr = ($this->readTFV("InstitutionType") == 1)?("usr_manager_priv"):("usr_manager_pub");
			$new_user = $this->getDBsettingsValue($usr);
			$this->addActiveProcesses (75, $new_user);
			$this->setValueInTable("application_summery_comments_internal", "application_sum_id", $this->dbTableInfoArray["application_summery_comments_internal"]->dbTableCurrentID, "is_at_manager", 1);
		}else {
			$this->setValueInTable("application_summery_comments_internal", "application_sum_id", $this->dbTableInfoArray["application_summery_comments_internal"]->dbTableCurrentID, "is_at_manager", 0);
			$appl_ref = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
			$SQL = "SELECT * FROM lkp_sites WHERE application_ref=".$appl_ref;
			$RS = mysqli_query($conn, $SQL);
			while ($row = mysqli_fetch_array($RS)) {
				$cSQL = "SELECT * FROM siteVisit WHERE application_ref = ".$appl_ref." and site_ref=".$row["sites_ref"];
				$cRS = mysqli_query($conn, $cSQL);
				if (mysqli_num_rows($cRS) == 0){
					$insSQL = "INSERT INTO siteVisit (application_ref, site_ref) VALUES ('".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."', '".$row["sites_ref"]."')";
					$insRS = mysqli_query($conn, $insSQL);
					if ($this->getValueFromTable("HEInstitution","HEI_id",$this->getValueFromTable("Institutions_application","application_id",$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,"institution_id"),"priv_publ") != 1) $tmpUser = "user_priv_site_visit";
					$usr = ($this->readTFV("InstitutionType") == 1)?("usr_project_admin_priv"):("usr_project_admin_pub");
					$new_user = $this->getDBsettingsValue($usr);
					$this->addActiveProcesses (25, $new_user, 0, 0, false, $this->makeWorkFlowStringFromCurrent ("siteVisit", "siteVisit_id", mysqli_insert_id($conn)) );
					$message = $this->getTextContent ("evalReportSumScreenForm9", "SitevisitInform");
					$this->misMail($new_user, "Site visit decision.", $message);
				}
			}
		}
	}
?>
