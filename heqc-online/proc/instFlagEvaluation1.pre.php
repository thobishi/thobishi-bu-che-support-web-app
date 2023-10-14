<?php 
	/* This code is outside the check on if the check box appid_reaccred is set because if any checkboxes are unchecked then 
	appid_reaccred is not populated for those checkboxes i.e. does not have a value.
	*/

	$fc_arr = array();

	$ref = readPost('codeSearch');
	if ($ref > ''){
		array_push($fc_arr,'HEI_code like ("%'.$ref.'%")');
	}
	
	$inst = readPost('nameSearch');
	if ($inst > ''){
		array_push($fc_arr,'HEI_name LIKE ("%'.$inst.'%")');
	}

	$filter_criteria = (count($fc_arr) > 0) ? "WHERE ". implode(' AND ',$fc_arr) : "";
        $conn = $this->getDatabaseConnection();
	$usql = <<<UPD
		UPDATE HEInstitution 
		SET flag_eligible_evaluation = 0
		$filter_criteria
UPD;

	$errorMail = false;
	mysqli_query($conn, $usql) or $errorMail = true;
	$this->writeLogInfo(10, "SQL-UPDREC", $usql."  --> ".mysqli_error($conn), $errorMail);

	if (isset($_POST["inst_id"]) && (count((array)($_POST["inst_id"]) > 0))) {

		$inst_id =  $_POST["inst_id"];
		foreach ($inst_id as $i_id){
			$this->setValueInTable("HEInstitution", "HEI_id", $i_id, "flag_eligible_evaluation", 1);
		}

	}
?>