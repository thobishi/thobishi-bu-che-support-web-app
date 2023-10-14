<?php 
	$currentUserID = $this->currentUserID;

	$personEmail = '';

	$detailsSQL  = <<<USERSQL
		SELECT * 
		FROM users
		WHERE users.user_id = ?
USERSQL;

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	$sm = $conn->prepare($detailsSQL);
	$sm->bind_param("s", $currentUserID);
	$sm->execute();
	$detailsRS = $sm->get_result();

	//$detailsRS = mysqli_query($detailsSQL);
	$detailsRow = mysqli_fetch_array($detailsRS);
	$personEmail = $detailsRow['email'];

	$cross = '<img src="images/dash_mark.gif">';
	$check = '<img src="images/check_mark.gif">';

?>

<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php 
	echo "Displays applications that have been through evaluation and are ready for a directorate recommendation. Under each application, you will see the following:";
	echo "<ul>";
	echo "<li>An edit link to complete the directorate recommendation for this application.</li>";
	echo "<li>The date this application was assigned to you</li>";
	echo "<li>The last day you will be able to view this application</li>";
	echo "<li>Application submission (if clicked, you can see all the documentation attached by the institution to the application)</li>";
	echo "<li>Institution's profile</li>";
	echo "<li>All evaluators and evaluator reports for this programme (click on a name to view the report)</li>";
	echo "<li>The recommendation for this application</li>";
	echo "</ul>";
	echo "Note that you will only be able to view these applications until the 'Access ends on' date, as set by the HEQC";
?>

</td></tr>
</table>

<!---------------------------------------->

<br>

<table width="98%" border=0 align="center" cellpadding="2" cellspacing="2">
	<?php 
		//final evaluation report column added at the end IF chair exists
		$tableHeadings =<<< DISPLAY
				<tr class='oncolourb'>
				<td valign='top'>Edit Dir.<br>recomm.</td>
				<td valign='top'>Proceeding</td>
				<td valign='top'>Date assigned</td>
				<td valign='top'>Access ends on</td>
				<td valign='top'>Institution</td>
				<td valign='top'>HEQC reference number</td>
				<td valign='top'>Programme name</td>
				<td valign='top'>Links to <br>applications <br> Checklist report</td>
				<td valign='top'>Evaluation reports</td>
				<td valign='top'>Recommendation</td>
				<td valign='top'>Complete<br>indicator</td>
				<td valign='top'>Previous<br>proceedings</td>
				</tr>
DISPLAY;

		$SQL =<<< MYSQL
			SELECT Institutions_application.application_id,
				ia_proceedings.ia_proceedings_id,
				Institutions_application.institution_id,
				ia_proceedings.recomm_access_end_date,
				Institutions_application.CHE_reference_code,
				Institutions_application.program_name,
				ia_proceedings.portal_sent_date,
				ia_proceedings.recomm_complete_ind,
				lkp_desicion.lkp_title as recomm_decision,
				HEInstitution.HEI_name,
				lkp_proceedings.lkp_proceedings_desc,
				ia_proceedings.reaccreditation_application_ref,
				ia_proceedings.checklist_final_doc
			FROM (ia_proceedings, Institutions_application, HEInstitution)
			LEFT JOIN lkp_desicion ON lkp_desicion.lkp_id = ia_proceedings.recomm_decision_ref
			LEFT JOIN lkp_proceedings ON ia_proceedings.lkp_proceedings_ref = lkp_proceedings.lkp_proceedings_id
			WHERE ia_proceedings.recomm_user_ref = ?
			AND ia_proceedings.application_ref = Institutions_application.application_id
			AND Institutions_application.institution_id = HEInstitution.HEI_id
			AND ia_proceedings.recomm_access_end_date > now()
			AND ia_proceedings.recomm_access_end_date != '1970-01-01'
			ORDER BY Institutions_application.CHE_reference_code
MYSQL;


$SQL2 =<<< MYSQL
		SELECT ia_proceedings_id, lkp_proceedings_desc
		FROM ia_proceedings, lkp_proceedings
		WHERE ia_proceedings.lkp_proceedings_ref = lkp_proceedings.lkp_proceedings_id
		AND application_ref = ?
		AND proceeding_status_ind = 1
		ORDER BY prev_ia_proceedings_ref
