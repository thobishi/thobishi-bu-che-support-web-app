<?php
	$new_title = readPost("FLD_new_title");
	$old_title = readPost("FLD_old_title");
	$app_id = readPost("FLD_application_ref");
	if ($app_id > 0 && $new_title > "" && $old_title > ""){
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
		if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
                
		$sql = <<<UPDCOND
			UPDATE Institutions_application 
			SET program_name = ?, prev_program_name = ?
			WHERE application_id = ?
UPDCOND;
		$errorMail = false;
		
		$sm = $conn->prepare($sql);
		$sm->bind_param("sss", $new_title, $old_title, $app_id);
		$sm->execute();
		$rs = $sm->get_result();
		if(!$rs) $errorMail = true;
		//mysqli_query($sql) or $errorMail = true;
		$this->writeLogInfo(10, "SQL-UPDREC", $sql."  --> ".mysqli_error($conn), $errorMail);
	}
?>