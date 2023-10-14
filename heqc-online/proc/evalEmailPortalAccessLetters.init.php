<?php
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
    if ($conn->connect_errno) {
        $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
        printf("Error: %s\n".$conn->error);
        exit();
    }
// Email letters of appointment to Evaluators that the user checked.

	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

	$groupID = 15; //evaluator group

	$criteria = array("evalReport_status_confirm = 1");

	//2012-11-09 Robin: Move evaluators to proceeding level
	//$evals = $this->getSelectedEvaluatorsForApplication($app_id, $criteria);
	$evals = $this->getSelectedEvaluatorsForApplication($app_proc_id,$criteria,"Proceedings");

	foreach($evals as $e){

	// Portal Access and date that evaluator received access to the application must only be recorded once.
		if ( ($e["evalReport_date_sent"] == "1000-01-01")) {

			$to 	= $e["E_mail"];
			$letter = "Letter on Evaluator Portal Access";
			$message = $_POST["evaluator_portal_email"]; 

			$priv_publ = $this->checkAppPrivPubl($app_id);
			$cc_usr = ($priv_publ == 1) ? "usr_eval_appoint_priv" : "usr_eval_appoint_pub";
			$cc_usr_id = $this->getDBsettingsValue($cc_usr);
			$cc = $this->getValueFromTable("users", "user_id", $cc_usr_id, "email");

			$this->misMailByName ($to, $letter, $message, $cc, true);

			//2012-11-09 Robin: Move evaluators to proceeding level
			//$SQL = "UPDATE `evalReport` SET evalReport_date_sent='".date("Y-m-d")."' WHERE application_ref=".$app_id ." AND Persnr_ref='".$e["Persnr"]."'";
			$SQL = "UPDATE `evalReport` SET evalReport_date_sent='".date("Y-m-d")."' WHERE ia_proceedings_ref=".$app_proc_id ." AND Persnr_ref='".$e["Persnr"]."'";
			$errorMail = false;
			mysqli_query($conn, $SQL) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-UPDREC", $SQL."  --> ".mysqli_error($conn), $errorMail);


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