MYSQL;


		$sm = $conn->prepare($SQL);
		$sm->bind_param("s", $currentUserID);
		$sm->execute();
		$rs = $sm->get_result();

		//$rs = mysqli_query($SQL);
		$tableData = "";
		if (mysqli_num_rows($rs) > 0)
		{
			while ($row = mysqli_fetch_array($rs))
			{
				$inst_id = $row['institution_id'];
				$applicationID = $row["application_id"];
				$app_proc_id = $row["ia_proceedings_id"];
				$link1 = $this->scriptGetForm ('ia_proceedings', $app_proc_id, '_secrRecommForm');
				$lkp_proceedings_desc = $row["lkp_proceedings_desc"];
				$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$inst_id."&DBINF_institutional_profile___institution_ref=".$inst_id."&DBINF_Institutions_application___application_id=".$applicationID;
				$end_access_date = $row['recomm_access_end_date'];
				$dateAssigned = $row['portal_sent_date'];
				$programme_code_link = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$applicationID.'\', \''.base64_encode($tmpSettings).'\', \'\');">Applic</a>';
				$checklist_final_doc="";
				if ($row["checklist_final_doc"] > 0){
					$checklist_final_dc = new octoDoc($prow["checklist_final_doc"]);
					$checklist_final_doc	 = "<a href='".$checklist_final_dc->url()."' target='_blank'>Final Checklisting Report </a>";
				}
				
				$programmeName = $row['program_name'];
				$heiProfileLink = '<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$inst_id.'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row['HEI_name'].'</a>';
				// Display reaccreditation application if there is one for this proceedings.
				$reacc_id = $row['reaccreditation_application_ref'];
				$reacc_link = "";
				$tmpSettingsReacc = '';
				if ($reacc_id > 0){
					$tmpSettingsReacc = ($reacc_id > 0) ? "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$inst_id."&DBINF_institutional_profile___institution_ref=".$inst_id."&DBINF_Institutions_application_reaccreditation___Institutions_application_reaccreditation_id=".$reacc_id : "";
					$reacc_link = '<a href="javascript:winPrintReaccApplicForm(\'Re-accreditation Application Form\',\''.$reacc_id.'\', \''.base64_encode($tmpSettingsReacc).'\', \'\');">Reaccred</a>';
				}

				// Get values for evaluation
				// 1. Get evaluators appointed for this application
				$criteria = array("evalReport_status_confirm = 1");  // Evaluators has confirmed that he will evaluate this application
				$a_evals = $this->getSelectedEvaluatorsForApplication($applicationID, $criteria,"Accred","evalReport_id");
				$evaluator_rpt = "";
				foreach ($a_evals as $a_eval){
					/* 2015-05-11 Robin: Recommendation writers must not see who evaluated the application
					$eval_name = <<<NAME
						$a_eval[lkp_title_desc] $a_eval[Names] $a_eval[Surname]
NAME;
					if ($a_eval["do_summary"] == 2) { $eval_name .= "(Chair)<br>"; }
					*/
					if ($a_eval['evalReport_doc'] > 0){
						$eval_octoDoc = new octoDoc($a_eval['evalReport_doc']);
						$eval_rpt = "<a href='".$eval_octoDoc->url()."' target='_blank'>".$a_eval['evalReport_date_completed']."</a>";
						$evaluator_rpt .= $eval_rpt.'<br>';
					}
					$sum_report = '';
					if ($a_eval['application_sum_doc'] > 0){
						$eval_sumDoc = new octoDoc($a_eval['application_sum_doc']);
						$sum_report	 = "<a href='".$eval_sumDoc->url()."' target='_blank'><br>Chair: ".$eval_sumDoc->getFilename()."</a>";
					}
				}
				$evaluator_rpt = ($evaluator_rpt == "") ? '&nbsp;' : $evaluator_rpt;

				$recomm_decision = $row["recomm_decision"];
				$recomm_complete = ($row["recomm_complete_ind"] == 1) ? $check : $cross;
				
				$sm2 = $conn->prepare($SQL2);
				$sm2->bind_param("s", $applicationID);
				$sm2->execute();
				$rs2 = $sm2->get_result();

				$prow = mysqli_fetch_array($rs2);
				$docs_arr = $this->getProceedingDocs($prow["ia_proceedings_id"], "application header");
				$docs = "";
				foreach($docs_arr as $d){
					$docs .= "<br />" . $d;
				}
				
				$document = $prow["lkp_proceedings_desc"].$docs;

				$tableData .=<<< DISPLAY
						<tr class='onblue' valign='top'>
						<td width='7%'><a href='$link1'><img src="images/ico_change.gif"></a></td>
						<td width='7%'>$lkp_proceedings_desc</td>
						<td width='7%'>$dateAssigned</td>
						<td width='7%'>$end_access_date</td>
						<td width="17%">$heiProfileLink</td>
						<td width="12%">{$row["CHE_reference_code"]}</td>
						<td width='15%'>$programmeName</td>
						<td width="12%">$programme_code_link<br/><br/>$reacc_link <br/><br/>$checklist_final_doc</td>
						<td width="15%">$evaluator_rpt<br>$sum_report<br></td>
						<td width="15%">$recomm_decision</td>
						<td width="5%">$recomm_complete</td>
						<td width="5%">$document</td>
						</tr>
DISPLAY;
			} // end while
			$displayApplicationsToEvaluate =<<< DISPLAY
				$tableHeadings
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


