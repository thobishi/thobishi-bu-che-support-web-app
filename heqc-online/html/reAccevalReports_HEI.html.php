<?php 
	$currentUserID = $this->currentUserID;

	$detailsSQL  = "SELECT * ";
	$detailsSQL .= "FROM users, Eval_Auditors ";
	$detailsSQL .= "WHERE 1 ";
	//$detailsSQL .= "AND Eval_Auditors.user_ref = '".$currentUserID."'";
	$detailsSQL .= "AND Eval_Auditors.user_ref = ?";
	
	$conn = $this->getDatabaseConnection();
	$stmt = mysqli_prepare($conn,$detailsSQL);
        $stmt->bind_param("s", $currentUserID);
        $stmt->execute();
        $detailsRS = $stmt->get_result();
                        
	$personNumber = '';
	$personEmail = '';
        
	//$detailsRS = mysqli_query($detailsSQL);
	while ($detailsRow = mysqli_fetch_array($detailsRS)){
		$personNumber = $detailsRow['Persnr'];
		$personEmail = $detailsRow['email'];
	}

?>

<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php 
	echo "Displays re-accreditation applications ready for evaluation. Under each application, you will see the following:";
	echo "<ul>";
	echo "<li>The date this application was assigned to you (as evaluator)</li>";
	echo "<li>The last day you will be able to view this application</li>";
	echo "<li>Application submission (if clicked, you can see all the documentation attached by the institution to the application)</li>";
	echo "<li>Institution's profile</li>";
	echo "<li>All evaluators assigned to evaluate this programme (click on a name for their contact details)</li>";
	echo "<li>All evaluator reports that have been uploaded so far</li>";
	echo "<li>Final evaluator report (you will only be able to upload this report if you are the chairperson)</li>";
	echo "</ul>";
	echo "<br>Note that you will only be able to view these applications until the 'Access ends on' date, as set by the HEQC";
?>

</td></tr>
</table>

<!---------------------------------------->

<br>

<table width="98%" border=0 align="center" cellpadding="2" cellspacing="2">
	<?php 
		//final evaluation report column added at the end IF chair exists
		$tableHeadings =<<< DISPLAY
				<td valign='top'>Date assigned</td>
				<td valign='top'>Access ends on</td>
				<td valign='top'>HEQC reference number</td>
				<td valign='top'>Programme name</td>
				<td valign='top'>Institution</td>
				<td valign='top'>Assigned evaluators</td>
				<td valign='top'>Evaluation report</td>
DISPLAY;

		$applicationsToEvaluate = '';

/*		$SQL =<<< MYSQL
			SELECT *
			FROM evalReport, Institutions_application_reaccreditation, HEInstitution, Eval_Auditors
			WHERE evalReport.reaccreditation_application_ref = Institutions_application_reaccreditation.Institutions_application_reaccreditation_id
			AND Institutions_application_reaccreditation.institution_ref = HEInstitution.HEI_id
			AND Eval_Auditors.Persnr = evalReport.Persnr_ref
			AND Institutions_application_reaccreditation.evaluator_access_end_date > now()
			AND Institutions_application_reaccreditation.evaluator_access_end_date != '1970-01-01'
			AND evalReport.Persnr_ref = '$personNumber'
			ORDER BY evalReport_date_sent
MYSQL;*/

	$SQL =<<< MYSQL
			SELECT *
			FROM evalReport, Institutions_application_reaccreditation, HEInstitution, Eval_Auditors
			WHERE evalReport.reaccreditation_application_ref = Institutions_application_reaccreditation.Institutions_application_reaccreditation_id
			AND Institutions_application_reaccreditation.institution_ref = HEInstitution.HEI_id
			AND Eval_Auditors.Persnr = evalReport.Persnr_ref
			AND Institutions_application_reaccreditation.evaluator_access_end_date > now()
			AND Institutions_application_reaccreditation.evaluator_access_end_date != '1970-01-01'
			AND evalReport.Persnr_ref = ?
			ORDER BY evalReport_date_sent
