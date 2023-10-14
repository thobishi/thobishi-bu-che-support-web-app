<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	// Applications that have been assigned
	$ac_meeting_id = $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;

	$apps_arr = array();

	//---------------------------------------------------------------------------------------------------------------------------------
	// Applications that WILL NO LONGER be tabled at meeting
	$SQL = <<<sql
		SELECT  a.application_id, p.ia_proceedings_id 
		FROM Institutions_application a, ia_proceedings p
		WHERE  a.application_id = p.application_ref
		AND p.ac_meeting_ref = $ac_meeting_id
sql;
	//	AND p.proceeding_status_ind = 0
	//	AND p.application_status_ref = 2
	// 2012-04-17 AND a.application_status = 2

	$rs = mysqli_query($conn, $SQL);
	while ($u_row = mysqli_fetch_array($rs)) {
		// Converting to proceedings.
		//$unCheckbox = 'inMeeting'.$u_row["application_id"];
		$unCheckbox = readPost('inMeeting'.$u_row["ia_proceedings_id"]);
		if (   $unCheckbox == 'on') {
			//$update2SQL = "UPDATE `Institutions_application` SET `application_status` = '1', `AC_Meeting_ref` = 0 WHERE `Institutions_application`.`application_id` =".$u_row["application_id"];
			//$errorMail = false;
			//mysqli_query($update2SQL) or $errorMail = true;
			//$this->writeLogInfo(10, "SQL-UPDREC", "Un-assign application to AC meeting:"  . $update2SQL."  --> ".mysqli_error(), $errorMail);

			//$update2SQL = "UPDATE `ia_proceedings` SET `ac_meeting_ref` = 0 WHERE `ia_proceedings`.`ia_proceedings_id` =".$u_row["ia_proceedings_id"];
			$update2SQL = <<<APPUPD
				UPDATE ia_proceedings 
				SET application_status_ref = 1, ac_meeting_ref = 0 
				WHERE ia_proceedings.ia_proceedings_id = {$u_row["ia_proceedings_id"]}
APPUPD;
			$errorMail = false;
			mysqli_query($conn, $update2SQL) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-UPDREC", "Un-assign application to AC meeting:"  . $update2SQL."  --> ".mysqli_error($conn), $errorMail);
		}
	}
	// Sites that WILL NO LONGER be tabled at meeting
	$usSQL = <<<sql
		SELECT  inst_site_app_proc_id 
		FROM inst_site_app_proceedings
		WHERE  ac_meeting_ref = $ac_meeting_id
sql;

	$urs = mysqli_query($conn, $usSQL);
	while ($us_row = mysqli_fetch_array($urs)) {
		$unCheckbox = readPost('inMeetSite'.$us_row["inst_site_app_proc_id"]);
		if ( $unCheckbox == 'on') {
			$updS = <<<SITEUPD
				UPDATE inst_site_app_proceedings 
				SET application_status_ref = 1, ac_meeting_ref = 0 
				WHERE inst_site_app_proceedings.inst_site_app_proc_id = {$us_row["inst_site_app_proc_id"]}
SITEUPD;
			$errorMail = false;
			mysqli_query($conn, $updS) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-UPDREC", "Un-assign site to AC meeting:"  . $updS."  --> ".mysqli_error($conn), $errorMail);
		}
	}	

	//2012-06-01: Robin - If users use previous to return applications to previous users and processes but the application
	// concerned has already gone to an AC Meeting and has an ac_meeting_ref - then when it gets back to Final recommendation approval
	// the application status is reset to 'ready for an AC Meeting'.  When scheduling an AC meeting it comes up in the list of 
	// applications that may be asigned to the meeting.  If selected then it will be assigned to the AC meeting and overwrite the previous
	// AC Meeting information.  In order to prevent this I am disallowing an application that has an ac_meeting_ref to come up in the list.

	$aSQL = <<<acSQL
		SELECT a.application_id, p.ia_proceedings_id, p.lkp_proceedings_ref 
		FROM Institutions_application a, ia_proceedings p
		WHERE  a.application_id = p.application_ref
		AND p.proceeding_status_ind = 0
 		AND p.application_status_ref = 1
		AND p.ac_meeting_ref = 0
