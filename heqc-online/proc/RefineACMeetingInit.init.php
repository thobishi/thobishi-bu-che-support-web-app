<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$SQL = "SELECT count(*) FROM lnk_ACMembers_ACMeeting WHERE lnk_confirmed <> 1 AND ac_meeting_ref=".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;
	$rs = mysqli_query($conn, $SQL);
	$row = mysqli_fetch_array($rs);
	if ($row[0] == 0){
		$this->logicVars["goRefineFormNormal"] = 1;
	}else{
		$this->logicVars["goRefineFormNormal"] = 0;
	}
?>
