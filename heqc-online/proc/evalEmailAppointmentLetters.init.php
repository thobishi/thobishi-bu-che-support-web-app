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
	$proc_type = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"lkp_proceedings_ref");

	//2012-11-09 Robin: Move evaluators to proceeding level
	//$evals = $this->getSelectedEvaluatorsForApplication($app_id);
	$evals = $this->getSelectedEvaluatorsForApplication($app_proc_id,"","Proceedings");


	foreach($evals as $e){
		$files = array();
		array_push($files, WRK_DOCUMENTS."/Code_of_ethics_June_2005.doc");
		$checkbox = "chkEval".$e["Persnr"];

		if (   isset($_POST["$checkbox"])  &&  ($_POST["$checkbox"] == 'on')   ){
			$to 	= $e["E_mail"];

			$letter = ($e["do_summary"] == 2) ? "Letter of appointment to chair person" : "Letter of appointment";
			if ($proc_type == 4){
				$letter = "Letter of appointment for conditional proceeding"; 
			}

			$contractDoc = new octoDoc($e['eval_contract_doc']);
			if ($contractDoc->isDoc()){

			array_push($files, $contractDoc->getFilepath());
		}
//$message = nl2br($this->getTextContent ("evalSelect2", $letter));
/* nl2br makes messages send with html tags in e.g. <br> */
//	$message = $this->getTextContent ("evalSelect2", $letter);

			$message = ($e["do_summary"] == 2) ? $_POST["chairman_appointment_email"] : $_POST["evaluator_appointment_email"];

			$priv_publ = $this->checkAppPrivPubl($app_id);
			$cc_usr = ($priv_publ == 1) ? "usr_eval_appoint_priv" : "usr_eval_appoint_pub";
			$cc_usr_id = $this->getDBsettingsValue($cc_usr);
			$cc = $this->getValueFromTable("users", "user_id", $cc_usr_id, "email");

			$this->misMailByName ($to, $letter, $message, $cc, true ,$files);

//			$SQL = <<<setFlag
//				UPDATE evalReport
//				SET lop_isSent = 1, lop_isSent_date = now()
//				WHERE application_ref='$app_id'
//				AND Persnr_ref='$e[Persnr]' 
//setFlag;
			$SQL = <<<setFlag
				UPDATE evalReport
				SET lop_isSent = 1, lop_isSent_date = now()
				WHERE ia_proceedings_ref = $app_proc_id
				AND Persnr_ref='$e[Persnr]'
setFlag;


echo $app_proc_id;
echo $SQL;
 file_put_contents('php://stderr', print_r($app_proc_id, TRUE));
 
 $errorMail = false;
			
			$urs = mysqli_query($this->getDatabaseConnection(), $SQL) or $errorMail=true;
			

$this->writeLogInfo(10, "SQL-UPDREC", $SQL."  --> ".mysqli_error(), $errorMail);	
			
			
			
			
		}

	}
?>
