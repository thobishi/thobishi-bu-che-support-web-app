<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	//$cond_met = readPost('FLD_condition_confirm_ind');

	//if ($cond_met == 1){
		
		$sel = <<<SELECT
			SELECT ia_conditions_ref, recomm_condition_met_yn_ref
			FROM  ia_conditions_proceedings
			WHERE ia_proceedings_ref = $app_proc_id
			AND ia_proceedings_ref > 0
SELECT;
		$rs = mysqli_query($conn, $sel);
		while ($row = mysqli_fetch_array($rs)){

			$sql = <<<UPDCOND
				UPDATE ia_conditions 
				SET condition_met_yn_ref = {$row["recomm_condition_met_yn_ref"]}
				WHERE ia_conditions_id = {$row["ia_conditions_ref"]}
UPDCOND;
			$errorMail = false;
			mysqli_query($conn, $sql) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-UPDREC", $sql."  --> ".mysqli_error($conn), $errorMail);
		}	
		
		// If all conditions have been met then set recomm_dec_ref to provisionally accredited - else with conditions
		$sql = <<<COUNTCOND
			SELECT count(*) AS unmet 
			FROM ia_conditions
			WHERE application_ref = $app_id
			AND condition_term_ref IN ('s','p','l')
			AND condition_met_yn_ref != 2
COUNTCOND;
		$urs = mysqli_query($conn, $sql);
		$urow = mysqli_fetch_array($urs);
		$unmet = $urow['unmet'];
		$recomm_dec_ref = 1; //Provisionally accredited
		if ($unmet > 0){
			$recomm_dec_ref = "2";
		}

		$this->setValueInTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"recomm_decision_ref",$recomm_dec_ref);

	//}	
?>
