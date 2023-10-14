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
	$SQL1 = <<<REF
		SELECT CHE_reference_code, program_name
		FROM Institutions_application 
		WHERE institution_id = $institution_id
		AND flag_eligible_reaccreditation = 1 
		AND CHE_reference_code NOT IN 
			(SELECT referenceNumber 
			FROM Institutions_application_reaccreditation  
			WHERE institution_ref = $institution_id
			AND reacc_active_ind = 0)
REF;


	  $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
          

$RS3 = mysqli_query ($conn, $SQL1);
 $nP1 = mysqli_num_rows($RS3);
	//end
?>


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
		WHERE institution_id = $institution_id
		AND flag_eligible_reaccreditation = 1 
		AND CHE_reference_code NOT IN 
			(SELECT referenceNumber 
			FROM Institutions_application_reaccreditation  
			WHERE institution_ref = $institution_id
			AND reacc_active_ind = 0)
REF;
			$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
       //echo $SQL; 
	$RS1 = mysqli_query($conn, $SQL);
	$nP = mysqli_num_rows($RS1);
	//echo $nP ;
	if ($nP > 0) {
		while ($row = mysqli_fetch_array($RS1)) {
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
	
</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>

<td align=center class="special1" colspan="2">
	<span class="specialb">
		APPLY FOR PROGRAMME RE-ACCREDITATION:
		<br>
	
	
	</span>
</td>
</tr>
<tr>
	<td colspan="2">

	<?php
		if ($nP == 0){
			$this->formActions["next"]->actionMayShow = false;
			$msg = "<span class='specialb'>None of your institution's programmes are available for re-accreditation at this time.</span>";
		}
		
	 	if ($nP1 > 0) { 
			$msg = <<<HTML
				<span class='specialb'>The following programmes are flagged for re-accreditation:<br></span>
				<table border='1'>
				<tr><td><b>HEQC Reference code</b></td><td><b>Programme name</b></td></tr>
HTML;
				
			while ($row = mysqli_fetch_array($RS3)){
				$msg .= <<<HTML
					<tr><td>$row[CHE_reference_code]</td><td>$row[program_name]</td></tr>
HTML;
			}
			$msg .= <<<HTML
				</table>
				<span >Once you have started an application it will be available from your home page<br></span>
				<br>
				
				<span class='specialb'>STEPS:<br></span>
				<span class='specialb'>1.	Update the Institutional Profile for your institution (menu: Tools / Institutional Profile) prior to submitting any applications.<br></span>
				<span class='specialb'>2.	Start a re-accreditation application:<br></span>
				
HTML;
			
		}	
		
		echo $msg; 
?>
	
	</td>
</tr>

	
<?php 
 	if ($nP > 0) { ?>
	<tr>
		<td align="left" width="70%"><b>Please select the HEQC reference code of the programme for which you want to apply for re-accreditation:</b></td>
		<td>
			<?php	
			$this->showField("referenceNumber"); 
			$this->formFields["institution_ref"]->fieldValue = $institution_id;
			$this->showField("institution_ref");
			$this->formFields["reaccreditationVersion"]->fieldValue = 2;
			?>
		</td>
		
		</tr>
		<tr>
		<td>
		<br>
			<span class='specialb'>3.	Complete the application.  The application can be saved and re-accessed from your Home page. <br></span>
	
	 
	  
			<span class='specialb'>4.	Submit the application<br></span>
	
	  <br>
	  <br><br>
	
	
	<br>
		</td>
	
	
	</tr>
	
<?php	}	?>

</table>