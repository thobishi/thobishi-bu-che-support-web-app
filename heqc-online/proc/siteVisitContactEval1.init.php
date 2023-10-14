<?php 
	if (isset($_POST["phone_comments"]) && ($_POST["phone_comments"] > "")) {
		$SQL = "INSERT INTO `siteVisit_contact_eval` VALUES (NULL, ? , ?, ?, ?)";
		
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
                
                $sm = $conn->prepare($SQL);
                $sm->bind_param("ssss", $_POST["siteVisit_ref"], $_POST["eval_ref"], $_POST["phone_comments"], $_POST["date"]);
                $sm->execute();
                $RS = $sm->get_result();
                
		//$RS = mysqli_query($SQL);
	}
?>
