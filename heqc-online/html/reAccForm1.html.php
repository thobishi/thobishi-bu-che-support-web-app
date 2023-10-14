<?php 
	$userID = $this->currentUserID;
	$institution_id = $this->getValueFromTable("users", "user_id", $userID, "institution_ref");
	$inst_name = $this->getValueFromTable("HEInstitution", "HEI_id", $institution_id, "HEI_name");

	//Rebecca: 2008-05-28
	//Create an array of application ref numbers (outcome is accredited, for that particular HEI)
	//and give that as a value to the referenceNumber field
	$refNoArray = array();

	// Display reference numbers for provisionally accredited programmes
	// Exclude reference numbers for programmes that re-accreditation has been applied for.
	$SQL = <<<REF
		SELECT CHE_reference_code 
		FROM Institutions_application 
		WHERE institution_id = ?
		AND flag_eligible_reaccreditation = 1 
		AND CHE_reference_code NOT IN 
			(SELECT referenceNumber 
			FROM Institutions_application_reaccreditation  
			WHERE institution_ref = ?
			AND reacc_active_ind = 0)
REF;
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
        $sm = $conn->prepare($SQL);
        $sm->bind_param("s", $institution_id);
        $sm->execute();
        $RS = $sm->get_result();
        
	//$RS = mysqli_query($SQL);
	$nP = mysqli_num_rows($RS);
	if ($nP > 0) {
		while ($row = mysqli_fetch_array($RS)) {
			$refNoArray[$row["CHE_reference_code"]] = $row["CHE_reference_code"];
		}
		$msg = "Select the reference number of the programme for which you want to apply for re-accreditation.";
		$this->formFields["referenceNumber"]->fieldValuesArray = $refNoArray;
	}
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td colspan="2">
	<br>
	<span class="specialb">
		APPLY FOR PROGRAMME RE-ACCREDITATION:
	</span>
	<br>
</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<?php
 	if ($nP > 0) { ?>
	<tr>
		<td align="right" width="70%"><b>Please select the HEQC reference code of the programme for which you want to apply for re-accreditation:</b></td>
		<td>
			<?php 	
			$this->showField("referenceNumber"); 
			$this->formFields["institution_ref"]->fieldValue = $institution_id;
			$this->showField("institution_ref");
			?>
		</td>
	</tr>
<?php 	}	?>

</table>