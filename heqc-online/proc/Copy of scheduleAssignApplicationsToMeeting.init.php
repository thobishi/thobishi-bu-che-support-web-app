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

	$aSQL = <<<acSQL
		SELECT a.application_id, p.ia_proceedings_id 
		FROM Institutions_application a, ia_proceedings p
		WHERE  a.application_id = p.application_ref
		AND p.proceeding_status_ind = 0
 		AND a.application_status = 1
acSQL;
	$ars = mysql_query($conn, $aSQL);
	while ($row = mysql_fetch_array($ars)) {
		//$checkbox = "notAssigned".$row["application_id];
		$checkbox = "notAssigned".$row["ia_proceedings_id"];
		if (   isset($_POST["$checkbox"])  &&  ($_POST["$checkbox"] == 'on')   ){
			$AC_Meeting_date = $this->getValueFromTable("AC_Meeting", "ac_id", $ac_meeting_id, "ac_start_date");
			$updateSQL = "UPDATE `Institutions_application` SET `application_status` = '2', `AC_Meeting_ref` = '".$ac_meeting_id."', `AC_Meeting_date` = '".$AC_Meeting_date."' WHERE `Institutions_application`.`application_id` =".$row["application_id"];
			mysql_query($conn, $updateSQL) or die(mysql_error($conn));
			$updateSQL = "UPDATE `ia_proceedings` SET  `ac_meeting_ref` = '".$ac_meeting_id."' WHERE `ia_proceedings`.`ia_proceedings_id` =".$row["ia_proceedings_id"];
			mysql_query($conn, $updateSQL) or die(mysql_error($conn));
		}
	}




	// Applications that WILL NO LONGER be tabled at meeting
	$SQL = <<<sql
		SELECT  a.application_id, p.ia_proceedings_id 
		FROM Institutions_application a, ia_proceedings p
		WHERE  a.application_id = p.application_ref
		AND p.proceeding_status_ind = 0
		AND a.application_status = 2
		AND p.ac_meeting_ref = $ac_meeting_id
sql;

	$rs = mysql_query($conn, $SQL) or die(mysql_error($conn));
	while ($u_row = mysql_fetch_array($rs)) {
		// Converting to proceedings.
		//$unCheckbox = 'inMeeting'.$u_row["application_id"];
		$unCheckbox = 'inMeeting'.$u_row["ia_proceedings_id"];
		if (   isset($_POST["$unCheckbox"])  &&  ($_POST["$unCheckbox"] == 'on')   ){
			$update2SQL = "UPDATE `Institutions_application` SET `application_status` = '1', `AC_Meeting_ref` = 0 WHERE `Institutions_application`.`application_id` =".$u_row["application_id"];
			mysql_query($conn, $update2SQL);
			$update2SQL = "UPDATE `ia_proceedings` SET `ac_meeting_ref` = 0 WHERE `ia_proceedings`.`ia_proceedings_id` =".$u_row["ia_proceedings_id"];
			mysql_query($conn, $update2SQL);
		}
	}

?>
