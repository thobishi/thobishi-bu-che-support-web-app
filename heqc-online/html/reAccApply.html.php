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
		SELECT CHE_reference_code, program_name
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
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td align=center class="special1" colspan="2">
	<span class="specialb">
		APPLY FOR PROGRAMME RE-ACCREDITATION:
	</span>
</td>
</tr>
<tr>
	<td colspan="2">
	<br>
	Please complete Section 1: Institutional information first by selecting that option in the Actions menu. 
	<br><br>
	Please complete Section 2: Programme information for each programme.  Please note that you may only apply for 
	the re-accreditation of a programme that is accredited and has a graduated cohort. The current status for <?php echo $inst_name?> is:
	<br><br>
	<?php
		if ($nP == 0){
			$this->formActions["next"]->actionMayShow = false;
			$msg = "<span class='specialb'>None of your institution's programmes are available for re-accreditation at this time.</span>";
		}
		
	 	if ($nP > 0) { 
			$msg = <<<HTML
				<span class='specialb'>The following programmes are available for application for re-accreditation:<br></span>
				<table border='1'>
				<tr><td><b>HEQC Reference code</b></td><td><b>Programme name</b></td></tr>
HTML;
				
			while ($row = mysqli_fetch_array($RS)){
				$msg .= <<<HTML
					<tr><td>$row[CHE_reference_code]</td><td>$row[program_name]</td></tr>
HTML;
			}
			$msg .= <<<HTML
				</table>
HTML;
			
		}	
		
		echo $msg; 
?>
	
	<br><br>
	Completing a re-accreditation application might take some time.
	You do not need to complete the entire application form in a single session - you may come back to the online application as often as necessary.
	<br><br>
	To save data that you have entered, you may press either the "Save", "Next" or "Previous" buttons; or "Log out".
	<br><br>
	Once you are certain that the required sections of the form are complete, submit the application to the Programme Accreditation Directorate by clicking "Submit and Logout", which will appear after the last question on the application form.
	<br><br>
	<span class="visi">In the interests of expediting the processing of the application, you are asked to ensure that 
	all questions are answered in full and that all relevant documentation is uploaded as requested. Failure to provide 
	responses and/ or the relevant documentation will result in the application being returned or cancelled. </span>
	<br><br>
	</td>
</tr>
<tr>
	<td align="center"  valign="top">
	<br>
		<table class="oncolour" width="80%" border=0 align="center" cellpadding="2" cellspacing="2">
			<tr>
				<td align="center">
				<b>
				If you have any queries contact <br>the accreditation directorate:<br>
				<br>
				<a href="mailto:reaccreditation@che.ac.za"><span class="specialh">reaccreditation@che.ac.za</span></a></span>
				</b>
				</td>
			</tr>
		</table>
		<br><br>
	</td>
</tr>
</table>
