<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	if (isset($_POST["do_summary"]) && ($_POST["do_summary"] > 0)) {
		$SQL = "UPDATE `evalReport` SET do_summary=1 WHERE evalReport_id='".$_POST["do_summary"]."'";
		mysqli_query ($conn, $SQL);
	}
?>