acSQL;
// 		2012-04-17 AND a.application_status = 1

	$ars = mysqli_query($conn, $aSQL);
	while ($row = mysqli_fetch_array($ars)) {
		//$checkbox = "notAssigned".$row["application_id];
		$checkbox = readPost("notAssigned".$row["ia_proceedings_id"]);
		if ( $checkbox == 'on'){
			$AC_Meeting_date = $this->getValueFromTable("AC_Meeting", "ac_id", $ac_meeting_id, "ac_start_date");
			//2012-04-17
			//$updateSQL = "UPDATE `Institutions_application` SET `application_status` = '2', `AC_Meeting_ref` = '".$ac_meeting_id."', `AC_Meeting_date` = '".$AC_Meeting_date."' WHERE `Institutions_application`.`application_id` =".$row["application_id"];
			//$errorMail = false;
			//mysqli_query($updateSQL) or $errorMail = true;
			//$this->writeLogInfo(10, "SQL-UPDREC", "Assign application to AC meeting:"  . $updateSQL."  --> ".mysqli_error(), $errorMail);

			//$updateSQL = "UPDATE `ia_proceedings` SET  `ac_meeting_ref` = '".$ac_meeting_id."' WHERE `ia_proceedings`.`ia_proceedings_id` =".$row["ia_proceedings_id"];
			$updateSQL = <<<ADDAPP
				UPDATE ia_proceedings 
				SET application_status_ref = '2', ac_meeting_ref = "{$ac_meeting_id}" 
				WHERE ia_proceedings.ia_proceedings_id = {$row["ia_proceedings_id"]}
ADDAPP;
			$errorMail = false;
			mysqli_query($conn, $updateSQL) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-UPDREC", "Assign application to AC meeting:"  . $updateSQL."  --> ".mysqli_error($conn), $errorMail);

			//if ($row['lkp_proceedings_ref'] != 4){ // no recommendation for conditional proceedings
			//2017-10-20 Richard: Include conditional re-accred
			if (($row['lkp_proceedings_ref'] != 4) && ($row['lkp_proceedings_ref'] != 6)){ // no recommendation for conditional proceedings
				// Generate a rtf copy of the Directorate recommendation that will be viewed in an AC Meeting by AC members.
				$fileName = "recomm_" . $row["ia_proceedings_id"] . ".rtf";
				$this->generateDocument($row["ia_proceedings_id"],"dir_recomm_document",$fileName,"ia_proceedings","ia_proceedings_id","recomm_doc");
			}
		}
	}

	//-------------------------------------------------------------------------------------------------------------------------------
	// Sites that will be tabled at the meeting
	$sSQL = <<<siteSQL
			SELECT inst_site_app_proc_id
			FROM inst_site_app_proceedings
			WHERE application_status_ref = 1
			AND site_proceeding_status_ind = 0
siteSQL;

	$srs = mysqli_query($conn, $sSQL);
	while ($srow = mysqli_fetch_array($srs)) {
		$checkbox = readPost("noSite".$srow["inst_site_app_proc_id"]);
		if ( $checkbox == 'on' ) {
			$ac_meeting_date = $this->getValueFromTable("AC_Meeting", "ac_id", $ac_meeting_id, "ac_start_date");
			$updateSQL = " UPDATE inst_site_app_proceedings 
				SET  application_status_ref = '2',ac_meeting_ref = {$ac_meeting_id} 
				WHERE inst_site_app_proceedings.inst_site_app_proc_id = {$srow["inst_site_app_proc_id"]}";

			$errorMail = false;
			mysqli_query($conn, $updateSQL) or $errorMail = true;
			$this->writeLogInfo(10, "SQL-UPDREC", "Assign site application to AC meeting:"  . $updateSQL."  --> ".mysqli_error($conn), $errorMail);

			$fileName = "site_recomm_" . $srow["inst_site_app_proc_id"] . ".rtf";
			$this->generateDocument($srow["inst_site_app_proc_id"],"dir_recomm_document_site",$fileName,"inst_site_app_proceedings","inst_site_app_proc_id","recomm_doc");
		}
	}

?>
