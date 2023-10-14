<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	// Email letters of appointment to Directorate recommendation users that the user checked.
	$site_app_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$r = $this->getSelectedRecommUserForSiteApplication($site_app_proc_id);

	$checkbox = "chkRecomm".$r["user_id"];

	if (   isset($_POST["$checkbox"])  &&  ($_POST["$checkbox"] == 'on')   ){
		$to 	= $r["email"];
		$letter = "Letter of appointment for site recommendation";
		$message = $_POST["recommendation_appointment_email"];
		$this->misMailByName ($to, $letter, $message, "", true);

		$SQL = <<<setFlag
			UPDATE inst_site_app_proceedings
			SET lop_isSent = 1, lop_isSent_date = now()
			WHERE inst_site_app_proc_id = $site_app_proc_id
			AND recomm_user_ref = $r[user_id]
setFlag;
		$errorMail = false;
		mysqli_query($conn, $SQL) or $errorMail = true;
		$this->writeLogInfo(10, "SQL-UPDREC", $SQL."  --> ".mysqli_error($conn), $errorMail);
		
	}

?>
