<?php
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	// Insert AC Members into lnk_ACMembers_ACMeeting
	$ac_meeting_id = $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;

	$delsql = <<<CLEARMEETING
		DELETE FROM lnk_ACMembers_ACMeeting
		WHERE ac_meeting_ref = ?
CLEARMEETING;

        $sm = $conn->prepare($delsql);
        $sm->bind_param("s", $ac_meeting_id);
        $sm->execute();
        $delrs = $sm->get_result();
        
	//$delrs = mysqli_query($delsql);

	$members_arr = array();

	$aSQL = <<<acSQL
		SELECT *
		FROM AC_Members
		WHERE ac_mem_active=1
acSQL;
	$rs = mysqli_query($conn, $aSQL);
	while ($row = mysqli_fetch_array($rs)) {
		$members_arr[$row["ac_mem_id"]] = $row;
	}


	foreach($members_arr as $e){
		$checkbox = "atMeeting_".$e["ac_mem_id"];

		if (   isset($_POST["$checkbox"])  &&  ($_POST["$checkbox"] == 'on')   ){

			$SQL  = "INSERT INTO `lnk_ACMembers_ACMeeting` (
						`lnk_id` ,
						`ac_member_ref` ,
						`ac_meeting_ref` ,
						`lnk_parkingbay` ,
						`lnk_date_communicated` ,
						`lnk_responsible` ,
						`lnk_confirmed` ,
						`lnk_airfare_date` ,
						`lnk_airfare_from` ,
						`lnk_airfare_to` ,
						`lnk_airfare_time` ,
						`lnk_airfare_ref` ,
						`lnk_shuttle_date` ,
						`lnk_shuttle_from` ,
						`lnk_shuttle_to` ,
						`lnk_shuttle_time` ,
						`lnk_shuttle_ref` ,
						`lnk_car_date` ,
						`lnk_car_ref`
						)
						VALUES (
						NULL , ?, ?, '', '1970-01-01', '', '1', '1970-01-01', '', '', '00:00:00', '', '1970-01-01', '', '', '00:00:00', '', '1970-01-01', ''
						); ";

                        $sm = $conn->prepare($SQL);
                        $sm->bind_param("ss", $e["ac_mem_id"], $ac_meeting_id);
                        $sm->execute();
                        $rs = $sm->get_result();
        
			//$rs = mysqli_query($SQL);
			//$lnk_id = mysqli_insert_id();

			// Set it to 1 in Insert statement above.  It was -1 and then updated to 1 here.  Not sure why so set it to 1 in insert statement above.
			//$SQL = "UPDATE lnk_ACMembers_ACMeeting SET lnk_confirmed = '1' WHERE lnk_id =".$lnk_id.";";
			//$rs = mysqli_query($SQL);


		}

	}
?>
