<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
$groupID = 15; //evaluator group
	$ref = "";
	if (isset($_POST["eval_id"]) && ($_POST["eval_id"] > 0)) {
		//$ref = "AND Persnr_ref=".$_POST["eval_id"]." AND";
		$ref = "AND Persnr_ref=".$_POST["eval_id"];
	}
	//$SQL = "SELECT * FROM `Eval_Auditors`, evalReport WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref=Persnr AND ".$ref." evalReport_status_confirm=1";
	$SQL = "SELECT * FROM `Eval_Auditors`, evalReport WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref=Persnr ".$ref;
	$RS = mysqli_query($conn, $SQL);
	while ($RS && ($row=mysqli_fetch_array($RS))) {
		if ( ($row["evalReport_date_sent"] == "1970-01-01")) {
			$SQL = "UPDATE `evalReport` SET evalReport_date_sent='".date("Y-m-d")."' WHERE application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID." AND Persnr_ref='".$row["Persnr"]."'";
			$updateRS = mysqli_query($conn, $SQL);
//			$this->setValueInTable("evalReport", "Persnr_ref", $row["Persnr"], "evalReport_date_sent", date("Y-m-d"));
		}
		$eval_user_id = $this->checkUserInDatabase($row["Title_ref"], $row["E_mail"], $row["Surname"], $row["Names"], $groupID);
		//BUG: $this->misMail($eval_user_id, "TEMP SUBJECT", "EMAIL FROM TEXT TEMPLATE!!!");
		$this->addActiveProcesses (30, $eval_user_id, 0, 0, false, $this->makeWorkFlowStringFromCurrent ("evalReport", "evalReport_id", $row["evalReport_id"]) );
		$p_id = $this->addActiveProcesses (32, $eval_user_id, 0, 0, false, $this->makeWorkFlowStringFromCurrent ("evalReport", "evalReport_id", $row["evalReport_id"]) );
		mysqli_query($conn, "UPDATE `evalReport` SET active_process_ref=".$p_id." WHERE evalReport_id=".$row["evalReport_id"]);
		if ($this->getValueFromTable("evalReport", "evalReport_id", $row["evalReport_id"], "do_summary") == 1)
		{
			$this->addActiveProcesses (73, $eval_user_id, 0, 0, false, $this->makeWorkFlowStringFromCurrent ("evalReport", "evalReport_id", $row["evalReport_id"]) );
		}
	}

/*
	$to = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref");
	$message = $this->getTextContent ("evalCheckForm4", "instAppForEval");
	$this->misEvalMail($to, "Application sent for Evaluation", $message);
*/
?>
