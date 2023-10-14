<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	// Applications that have been assigned
	$heqc_meeting_id = $this->dbTableInfoArray["HEQC_Meeting"]->dbTableCurrentID;

	$apps_arr = array();

	// Programme applications that will be tabled at the meeting
	$aSQL = <<<acSQL
		SELECT p.ia_proceedings_id 
		FROM ia_proceedings p
		WHERE  p.proceeding_status_ind = 0
 		AND p.application_status_ref = 5
acSQL;
	$ars = mysqli_query($conn, $aSQL);
	while ($row = mysqli_fetch_array($ars)) {
		$checkbox = "notAssigned".$row["ia_proceedings_id"];
		if (   isset($_POST["$checkbox"])  &&  ($_POST["$checkbox"] == 'on')   ){
			$updateSQL = "UPDATE `ia_proceedings` SET  `heqc_meeting_ref` = '".$heqc_meeting_id."', `application_status_ref` = '6' WHERE `ia_proceedings`.`ia_proceedings_id` =".$row["ia_proceedings_id"];
			mysqli_query($conn,$updateSQL) or die(mysqli_error());
		}
	}

	//-------------------------------------------------------------------------------------------------------------------------------
	// Sites that will be tabled at the meeting
	$sSQL = <<<siteSQL
			SELECT inst_site_app_proc_id
			FROM inst_site_app_proceedings
			WHERE application_status_ref = 5
			AND site_proceeding_status_ind = 0
siteSQL;

	$srs = mysqli_query($conn, $sSQL);
	while ($srow = mysqli_fetch_array($srs)) {
		$checkbox = readPost("noSite".$srow["inst_site_app_proc_id"]);
		if ( $checkbox == 'on' ) {
			$updateSQL = " UPDATE inst_site_app_proceedings 
				SET  application_status_ref = '6',heqc_meeting_ref = {$heqc_meeting_id} 
				WHERE inst_site_app_proceedings.inst_site_app_proc_id = {$srow["inst_site_app_proc_id"]}";
			$errorMail = false;
			mysqli_query($conn, $updateSQL) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-UPDREC", "Assign site application to AC meeting:"  . $updateSQL."  --> ".mysqli_error(), $errorMail);

			$fileName = "site_recomm_" . $srow["inst_site_app_proc_id"] . ".rtf";
			$this->generateDocument($srow["inst_site_app_proc_id"],"dir_recomm_document_site",$fileName,"inst_site_app_proceedings","inst_site_app_proc_id","recomm_doc");
		}
	}
	
	// Programme applications that WILL NO LONGER be tabled at meeting
	$SQL = <<<sql
		SELECT  p.ia_proceedings_id 
		FROM ia_proceedings p
		WHERE  p.proceeding_status_ind = 0
		AND p.application_status_ref = 6
		AND p.heqc_meeting_ref = $heqc_meeting_id
sql;

	$rs = mysqli_query($conn, $SQL) or die(mysqli_error($conn));
	while ($u_row = mysqli_fetch_array($rs)) {
		$unCheckbox = 'inMeeting'.$u_row["ia_proceedings_id"];
		if (   isset($_POST["$unCheckbox"])  &&  ($_POST["$unCheckbox"] == 'on')   ){
			$update2SQL = "UPDATE `ia_proceedings` SET `heqc_meeting_ref` = 0, `application_status_ref` = '5' WHERE `ia_proceedings`.`ia_proceedings_id` =".$u_row["ia_proceedings_id"];
			mysqli_query($conn, $update2SQL);
		}
	}
	
		// Sites that WILL NO LONGER be tabled at meeting
	$usSQL = <<<sql
		SELECT  inst_site_app_proc_id 
		FROM inst_site_app_proceedings
		WHERE  heqc_meeting_ref = $heqc_meeting_id
sql;

	$urs = mysqli_query($conn, $usSQL);
	while ($us_row = mysqli_fetch_array($urs)) {
		$unCheckbox = readPost('inMeetSite'.$us_row["inst_site_app_proc_id"]);
		if ( $unCheckbox == 'on') {
			$updS = <<<SITEUPD
				UPDATE inst_site_app_proceedings 
				SET application_status_ref = 5, heqc_meeting_ref = 0 
				WHERE inst_site_app_proceedings.inst_site_app_proc_id = {$us_row["inst_site_app_proc_id"]}
SITEUPD;
			$errorMail = false;
			mysqli_query($conn, $updS) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-UPDREC", "Un-assign site to HEQC meeting:"  . $updS."  --> ".mysqli_error($conn), $errorMail);
		}
	}	


?>
