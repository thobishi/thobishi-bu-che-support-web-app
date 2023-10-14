<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$site_app_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;

	$site_arr = $this->getSiteVisitsForApp($site_app_id);
	$groupID = 15; //evaluator group
	
	foreach($site_arr as $s){

		$site_visit_id = $s["inst_site_visit_id"];
		$subject = "Site visit details - " . $s["site_name"];
		$message = readPost('email_sitevisit_'.$s["inst_site_visit_id"]);
		$cc_usr_id = $this->getDBsettingsValue('usr_site_panel');
		$cc = $this->getValueFromTable("users", "user_id", $cc_usr_id, "email");

		// Get all attachments for each site visit
		$files = array();
		$doc_arr = $this->getSiteVisitAttachments($site_visit_id);
		foreach ($doc_arr AS $doc_id => $title){
				$doc_url = $this->getValueFromTable("documents", "document_id", $doc_id,"document_url");
				$doc_name = $this->getValueFromTable("documents", "document_id", $doc_id,"document_name");
				array_push($files,array(OCTODOC_DIR.$doc_url,$doc_name));
		}

		$eval_arr = $this->getSelectedEvaluatorsForSiteVisits($site_visit_id, 'visit');
		foreach ($eval_arr as $e){
			$checkbox = "chkEvalSite".$e['inst_site_visit_eval_id'];

			if (   isset($_POST["$checkbox"])  &&  ($_POST["$checkbox"] == 'on')   ){
				$to = $e['E_mail'];

				$this->misMailByName ($to, $subject, $message, $cc, true ,$files);
				
				$SQL = <<<setFlag
					UPDATE inst_site_visit_eval
					SET panel_letter_sent_date = now()
					WHERE inst_site_visit_ref = $site_visit_id
					AND evaluator_persnr = $e[Persnr]
setFlag;
				$errorMail = false;
				mysqli_query($conn, $SQL) or $errorMail = true;
				$this->writeLogInfo(10, "SQL-UPDREC", $SQL."  --> ".mysqli_error($conn), $errorMail);
			}
			
			if (!($e["user_ref"] > 0)){
				// If user is not found then a user is created.
				$eval_user_id = $this->checkUserInDatabase($e["Title_ref"], $e["E_mail"], $e["Surname"], $e["Names"], $groupID);

				//inserts the user_id into the Eval_Auditors table
				$SQL = "UPDATE `Eval_Auditors` SET user_ref ='".$eval_user_id."' WHERE Persnr='".$e["Persnr"]."'";
				$errorMail = false;
				mysqli_query($conn, $SQL) or $errorMail = true;
				$this->writeLogInfo(10, "SQL-UPDREC", $SQL."  --> ".mysqli_error($conn), $errorMail);
			}
		}
	}

?>
