<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	// Insert AC Members into lnk_ACMembers_ACMeeting
	$heqc_meeting_id = $this->dbTableInfoArray["HEQC_Meeting"]->dbTableCurrentID;

	$delsql = <<<CLEARMEETING
		DELETE FROM heqc_meeting_members
		WHERE heqc_meeting_ref = $heqc_meeting_id
CLEARMEETING;
	$delrs = mysqli_query($conn, $delsql);

	$members_arr = array();

	$aSQL = <<<acSQL
		SELECT * 
		FROM users,sec_UserGroups 
		WHERE users.user_id = sec_UserGroups.sec_user_ref
		AND sec_UserGroups.sec_group_ref = 24 
		AND users.active=1 
		ORDER BY surname, name
acSQL;
	$rs = mysqli_query($conn, $aSQL);
	while ($row = mysqli_fetch_array($rs)) {
		$members_arr[$row["user_id"]] = $row;
	}


	foreach($members_arr as $e){
		$checkbox = "atMeeting_".$e["user_id"];

		if (   isset($_POST["$checkbox"])  &&  ($_POST["$checkbox"] == 'on')   ){

			$SQL  = "Insert into heqc_meeting_members (
						`heqc_meeting_members_id` ,
						`user_ref` ,
						`heqc_meeting_ref`
						)
						VALUES (
						NULL , $e[user_id], $heqc_meeting_id
						) ";


			$rs = mysqli_query($conn, $SQL);

		}

	}
?>
