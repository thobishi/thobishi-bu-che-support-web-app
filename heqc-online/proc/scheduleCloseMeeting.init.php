<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	// Set AC meeting as complete (set ac_end_date to current date)
	$ac_meeting_id = $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;

	$SQL  = "UPDATE `AC_Meeting` SET `ac_end_date` = ? WHERE `AC_Meeting`.`ac_id` =?";
	
	$d = date("Y-m-d");
	
	$sm = $conn->prepare($SQL);
        $sm->bind_param("ss", $d, $ac_meeting_id);
        $sm->execute();
        $rs = $sm->get_result();
        
	//$rs = mysqli_query($SQL);
	// 2012-04-17 Robin: System has moved across from applications to proceedings.
	// Time delay in closing meetings.  It is the users choice when to close a meeting.  Thus meetings may still be open
	// a year after it has taken place.  Thus application statuses would have changed according to HEQC meeting etc.
	// Thus the restriction that the status is '2' because '2' means assigned to the meeting.
	$SQL  = "UPDATE `Institutions_application` SET `application_status` = '3' WHERE `AC_Meeting_ref`=? AND `application_status` = '2'";
	$errorMail = false;
	$sm = $conn->prepare($SQL);
        $sm->bind_param("s", $ac_meeting_id);
        $sm->execute();
        $rs = $sm->get_result();
        
        if(!$rs) $errorMail = true;
	//$rs = mysqli_query($SQL) or $errorMail = true;
	$this->writeLogInfo(10, "SQL-UPDREC", $SQL."  --> ".mysqli_error($conn), $errorMail);
	
	$SQL  = "UPDATE `ia_proceedings` SET `application_status_ref` = '3' WHERE `ac_meeting_ref`=? AND `application_status_ref` = '2'";
	$errorMail = false;
	
	$sm = $conn->prepare($SQL);
        $sm->bind_param("s", $ac_meeting_id);
        $sm->execute();
        $rs = $sm->get_result();
        
        if(!$rs) $errorMail = true;
	
	//$rs = mysqli_query($SQL) or $errorMail = true;
	$this->writeLogInfo(10, "SQL-UPDREC", $SQL."  --> ".mysqli_error($conn), $errorMail);

?>
