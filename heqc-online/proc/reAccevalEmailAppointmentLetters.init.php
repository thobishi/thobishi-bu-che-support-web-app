<?php
	// Email letters of appointment to Evaluators that the user checked.
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;

	$evals = $this->getSelectedEvaluatorsForApplication($reaccred_id,"","Reaccred");

	$files = array();
	array_push($files, WRK_DOCUMENTS."/Code_of_Ethics_and_declaration_of_conflict_of_interest.doc");
	array_push($files, WRK_DOCUMENTS."/Contract Candidacy Phase.doc");

	foreach($evals as $e){
		$checkbox = "chkEval".$e["Persnr"];

		if (   isset($_POST["$checkbox"])  &&  ($_POST["$checkbox"] == 'on')   ){
			$to 	= $e["E_mail"];

			$letter = ($e["do_summary"] == 2) ? "Letter of appointment to chair person" : "Letter of appointment";

			//$message = nl2br($this->getTextContent ("evalSelect2", $letter));
/* nl2br makes messages send with html tags in e.g. <br> */
//			$message = $this->getTextContent ("evalSelect2", $letter);

			$message = ($e["do_summary"] == 2) ? $_POST["chairman_appointment_email"] : $_POST["evaluator_appointment_email"];
			$this->misMailByName ($to, $letter, $message, "", true ,$files);

			$SQL = <<<setFlag
				UPDATE evalReport
				SET lop_isSent = 1, lop_isSent_date = now()
				WHERE reaccreditation_application_ref='$reaccred_id'
				AND Persnr_ref='$e[Persnr]'
setFlag;
			$urs = mysqli_query($conn, $SQL);
		}

	}
?>
