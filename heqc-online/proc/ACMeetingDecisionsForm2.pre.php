<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$SQL = "DELETE FROM `lnk_ACMembers_attend_meeting` WHERE ac_meeting_ref=".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;
	$RS = mysqli_query($conn, $SQL);
	foreach ($_POST AS $key=>$val) {
		if (stristr($key, "ac_member") > "") {
			$SQL = "INSERT INTO `lnk_ACMembers_attend_meeting` (ac_meeting_ref, ac_member_ref) VALUES (".$this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID.", ".$val.")";
			$RS = mysqli_query($conn, $SQL);
		}
	}
?>
