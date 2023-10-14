<?php
// Email letters of appointment to Evaluators that the user checked.
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
	$groupID = 15; //evaluator group

	$criteria = array("evalReport_status_confirm = 1");
	$evals = $this->getSelectedEvaluatorsForApplication($reaccred_id, $criteria, "Reaccred");

	foreach($evals as $e){

	// Portal Access and date that evaluator received access to the application must only be recorded once.
		if ( ($e["evalReport_date_sent"] == "1970-01-01")) {

			$to 	= $e["E_mail"];
			$letter = "Letter on Evaluator Portal Access";
			$message = $_POST["evaluator_portal_email"];
			$this->misMailByName ($to, $letter, $message, "", true);

			$SQL = "UPDATE `evalReport` SET evalReport_date_sent='".date("Y-m-d")."' WHERE reaccreditation_application_ref=".$reaccred_id ." AND Persnr_ref='".$e["Persnr"]."'";
			$updateRS = mysqli_query($conn, $SQL);

			// 2009-07-27 Robin - Only check and insert a user if a user has not already beed assigned.  Reason is that the 
			// check is done on email address and a CHE user may change the evaluator email address - causing a new user to be 
			// created or the user to be updated to an incorrect user. 
			// We may need to add functionality for a CHE user to view and change evluator signons.

			if (!($e["user_ref"] > 0)){
				// If user is not found then a user is created.
				$eval_user_id = $this->checkUserInDatabase($e["Title_ref"], $e["E_mail"], $e["Surname"], $e["Names"], $groupID);

				//inserts the user_id into the Eval_Auditors table
				$SQL = "UPDATE `Eval_Auditors` SET user_ref ='".$eval_user_id."' WHERE Persnr='".$e["Persnr"]."'";
				$updateRS = mysqli_query($conn, $SQL);
			}
		}

	}


?>
