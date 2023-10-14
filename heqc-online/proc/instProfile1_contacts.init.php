<?php 
	$inst = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$SQL = "SELECT * FROM `institutional_profile_contacts` WHERE contact_type_ref = 1 AND institution_ref=".$inst;
	$RS = mysqli_query($conn, $SQL);
	$mid = 0;
	if ($RS && ($row = mysqli_fetch_array($RS))) {
		$mid = $row["institutional_profile_contacts_id"];
		$this->setFormDBinfo("institutional_profile_contacts", "institutional_profile_contacts_id", $mid);
	} else {
		$this->setFormDBinfo("institutional_profile_contacts", "institutional_profile_contacts_id", 'NEW');
	}
?>
