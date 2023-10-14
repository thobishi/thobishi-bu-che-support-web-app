<?php
	$currentUserID = $this->currentUserID;

	$ep = $this->getEvalPersnrForUser($currentUserID);
	$ep_persnrs = implode(", ", (array)$ep["personNumber"]);
if ($ep_persnrs>0) {

	$html_rows = "";
	// Get site applications for this evaluator
	$conn = $this->getDatabaseConnection();
	$sql = <<<SQL
		SELECT HEInstitution.HEI_id, HEInstitution.HEI_code, HEInstitution.HEI_name,
			inst_site_app_proceedings.inst_site_app_proc_id,
			inst_site_app_proceedings.applic_background,
			inst_site_app_proceedings.evaluator_access_end_date
		FROM inst_site_app_proceedings, inst_site_app_proceedings_eval, HEInstitution
		WHERE inst_site_app_proceedings.inst_site_app_proc_id = inst_site_app_proceedings_eval.inst_site_app_proc_ref
		AND inst_site_app_proceedings.institution_ref = HEInstitution.HEI_id
		AND evaluator_persnr IN ({$ep_persnrs})
		AND inst_site_app_proceedings.evaluator_access_end_date >= CURDATE()
		AND inst_site_app_proceedings.evaluator_access_end_date != '1970-01-01'
SQL;
        $rs = mysqli_query($conn, $sql); // or die(mysqli_error());
	if (mysqli_num_rows($rs) == 0){
		$html_rows = "<tr><td colspan='6' align='center'>-- There are no site applications assigned to the current user at this time --</td></tr>";
	}
	while ($site_proc_row = mysqli_fetch_array($rs)){
		$inst_id = $site_proc_row["HEI_id"];
		$site_proc_id = $site_proc_row["inst_site_app_proc_id"];
		$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$inst_id."&DBINF_institutional_profile___institution_ref=".$inst_id;
		$heiProfileLink = '<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$inst_id.'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$site_proc_row['HEI_name'].'</a>';
				
		// Get site visits assigned to the evaluator
		$visits = $this->getSiteVisitsForAppAndEval($site_proc_id, $ep_persnrs);
		foreach($visits as $visit_id => $visit):

			$prog_info = "";
			$progs = $this->getSelectedApplicationsForSiteVisit($visit_id);
			foreach($progs as $app_id => $prog):		
				$prog_name = $prog['program_name'];
				$progSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$inst_id."&DBINF_institutional_profile___institution_ref=".$inst_id."&DBINF_Institutions_application___application_id=".$app_id;

				$prog_link = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$app_id.'\', \''.base64_encode($progSettings).'\', \'\');">'.$prog["CHE_reference_code"].'</a>';
				$proc_docs = "";
				// Get all completed proceedings in order to get relevant documentation for evaluator: representations, deferrals, conditions
				$psql = <<<PROCEEDINGS
					SELECT ia_proceedings_id, lkp_proceedings_desc
					FROM ia_proceedings, lkp_proceedings
					WHERE ia_proceedings.lkp_proceedings_ref = lkp_proceedings.lkp_proceedings_id
					AND application_ref = {$app_id}
					AND proceeding_status_ind = 1
					ORDER BY prev_ia_proceedings_ref
PROCEEDINGS;
				$prs = mysqli_query($conn, $psql);
				if ($prs){
					if (mysqli_num_rows($prs) > 0){
						while ($prow = mysqli_fetch_array($prs)){
							$proc_docs_arr = $this->getProceedingDocs($prow["ia_proceedings_id"], "evaluator portal");
							foreach($proc_docs_arr as $d){
								$proc_docs .= $d . "<br />";
							}		
						}
					}
				}
				$prog_info .= $prog_name ." ". $prog_link . "<br />" . $proc_docs;
			endforeach;

			$link = $this->scriptGetForm ('inst_site_visit', $visit['inst_site_visit_id'], 'next');
			$ulink = "<a href='".$link."'><img border=\'0\' src=\"images/ico_print.gif\"></a> Upload/replace<br/>";		

			$l_doc = "Click on the Upload/replace image to upload your report";
			if ($visit['site_visit_report_doc'] > 0){
				$e_doc = new octoDoc($visit['site_visit_report_doc']);
				$l_doc = "<a href='".$e_doc->url()."' target='_blank'>".$e_doc->getFilename()."</a>";
			}
			
			$html_rows .= <<<HTML
				<tr class="onblue" valign="top">
					<td>{$site_proc_row["evaluator_access_end_date"]}</td>
					<td>{$heiProfileLink}</td>
					<td>{$visit["site_name"]}, {$visit["location"]}<br>
						{$visit["address"]}
					</td>
					<td>{$visit["final_date_visit"]}</td>
					<td>{$prog_info}</td>
					<td>{$ulink} {$l_doc}</td>
				</tr>
HTML;
		endforeach;
	}
}
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		Displays site applications ready for evaluation. Under each application, you will see the following:";
		<ul>
		<li>The last day you will be able to view this application</li>
		<li>Institution's profile</li>
		<li>Site visit date</li>
		<li>Programme Information</li>
		</ul>
		Note that you will only be able to view these applications until the 'Access ends on' date, as set by the HEQC
	</td>
</tr>
</table>
<br>
<table>
<tr class="oncolourb">
	<td valign='top'>Access ends on</td>
	<td valign='top'>Institution</td>
	<td valign='top'>Site name</td>
	<td valign='top'>Visit date</td>
	<td valign='top'>Programme information</td>
	<td valign='top'>Site visit report</td>
</tr>
	<?php
	if ($ep_persnrs>0) {
	echo $html_rows;
	
	}?>
</table>

