<?php

	//Email list of applications to all assigned AC members
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$ac_meeting_id = $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;
	$members_arr = array();
	$message = readPost('ACmemberNotificationOfApps');
	if (!($message > '')){
		$message = $this->getTextContent("scheduleACMeeting", "Confirm AC applications");
	}

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
		$to = $e["ac_mem_email"];
		$this->misMailByName ($to, "List of applications for AC Meeting", $message, "");
		$this->setValueInTable("lnk_ACMembers_ACMeeting", "lnk_id", $e["lnk_id"], "email_notification_date", date("Y-m-d"));
	}
?>
