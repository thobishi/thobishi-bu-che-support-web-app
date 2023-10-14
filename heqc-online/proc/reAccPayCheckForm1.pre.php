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
        
	if ($this->formFields["new_inst_fee"]->fieldValue == "") {
		$this->formFields["new_inst_fee"]->fieldValue = 0;
	}

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
	$SQL = "SELECT * FROM `institutional_profile_sites`, `lkp_sites_reaccred` WHERE sites_ref=institutional_profile_sites_id AND main_site=0 AND Institutions_application_reaccreditation_ref='".$reaccred_id."'";

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
	if (!($new_institution_fee > 0)) {
		$Inst_ref = $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reaccred_id, "institution_ref");
		if ($this->getValueFromTable("institutional_profile", "institution_ref", $Inst_ref, "new_institution") != 2) {
			$new_institution_fee += $this->getDBsettingsValue("accreditation_as_provider_fee");
		}
	}

		// RTN 24/10/2007 disable once off site payment for a new institution.
		//if ($this->formFields["site_fee"]->fieldValue == "") {
		//	$this->formFields["site_fee"]->fieldValue = $site_delivery_value;
		//}

		// RTN 5/9/2006 - CHE is no longer charging a fee per program per site.
		// RTN 24/10/2007 re-instate a fee per program per site.
		$this->formFields["prog_fee_additional_sites"]->fieldValue = $program_fee_site_delivery_value;
		$this->formFields["new_inst_fee"]->fieldValue = $new_institution_fee;

//	}

	//NOT SURE IF THIS CHECK SHOULD BE HERE? WHAT IF THE TOTAL CHANGE? 	- THEN IT WON'T BE UPDATED:
	//if ($this->formFields["invoice_total"]->fieldValue == "")
	// RTN 5/9/2006 - CHE is no longer charging a fee per program per site.
	// RTN 24/10/2007 re-instate a fee per program per site to replace once off site payment.
	//$this->formFields["invoice_total"]->fieldValue = $this->formFields["programme_fee"]->fieldValue + $this->formFields["site_fee"]->fieldValue + $this->formFields["new_inst_fee"]->fieldValue;
	$this->formFields["invoice_total"]->fieldValue = $this->formFields["programme_fee"]->fieldValue + $this->formFields["prog_fee_additional_sites"]->fieldValue + $this->formFields["new_inst_fee"]->fieldValue;
?>
