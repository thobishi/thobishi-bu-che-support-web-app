<?php

	//Email list of applications to all assigned AC members
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$heqc_meeting_id = $this->dbTableInfoArray["HEQC_Meeting"]->dbTableCurrentID;
	$files = "";
	$doc_id = $this->getValueFromTable("HEQC_Meeting","heqc_id",$heqc_meeting_id,"ac_summary_doc");
	if ($doc_id > ""){
		$doc_url = $this->getValueFromTable("documents", "document_id", $doc_id,"document_url");
		$doc_name = $this->getValueFromTable("documents", "document_id", $doc_id,"document_name");
//echo $doc_id . " * " . $doc_url . " * " . $doc_name; die();
		$files = array();
		array_push($files,array(OCTODOC_DIR.$doc_url,$doc_name));
	}
	$members_arr = array();

	$aSQL = <<<SQL
		SELECT m.*, users.email, users.user_id 
		FROM heqc_meeting_members m, users 
		WHERE heqc_meeting_ref = $heqc_meeting_id 
		AND m.user_ref = users.user_id
SQL;

	$rs = mysqli_query($conn, $aSQL);
	while ($row = mysqli_fetch_array($rs)) {
		$members_arr[$row["user_ref"]] = $row;
	}

	foreach($members_arr as $e){
		$to 	= $e["email"];
		$message = $this->getTextContent("scheduleHEQCMeeting", "Confirm HEQC applications");
		$this->misMailByName ($to, "List of applications for HEQC Meeting", $message, "", true ,$files);
		$this->setValueInTable("heqc_meeting_members", "heqc_meeting_members_id", $e["heqc_meeting_members_id"], "email_notification_date", date("Y-m-d"));
	}
?>
