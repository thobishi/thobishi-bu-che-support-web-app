<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	// Email letters of appointment to Directorate Recommendation users that the user checked.
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$criteria = array("lop_status_confirm = 1");
	$r = $this->getSelectedRecommUserForSiteApplication($site_proc_id, $criteria);

	$checkbox = "chkRecomm".$r["user_id"];
	if (   isset($_POST["$checkbox"])  &&  ($_POST["$checkbox"] == 'on')   ){
		
		$to 	= $r["email"];
		$letter = "Site Visit Recommendation Portal Access";
		$message = $_POST["recommendation_portal_email"];
		$this->misMailByName ($to, $letter, $message, "", true);
		$today = date("Y-m-d");
		$SQL = <<<USQL
			UPDATE inst_site_app_proceedings
			SET portal_sent_date='$today' 
			WHERE inst_site_app_proc_id = $site_proc_id 
			AND recomm_user_ref = $r[user_id]
USQL;
		$errorMail = false;
		mysqli_query($conn, $SQL) or $errorMail = true;
		$this->writeLogInfo(10, "SQL-UPDREC", $SQL."  --> ".mysqli_error($conn), $errorMail);

	}
?>
