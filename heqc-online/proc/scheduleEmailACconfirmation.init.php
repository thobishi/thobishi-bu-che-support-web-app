<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	// Insert AC Members into lnk_ACMembers_ACMeeting
	$ac_meeting_id = $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;


	// 2010-07-25: Robin Default message to one set in template_text table in database if a message has not been posted.
	$message = readPost('ACmemberNotification');
	if ($message == '') $message = $this->getTextContent("scheduleACMeeting", "Confirm AC Date");

	$members_arr = array();

	$aSQL = <<<acSQL
		SELECT lnk_ACMembers_ACMeeting.*, AC_Members.ac_mem_email, AC_Members.ac_mem_id
		FROM lnk_ACMembers_ACMeeting, AC_Members
		WHERE ac_meeting_ref=?
		AND lnk_confirmed = 1
		AND AC_Members.ac_mem_id = lnk_ACMembers_ACMeeting.ac_member_ref
acSQL;
        $sm = $conn->prepare($aSQL);
        $sm->bind_param("s", $ac_meeting_id);
        $sm->execute();
        $rs = $sm->get_result();
        
	//$rs = mysqli_query($aSQL);
	while ($row = mysqli_fetch_array($rs)) {
		$members_arr[$row["ac_member_ref"]] = $row;
	}



	foreach($members_arr as $e){
		$checkbox = "overrideEmail_".$e["ac_member_ref"];

		// Only email checked AC members.
		if (isset($_POST["$checkbox"]) && ($_POST["$checkbox"] == 'on')){
			$to 	= $e["ac_mem_email"];
			$this->misMailByName ($to, "Confirmation of AC Meeting", $message, "");
		}


	}
?>
