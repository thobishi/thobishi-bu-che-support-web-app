<?php 
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
    if ($conn->connect_errno) {
        $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
        printf("Error: %s\n".$conn->error);
        exit();
    }
$groupID = 14; //AC_HEI group
	$ref = "";
	$ac_mem_id = $this->dbTableInfoArray["AC_Members"]->dbTableCurrentID;
	$ac_user_id = "";

//only update the users table if ac_mem_active is 1 (1 = active; 0 = disabled)
	switch ($_POST["FLD_ac_mem_active"])
	{
		case '0':
			$ac_user_id = $this->getValueFromTable("AC_Members", "ac_mem_id", $ac_mem_id, "user_ref");
			$SQL = "DELETE FROM sec_UserGroups WHERE sec_user_ref=".$ac_user_id;
			$RS = mysqli_query($conn, $SQL);
			break;
		case '1':
			$SQL = "SELECT * FROM `AC_Members` WHERE ac_mem_id=".$ac_mem_id;
			$RS = mysqli_query($conn, $SQL);

			while ($row = mysqli_fetch_array($RS)) {
			//the ac mem gets a user id from users
				$ac_user_id = $this->checkUserInDatabase($row["ac_mem_title_ref"], $row["ac_mem_email"], $row["ac_mem_surname"], $row["ac_mem_name"], $groupID)."<br><br>";
			//inserts the user_id into the AC_Members table
				$SQL = "UPDATE `AC_Members` SET user_ref ='".$ac_user_id."' WHERE ac_mem_id='".$ac_mem_id."'";
				$updateRS = mysqli_query($conn, $SQL);
			}
			break;
	}

?>