MYSQL;

                
                $stmt = $conn->prepare($SQL);
                $stmt->bind_param("s", $personNumber);
                $stmt->execute();
                $rs = $stmt->get_result();
		
		//$rs = mysqli_query($SQL);
		if (mysqli_num_rows($rs) > 0)
		{

			while ($row = mysqli_fetch_array($rs))
			{
				$reacc_id = $row["Institutions_application_reaccreditation_id"];
				$inst_id = $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id",$reacc_id, "institution_ref");
				$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$inst_id."&DBINF_institutional_profile___institution_ref=".$inst_id."&DBINF_Institutions_application_reaccreditation___Institutions_application_reaccreditation_id=".$reacc_id;
				$isChairID = "";

				$end_access_date = $row['evaluator_access_end_date'];
				$dateAssigned = $row['evalReport_date_sent'];
				$programme_code_link = '<a href="javascript:winPrintReaccApplicForm(\'Re-accreditation Application Form\',\''.$reacc_id.'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["referenceNumber"].'</a>';
				$programmeName = $row['programme_name'];
				$heiProfileLink = '<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$inst_id.'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row['HEI_name'].'</a>';

				$tableData =<<< DISPLAY
						<td width='7%'>$dateAssigned</td>
						<td width='7%'>$end_access_date</td>
						<td width="10%">$programme_code_link</td>
						<td width='17%'>$programmeName</td>
						<td width="17%">$heiProfileLink</td>
DISPLAY;

				$evalSQL =<<< MYSQL
					SELECT *
					FROM evalReport
					WHERE reaccreditation_application_ref=?
					AND evalReport_status_confirm='1'
MYSQL;
					$sm = $conn->prepare($evalSQL);
					$sm->bind_param("s", $reacc_id);
					$sm->execute();
					$evalRS = $sm->get_result();


				//$evalRS = mysqli_query($evalSQL);
				$counterReports = 0; //number of reports that should be uploaded
				$reportsUploaded = 0; //number of reports that have been uploaded already

				$evalContactDetails = "";

				while ($evalRow = mysqli_fetch_array($evalRS))
				{
					$user_id =  $this->getValueFromTable("Eval_Auditors", "Persnr", $evalRow['Persnr_ref'], "user_ref");
					$title_ref =  $this->getValueFromTable("users", "user_id", $user_id, "title_ref");
					$reportsUploaded = ($evalRow['evalReport_doc'] > 0) ? $reportsUploaded+1 : $reportsUploaded;

					$persNoRef = $evalRow['Persnr_ref'];
					$evalTitle = $this->getValueFromTable("lkp_title", "lkp_title_id", $title_ref, "lkp_title_desc");
					$evalName = $this->getValueFromTable("Eval_Auditors", "Persnr", $evalRow['Persnr_ref'], "Names");
					$evalSurname = $this->getValueFromTable("Eval_Auditors", "Persnr", $evalRow['Persnr_ref'], "Surname");
					$encodedTmpSettings = base64_encode($tmpSettings);
					$printChair = "";

					if ($evalRow["do_summary"] == 2) {
						$printChair = "(Chair)<br>";
						$isChairID = $evalRow['Persnr_ref'];
					}

					$evalContactDetails .=<<< DISPLAY
					- <a href="javascript:winEvalContactDetails('Evaluator Contact Details', $persNoRef, '$encodedTmpSettings', '');">
					$evalTitle $evalName $evalSurname
					</a>
					$printChair
					<br>
DISPLAY;
					$counterReports++;
				}

				$evalReport_doc = "<a href='javascript:setID(\"".$row["evalReport_id"]."\");moveto(\"next\");'><img border=\'0\' src=\"images/ico_print.gif\"></a> Upload/view reports";
				$finalReportbyChairID = $this->getValueFromTable("evalReport", "Persnr_ref", $isChairID, "application_sum_doc");
				$finalEvalReport = new octoDoc($finalReportbyChairID);
				$finalEvalReport_link = ($this->getValueFromTable("evalReport", "Persnr_ref", $isChairID, "application_sum_doc") > 0) ? "<a href='".$finalEvalReport->url()."' target='_blank'>".$finalEvalReport->getFilename()."</a>" : "Still to be uploaded by Chair.";
				$finalReport_chair = "<a href='javascript:setID(\"".$row["evalReport_id"]."\");moveto(\"1624\");'><img border=\'0\' src=\"images/ico_print.gif\"></a> Upload/view final report";

				//if chairman exists, print the last column
				$printChairColumn = "";
				if ($isChairID != '') {
					$tableHeadings .= <<< DISPLAY
					<td valign='top'>Final evaluation report</td>
DISPLAY;
					$ifUserIsChair = ($row["do_summary"] == '2') ?  $finalReport_chair : $finalEvalReport_link;
					$printChairColumn =<<< DISPLAY
					<td>
					$ifUserIsChair
					</td>
DISPLAY;

				}


				$evalReportsColumns =<<< DISPLAY
				<td>
					$evalContactDetails
				</td>
				<td>$evalReport_doc
					<hr>
					$reportsUploaded/$counterReports reports uploaded
				</td>
				$printChairColumn
DISPLAY;

				$applicationsToEvaluate .=<<< DISPLAY
				<tr class='onblue' valign='top'> $tableData $evalReportsColumns </tr>
DISPLAY;

			}
		
			$displayApplicationsToEvaluate =<<< DISPLAY
			<tr class='oncolourb'> $tableHeadings </tr>
			$applicationsToEvaluate
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

<script>
function setID(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='evalReport|'+val;
}
</script>



