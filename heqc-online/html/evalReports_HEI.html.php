<?php
	$currentUserID = $this->currentUserID;
      // echo $currentUserID
    $ep = $this->getEvalPersnrForUser($currentUserID);
	$ep_persnrs = implode("," , (array)$ep["personNumber"]); 
//	var_dump($ep_persnrs);
	//var_dump($ep);
//echo $ep_persnrs;

if (!empty($ep_persnrs)) { 
?>

<br>
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php
	echo "Displays applications ready for evaluation. Under each application, you will see the following:";
	echo "<ul>";
	echo "<li>The date this application was assigned to you (as evaluator)</li>";
	echo "<li>The last day you will be able to view this application</li>";
	echo "<li>Application submission (if clicked, you can see all the documentation attached by the institution to the application)</li>";
	echo "<li>Institution's profile</li>";
	echo "<li>Deferral or representation information received from the institution</li>";
	echo "</ul>";
	echo "Note that you will only be able to view these applications until the 'Access ends on' date, as set by the HEQC";
?>

</td></tr>
</table>

<!---------------------------------------->

<br>

<table width="98%" border="0" align="center" cellpadding="2" cellspacing="2">
	<?php
                //$conn = $this->getDatabaseConnection();
		//final evaluation report column added at the end IF chair exists
		$tableHeadings =<<< DISPLAY
				<td valign='top'>Access ends on</td>
				<td valign='top'>Institution and Programme information</td>
				<td valign='top'>Evaluations</td>
DISPLAY;

		/*$SQL =<<<ASSIGNED
			SELECT Institutions_application.institution_id, HEInstitution.HEI_name, 
				Institutions_application.application_id,  
				Institutions_application.CHE_reference_code, Institutions_application.program_name,
				ia_proceedings.evaluator_access_end_date,
				ia_proceedings.reaccreditation_application_ref 
			FROM ia_proceedings, evalReport, HEInstitution, Institutions_application
			WHERE ia_proceedings.ia_proceedings_id = evalReport.ia_proceedings_ref
			AND ia_proceedings.application_ref = Institutions_application.application_id
			AND Institutions_application.institution_id = HEInstitution.HEI_id
			AND ia_proceedings.evaluator_access_end_date >= CURDATE()
			AND ia_proceedings.evaluator_access_end_date != '1970-01-01'
			AND evalReport.evalReport_status_confirm = 1
			AND evalReport.Persnr_ref IN ({$ep_persnrs})
ASSIGNED;*/
		// 2017-06-29 Richard
		//Added application background to programme information
		$SQL =<<<ASSIGNED
			SELECT Institutions_application.institution_id, HEInstitution.HEI_name, 
				Institutions_application.application_id,  
				Institutions_application.CHE_reference_code, Institutions_application.program_name,
				ia_proceedings.evaluator_access_end_date,
				ia_proceedings.reaccreditation_application_ref,
				ia_proceedings.ia_proceedings_id,
				ia_proceedings.applic_background
			FROM ia_proceedings, evalReport, HEInstitution, Institutions_application
			WHERE ia_proceedings.ia_proceedings_id = evalReport.ia_proceedings_ref
			AND ia_proceedings.application_ref = Institutions_application.application_id
			AND Institutions_application.institution_id = HEInstitution.HEI_id
			AND ia_proceedings.evaluator_access_end_date >= CURDATE()
			AND ia_proceedings.evaluator_access_end_date != '1000-01-01'
			AND evalReport.evalReport_status_confirm = 1
		 AND evalReport.Persnr_ref IN ({$ep_persnrs})

			
ASSIGNED;

//echo $ep_persnrs;


		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
		if ($conn->connect_errno) {
		    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
		    printf("Error: %s\n".$conn->error);
		    exit();
		}

		//$sm = $conn->prepare($SQL);
		////$sm->bind_param("s", $ep_persnrs);
		//$sm->execute();
		//$rs = $sm->get_result();
		// 2012-08-29 Robin
		// Get distinct applications currently assigned to the logged on evaluator
		// Using joins and GROUP BY instead of IN (query) AND Distinct for performance
/*
		$SQL =<<< MYSQL
			SELECT Institutions_application.institution_id, HEInstitution.HEI_name, Institutions_application.application_id,  
				Institutions_application.CHE_reference_code, Institutions_application.program_name,
				Institutions_application.evaluator_access_end_date, Institutions_application.evaluationType
			FROM evalReport, Institutions_application, HEInstitution, Eval_Auditors
			WHERE evalReport.application_ref = Institutions_application.application_id
			AND Institutions_application.institution_id = HEInstitution.HEI_id
			AND Eval_Auditors.Persnr = evalReport.Persnr_ref
			AND Institutions_application.evaluator_access_end_date >= CURDATE()
			AND Institutions_application.evaluator_access_end_date != '1970-01-01'
			AND evalReport_status_confirm = 1
			AND evalReport.Persnr_ref = '{$ep["personNumber"]}'
			GROUP BY Institutions_application.institution_id, HEInstitution.HEI_name, Institutions_application.application_id,  
				Institutions_application.CHE_reference_code, Institutions_application.program_name,
				Institutions_application.evaluator_access_end_date
			ORDER BY Institutions_application.institution_id, HEInstitution.HEI_name, Institutions_application.application_id,  
				Institutions_application.CHE_reference_code, Institutions_application.program_name,
				Institutions_application.evaluator_access_end_date
MYSQL;
*/              file_put_contents('php://stderr', print_r($SQL, TRUE));
		$rs = mysqli_query($conn, $SQL);
	//	echo $SQL;
		$tableData = "";
		if (mysqli_num_rows($rs) > 0)
		{
			while ($row = mysqli_fetch_array($rs))
			{
				$end_access_date = $row['evaluator_access_end_date'];

				$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$row['institution_id']."&DBINF_institutional_profile___institution_ref=".$row['institution_id']."&DBINF_Institutions_application___application_id=".$row["application_id"];
				$applicationID = $row["application_id"];
				$heiProfileLink = '<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$row['institution_id'].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row['HEI_name'].'</a>';
				$programmeName = $row['program_name'];
				$programme_code_link = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$applicationID.'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["CHE_reference_code"].'</a>';
				// 2017-06-29 Richard
				//Added application background to programme information
				if ($row["applic_background"] > ""){
					$ia_proceedings_id = $row["ia_proceedings_id"];
					$applic_background_link = "<a href='pages/backgroundsForProceedings.php?id=".base64_encode($ia_proceedings_id)."' target='_blank'>Background</a>";
				}
				$reacc_id = $row["reaccreditation_application_ref"];
				$reacc_link = '';
				$reacc_progname = '';
				if ($reacc_id > 0){
					$tmpSettingsReacc = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$row['institution_id']."&DBINF_institutional_profile___institution_ref=".$row['institution_id']."&DBINF_Institutions_application_reaccreditation___Institutions_application_reaccreditation_id=".$reacc_id;
					$reacc_progname = $this->getValueFromTable("Institutions_application_reaccreditation","Institutions_application_reaccreditation_id",$reacc_id, "programme_name");
					$reacc_link = '<a href="javascript:winPrintReaccApplicForm(\'Re-accreditation Application Form\',\''.$reacc_id.'\', \''.base64_encode($tmpSettingsReacc).'\', \'\');">Re-accreditation</a>';
				}

				$proc_docs = "";
				// Get all completed proceedings in order to get relevant documentation for evaluator: representations, deferrals, conditions
				$psql = <<<PROCEEDINGS
					SELECT ia_proceedings_id, lkp_proceedings_desc
					FROM ia_proceedings, lkp_proceedings
					WHERE ia_proceedings.lkp_proceedings_ref = lkp_proceedings.lkp_proceedings_id
					AND application_ref = $applicationID
					AND proceeding_status_ind = 1
					ORDER BY prev_ia_proceedings_ref
PROCEEDINGS;


//echo $applicationID;

     $conn= $this->getDatabaseConnection();
				$prs = mysqli_query($conn, $psql);
				if ($prs){
					if (mysqli_num_rows($prs) > 0){
						while ($prow = mysqli_fetch_array($prs)){
							$proc_docs_arr = $this->getProceedingDocs($prow["ia_proceedings_id"], "evaluator portal");
							foreach($proc_docs_arr as $d){
								// 2017-07-19 Richard - Added break after each document
								$proc_docs .= "<br />" . $d . "<br />";
							}		
						}
					}
				}
				
				$programme_info = $heiProfileLink . "<br /><br />"; 
				$programme_info .= $programme_code_link . ": " . $programmeName . "<br /><br />"; 
				// 2017-06-29 Richard
				//Added application background to programme information
				$programme_info .= $applic_background_link . "<br /><br />"; 
				$programme_info .= ($reacc_id > 0) ? $reacc_link . ": " . $reacc_progname . "<br />" : "";
				$programme_info .= $proc_docs;

				//--------------------------------------------------------------------------------------------------------------------------------
				// Get all evaluator reports by the user for the current application.  
				// Other evaluator details must only be displayed if it is a cluster evaluation
				// Representations (proceeding is a representation proceeding): Must be able to see all previous evaluator reports
				// Accreditation, Deferrals: Must only see their own reports

				// 1. Own evaluations
				$evaluations = "";
				$esql = <<<MYSQL
					SELECT evalReport_id, Persnr_ref, evalReport_doc, evalReport_date_sent, evalReport_date_completed, ia_proceedings_ref, lkp_proceedings_ref, lkp_proceedings_desc
					FROM evalReport
					LEFT JOIN ia_proceedings ON evalReport.ia_proceedings_ref = ia_proceedings.ia_proceedings_id
					LEFT JOIN lkp_proceedings ON ia_proceedings.lkp_proceedings_ref = lkp_proceedings.lkp_proceedings_id
					WHERE evalReport.application_ref = $applicationID
					AND evalReport.Persnr_ref IN ({$ep_persnrs})
					AND evalReport_status_confirm = 1
MYSQL;

         //  $conn= $this->getDatabaseConnection();
				$ers = mysqli_query($conn, $esql);
				if ($ers){
					$evaluations = <<<EHTML
						<table cellpadding="2" cellspacing="2">
							<tr class='oncolourb'>
								<td>Proceeding type</td><td>Date assigned</td><td>Date completed</td><td colspan="3">Evaluation report</td>
							</tr>
EHTML;
					while ($erow = mysqli_fetch_array($ers)){

						$dateSent = ($erow['evalReport_date_sent'] > '1000-01-01') ? $erow['evalReport_date_sent'] : '-';
						$dateCompleted = ($erow['evalReport_date_completed'] > '1000-01-01') ? $erow['evalReport_date_completed'] : '-';
						$proceedings = ($erow['lkp_proceedings_desc'] > '') ? $erow['lkp_proceedings_desc'] : '-';
						$ulink = "";
						
						// 2017-07-25 Richard: Included conditional re-accred
						if (($erow['lkp_proceedings_ref'] == '4')||($erow['lkp_proceedings_ref'] == '6')){
							$plink = $this->scriptGetForm ('ia_proceedings', $erow["ia_proceedings_ref"], '_condForm_eval');
							$ulink = "<a href='".$plink."'><img border=\'0\' src=\"images/ico_print.gif\"></a> Edit conditions met";
							$l_doc = "&nbsp";
							$upl_txt = "";
						} else {
					
							// Only allow an upload or replace on your own reports.
							if (array($erow['Persnr_ref'], $ep["personNumber"])){
								$link = $this->scriptGetForm ('evalReport', $erow['evalReport_id'], 'next');
								$ulink = "<a href='".$link."'><img border=\'0\' src=\"images/ico_print.gif\"></a>";		
							}
							$l_doc = "Click on the Upload/replace image to upload your report";
							if ($erow["evalReport_doc"] > 0){
								$e_doc = new octoDoc($erow["evalReport_doc"]);
								$l_doc = "<a href='".$e_doc->url()."' target='_blank'>".$e_doc->getFilename()."</a>";
							}
							$upl_txt = "Upload/<br/>replace";
						}
						$evaluations .= <<<EHTML
								<tr>
									<td>$proceedings</td><td>$dateSent</td><td>$dateCompleted</td><td>$ulink</td><td>$upl_txt</td><td>$l_doc</td>
								</tr>
EHTML;
					}
					$evaluations .= <<<EHTML
						</table>
EHTML;
				}
				
				// 2. Evaluations by other evaluators (if indicated that others may see them by the evaluator user)
				$o_evaluations = "";
				$oSQL =<<< MYSQL
					SELECT *
					FROM evalReport
					WHERE application_ref = $applicationID
					AND evalReport_status_confirm = 1
					AND view_by_other_eval_yn_ref = 2
					AND Persnr_ref NOT IN ({$ep_persnrs})
MYSQL;
			$conn= $this-> getDatabaseConnection();
			
						$ors = mysqli_query($conn, $oSQL);
				if ($ors){
					if (mysqli_num_rows($ors) > 0){
						$o_evaluations = <<<EHTML
							<br /><br />
							<b>Other evaluations:</b><br />
							<table cellpadding="2" cellspacing="2">
EHTML;
					
						while ($orow = mysqli_fetch_array($ors)){

							$l_doc = "";
							$num_other = 0;
							if ($orow["evalReport_doc"] > 0){
								$o_doc = new octoDoc($orow["evalReport_doc"]);
								$l_doc = "<a href='".$o_doc->url()."' target='_blank'>".$o_doc->getFilename()."</a>";
								$o_evaluations .= <<<EHTML
								<tr>
									<td>$orow[evalReport_date_completed]</td><td>$l_doc</td>
								</tr>
EHTML;
								$num_other++;
							}
						}
						if ($num_other == 0){
							$o_evaluations .= "Reports not yet uploaded";
						}
						$o_evaluations .= <<<EHTML
							</table>
EHTML;
					}
				}


				//-------------------------------------------------------------------------------------------------------------------------------
				
				$tableData .=<<<DISPLAY
					<tr class='onblue' valign='top'>
						<td width='7%'>$end_access_date</td>
						<td width="27%">
							$programme_info
							$o_evaluations
						</td>
						<td width="65%">
							$evaluations
						</td>
					</tr>
DISPLAY;
			}
			$displayApplicationsToEvaluate = <<< DISPLAY
				<tr class='oncolourb'> $tableHeadings </tr>
				$tableData
DISPLAY;
			
		}
		else
		{
			$displayApplicationsToEvaluate =<<< DISPLAY
			<tr class='onblue' valign='top'>
				<td colspan='10' align='center'>-You do not have any applications assigned to you at this time-</td>
			</tr>
DISPLAY;
		}

		echo $displayApplicationsToEvaluate;

	?>
</table>
<br>

<br>
<br>
<?php
}

else {

	echo "Displays applications ready for evaluation. Under each application, you will see the following:";
	echo "<ul>";
	echo "<li>The date this application was assigned to you (as evaluator)</li>";
	echo "<li>The last day you will be able to view this application</li>";
	echo "<li>Application submission (if clicked, you can see all the documentation attached by the institution to the application)</li>";
	echo "<li>Institution's profile</li>";
	echo "<li>Deferral or representation information received from the institution</li>";
	echo "</ul>";
	echo "Note that you will only be able to view these applications until the 'Access ends on' date, as set by the HEQC";

	
}
?>
<script>
function setID(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='evalReport|'+val;
}
</script>



