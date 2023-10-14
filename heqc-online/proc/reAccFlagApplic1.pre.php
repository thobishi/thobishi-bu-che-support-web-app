<?php 
	/* This code is outside the check on if the check box appid_reaccred is set because if any checkboxes are unchecked then 
	appid_reaccred is not populated for those checkboxes i.e. does not have a value.
	*/
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
        $fc_arr = array();

	$ref = readPost('searchText');
	if ($ref > ''){
		array_push($fc_arr,'CHE_reference_code like ("%'.$ref.'%")');
	}

	$inst = readPost('institution');
	if ($inst > 0){
		array_push($fc_arr,'institution_id = '.$inst);
	}

	$filter_criteria = (count($fc_arr) > 0) ? "WHERE ". implode(' AND ',$fc_arr) : "";

	$usql = <<<UPD
		UPDATE Institutions_application 
		SET flag_eligible_reaccreditation = 0
		$filter_criteria
UPD;

	$errorMail = false;
	mysqli_query($conn, $usql); // or $errorMail = true;
	$this->writeLogInfo(10, "SQL-UPDREC", $usql."  --> ".mysqli_error($conn), $errorMail);

	if (isset($_POST["appid_reaccred"]) && (count($_POST["appid_reaccred"] > 0))) {

		$appid_reaccred =  $_POST["appid_reaccred"];
		foreach ($appid_reaccred as $app_id){
			$this->setValueInTable("Institutions_application", "application_id", $app_id, "flag_eligible_reaccreditation", 1);
		}

	}
?>
