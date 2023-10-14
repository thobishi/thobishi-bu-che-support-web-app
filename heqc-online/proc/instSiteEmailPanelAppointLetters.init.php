<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$site_app_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$eval_arr = $this->getSelectedEvaluatorsForSiteVisits($site_app_id, 'applic');

	foreach($eval_arr as $e){
		$files = array();
		$checkbox = "chkEval".$e["Persnr"];

		if (   isset($_POST["$checkbox"])  &&  ($_POST["$checkbox"] == 'on')   ){
			$to = $e['E_mail'];
			$subject = "Invitation to conduct site visit";
			$message = readPost('email_persnr_'.$e["Persnr"]);
			
			$contractDoc = new octoDoc($e['eval_contract_doc']);
			if ($contractDoc->isDoc()){
				array_push($files, $contractDoc->getFilepath());
			}

			$cc_usr = "usr_site_panel";
			$cc_usr_id = $this->getDBsettingsValue($cc_usr);
			$cc = $this->getValueFromTable("users", "user_id", $cc_usr_id, "email");

			$this->misMailByName ($to, $subject, $message, $cc, true ,$files);
			
			$SQL = <<<setFlag
				UPDATE inst_site_app_proceedings_eval
				SET appoint_email_sent_date = now()
				WHERE inst_site_app_proc_ref = $site_app_id
				AND evaluator_persnr = $e[Persnr]
setFlag;
			$errorMail = false;
			mysqli_query($conn, $SQL) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-UPDREC", $SQL."  --> ".mysqli_error($conn), $errorMail);
		}
	}
?>
