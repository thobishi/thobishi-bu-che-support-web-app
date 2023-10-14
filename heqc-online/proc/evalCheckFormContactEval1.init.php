<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	if (isset($_POST["phone_comments"]) && ($_POST["phone_comments"] > "")) {
		$SQL = "INSERT INTO `choose_eval_contact_eval` VALUES (NULL, ".$_POST["application_ref"].", ".$_POST["eval_ref"].", '".$_POST["phone_comments"]."', '".$_POST["date"]."')";
		$RS = mysqli_query($conn, $SQL);
	}
?>
