<?php 
//program_fee_additional_site_delivery
//accreditation_as_provider_fee
//offering_fee_each_site
//accreditation_fee_undergrad
//accreditation_fee_postgrad
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

	if ($this->formFields["new_inst_fee"]->fieldValue == "") {
		$this->formFields["new_inst_fee"]->fieldValue = 0;
	}

	$this->formFields["application_ref"]->fieldValue = $app_id;

	// 2010-07-14 Robin: Calculate payment based on qualification type instead of NQF level.
	//$NQF = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "NQF_ref");
	$qual_type = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "qualification_type_ref");

	$settings_key = "accreditation_fee_undergrad";

	//if ($NQF >= 5) {  // replaced by qualification type
	if ($qual_type >= 7) {
		$settings_key = "accreditation_fee_postgrad";
	}

	if ($this->formFields["programme_fee"]->fieldValue == 0) {
		$this->formFields["programme_fee"]->fieldValue = $this->getDBsettingsValue($settings_key);
	}


// RTN 5/9/2006 - CHE is no longer charging a fee per program per site.
// RTN 24/10/2007 - re-instate a fee per program per site.
	//$site_delivery_value = 0;

	$program_fee_site_delivery_value = 0;
	$new_institution_fee = $this->formFields["new_inst_fee"]->fieldValue;
	$sites = "";

	// RTN 5/9/2006 - Moved this here because additional site payment must be a once off for a new institution
	// RTN 24/10/2007 re-instate a fee per program per site.
	//NOT SURE IF THIS CHECK SHOULD BE HERE. IF IT IS, THE SITES WON'T SHOW IF YOU GET BACK TO THIS PAGE A SECOND TIME.
	//	if ($this->formFields["site_fee"]->fieldValue == "") {
	//$SQL = "SELECT * FROM `institutional_profile_sites` WHERE  main_site=0 AND institution_ref='".$Inst_ref."'";
	//15-09-2020 removed AND main_site=0 from SQL


	$SQL = "SELECT * FROM `institutional_profile_sites`, `lkp_sites` WHERE sites_ref=institutional_profile_sites_id AND application_ref='".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."'";
	$RS = mysqli_query($conn, $SQL);
	$site_no = 1;
	while ($RS && ($row=mysqli_fetch_array($RS))) {
		// RTN 5/9/2006 - CHE is no longer charging a fee per program per site.
		// RTN 24/10/2007 re-instate a fee per program per site to replace once off site payment.
		// $site_delivery_value += $this->getDBsettingsValue("offering_fee_each_site");
		$program_fee_site_delivery_value += $this->getDBsettingsValue("program_fee_additional_site_delivery");
		$sites .= $site_no . ". " . $row["location"]." - ".$row["site_name"]."<br>\n";
		$site_no++;
	}
	$site_no = $site_no - 1;
	$program_fee_site_delivery_value = $program_fee_site_delivery_value - $this->getDBsettingsValue("program_fee_additional_site_delivery");

	if (!($new_institution_fee > 0)) {
		/*
		2015-01-18 Change criteria to check for a new institution:
			From using new_instittuion field in the Institutional Profile
			To whether they have any programmes with an outcome.
		if ($this->getValueFromTable("institutional_profile", "institution_ref", $Inst_ref, "new_institution") == 1) {
		 */
		$inst_id = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id");
		$sql = <<<SQL
			SELECT HEI_id, sum(new_inst_fee ) AS new_inst_fee
			FROM HEInstitution, Institutions_application, `payment` 
			WHERE HEInstitution.HEI_id = Institutions_application.institution_id 
			AND payment.application_ref = Institutions_application.application_id
			AND HEInstitution.HEI_id = {$inst_id}
			GROUP BY HEI_id
			ORDER BY HEI_id
SQL;
		$rs = mysqli_query($conn, $sql);
		if (mysqli_num_rows($rs) > 0){
			$row = mysqli_fetch_array($rs);
			if ($row["new_inst_fee"] == 0){
				// Check if new fee wasnt paid on a reaccreditation application
				$sqlr = <<<SQL
					SELECT HEI_id, sum(new_inst_fee ) AS new_inst_fee
					FROM HEInstitution, Institutions_application_reaccreditation, `payment` 
					WHERE HEInstitution.HEI_id = Institutions_application_reaccreditation.institution_ref 
					AND payment.reaccreditation_application_ref = Institutions_application_reaccreditation.Institutions_application_reaccreditation_id
					AND HEInstitution.HEI_id = {$inst_id}
					GROUP BY HEI_id
					ORDER BY HEI_id
SQL;
				$rsr = mysqli_query($conn, $sqlr);
				if (mysqli_num_rows($rsr) > 0){
					$rowr = mysqli_fetch_array($rsr);
					if ($rowr["new_inst_fee"] == 0){
						$new_institution_fee += $this->getDBsettingsValue("accreditation_as_provider_fee");
					}
				} else {  // fee is 0
					$new_institution_fee += $this->getDBsettingsValue("accreditation_as_provider_fee");
				}
			}
		}
	}

		// RTN 24/10/2007 disable once off site payment for a new institution.
		//if ($this->formFields["site_fee"]->fieldValue == "") {
		//	$this->formFields["site_fee"]->fieldValue = $site_delivery_value;
		//}

		// RTN 5/9/2006 - CHE is no longer charging a fee per program per site.
		// RTN 24/10/2007 re-instate a fee per program per site.
		$this->formFields["prog_fee_additional_sites"]->fieldValue = $program_fee_site_delivery_value ;
		$this->formFields["new_inst_fee"]->fieldValue = $new_institution_fee;


//	}

	//NOT SURE IF THIS CHECK SHOULD BE HERE? WHAT IF THE TOTAL CHANGE? 	- THEN IT WON'T BE UPDATED:
	//if ($this->formFields["invoice_total"]->fieldValue == "")
	// RTN 5/9/2006 - CHE is no longer charging a fee per program per site.
	// RTN 24/10/2007 re-instate a fee per program per site to replace once off site payment.
	//$this->formFields["invoice_total"]->fieldValue = $this->formFields["programme_fee"]->fieldValue + $this->formFields["site_fee"]->fieldValue + $this->formFields["new_inst_fee"]->fieldValue;
	
	$this->formFields["invoice_total"]->fieldValue = $this->formFields["programme_fee"]->fieldValue + ($this->formFields["prog_fee_additional_sites"]->fieldValue ) + $this->formFields["new_inst_fee"]->fieldValue;
	
	

?>
