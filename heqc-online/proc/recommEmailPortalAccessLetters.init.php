<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	// Email letters of appointment to Directorate Recommendation users that the user checked.
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$groupID = 19; //recommendation group
	$criteria = array("lop_status_confirm = 1");
	$r = $this->getSelectedRecommUserForAppProceeding($app_proc_id, $criteria);

	// Portal Access and date that recommendation user received access to the application must only be recorded once.
	if ( ($r["portal_sent_date"] == "1000-01-01")) {
		$to 	= $r["email"];
		$letter = "Letter on Recommendation Portal Access";
		$message = $_POST["recommendation_portal_email"];
		$this->misMailByName ($to, $letter, $message, "", true);
		$today = date("Y-m-d");
		$SQL = <<<USQL
			UPDATE ia_proceedings
			SET portal_sent_date='$today' 
			WHERE ia_proceedings_id = $app_proc_id 
			AND recomm_user_ref = $r[recomm_user_ref]
USQL;

		$errorMail = false;
		mysqli_query($conn, $SQL) or $errorMail = true;
		$this->writeLogInfo(10, "SQL-UPDREC", $SQL."  --> ".mysqli_error($conn), $errorMail);
	}
?>
