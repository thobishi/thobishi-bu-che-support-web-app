<?php 
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;

	$isPaid = false;

// echo $app_id." - ";
// 	echo $app_proc_id;die;

	$payment_type=0;

	$this->formFields["application_ref"]->fieldValue = $app_id;
	$this->formFields["ia_proceedings_ref"]->fieldValue = $app_proc_id;
	$condition_paid = "";

	$this->formFields["condition_administrative_fee_per_programme"]->fieldValue = 0;

	$proc_type = $this->getValueFromTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "lkp_proceedings_ref");


	if ($proc_type == 5){ //reaccreditation
		$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
		$this->formFields["reaccreditation_application_ref"]->fieldValue = $reaccred_id;
		$NQF = $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reaccred_id, "NQF_level");
		$settings_key = "accreditation_fee_undergrad";
		if ($NQF >= 5) {
			$settings_key = "accreditation_fee_postgrad";
		}
		if ($this->formFields["programme_fee"]->fieldValue == 0) {
			$this->formFields["programme_fee"]->fieldValue = $this->getDBsettingsValue($settings_key);
		}

		$sites = "";
		$SQL = <<<SQL
			SELECT * FROM `institutional_profile_sites`, `lkp_sites_reaccred` 
			WHERE sites_ref=institutional_profile_sites_id 
			AND main_site=0 
			AND Institutions_application_reaccreditation_ref='{$reaccred_id}'
SQL;
		$RS = mysqli_query($conn, $SQL);
		$site_no = 1;
		$program_fee_site_delivery_value = 0;
		while ($RS && ($row=mysqli_fetch_array($RS))) {
			$program_fee_site_delivery_value += $this->getDBsettingsValue("program_fee_additional_site_delivery");
			$sites .= $site_no . ". " . $row["location"]." - ".$row["site_name"]."<br>\n";
			$site_no++;
		}
		$sites = ($sites > "")? $sites : "-- No additional sites --";
		$this->formFields["prog_fee_additional_sites"]->fieldValue = $program_fee_site_delivery_value;

		$fee = $this->formFields["programme_fee"]->fieldValue + $this->formFields["prog_fee_additional_sites"]->fieldValue;



		$pay_desc = "(Reaccreditation)";
	} else {  //Deferral, representation and condition proceedings



		$settings_key = "";
		$pay_desc = "";
		$fee = 0;
		switch($proc_type){
		case '2':	// Deferral
			$settings_key = "proceeding_fee_deferral";
			$fee = $this->getDBsettingsValue($settings_key);
			$pay_desc = "(Deferral)";
			break;
		case '3':   // Representation
			$settings_key = "proceeding_fee_representation";
			$fee = $this->getDBsettingsValue($settings_key);
			$pay_desc = "(Representation)";
			break;
		case '4':   // Condition
		case '6':   // 2017-06-08 Richard: Included Re-accreditation condition
			$payment_type=1;
			$settings_key = "proceeding_fee_per_condition";
			$sql = <<<SQL
				SELECT count(*) as num_conditions
				FROM ia_conditions_proceedings
				WHERE ia_proceedings_ref = {$app_proc_id}
SQL;
			$rs = mysqli_query($conn, $sql) or die($sql . ": " . mysqli_error($conn));
			$row = mysqli_fetch_array($rs);
			$num_conditions = $row["num_conditions"];
			if ($num_conditions > 0){
				$fee = $num_conditions * $this->getDBsettingsValue($settings_key);
			}
			$pay_desc = "($num_conditions conditions)";

					// Added by Kevin Koekemoer 08/03/2021 - 

		$condition_administrative_fee_per_programme = 0;

		//get condition setting value

		$settings_key = "condition_administrative_fee_per_programme";
		if ($this->formFields["condition_administrative_fee_per_programme"]->fieldValue == 0) {
			$this->formFields["condition_administrative_fee_per_programme"]->fieldValue = $this->getDBsettingsValue($settings_key);
			$condition_administrative_fee_per_programme = $this->getDBsettingsValue($settings_key);
		}


		//check if payment exists

 		$application_ref = $this->dbTableInfoArray["application_ref"]->dbTableCurrentID;
 		$application_id = $this->dbTableInfoArray["application_id"]->dbTableCurrentID;


 			$sql = <<<SQL
				SELECT * from payment 
 			WHERE application_ref = {$app_id}
SQL;

 		$RS = mysqli_query($conn, $sql) or die($sql . ": " . mysqli_error($conn));

 		while ($RS && ($row=mysqli_fetch_array($RS))) {
 			if($row["condition_administrative_fee_per_programme"] == $this->getDBsettingsValue("condition_administrative_fee_per_programme"))
 			{
 				$this->formFields["condition_administrative_fee_per_programme"]->fieldValue = 0;
 				$condition_administrative_fee_per_programme = 0;
				$condition_paid = "Admin fee of R" . $this->getDBsettingsValue("condition_administrative_fee_per_programme") . " already paid on " . $row["date_payment"];
				$isPaid = true;
			}
 		}


			break;
		}




 	}

 	if ($this->formFields["proceeding_fee"]->fieldValue == 0) {
 		$this->formFields["proceeding_fee"]->fieldValue = $fee;
	}


 	$this->formFields["total_fee"]->fieldValue = $fee + $this->formFields["condition_administrative_fee_per_programme"]->fieldValue;

 	$this->formFields["invoice_total"]->fieldValue = $fee + $this->formFields["condition_administrative_fee_per_programme"]->fieldValue;



?>
