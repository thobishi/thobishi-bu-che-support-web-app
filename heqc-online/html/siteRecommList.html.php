<?php 
	$currentUserID = $this->currentUserID;

	$cross = '<img src="images/dash_mark.gif">';
	$check = '<img src="images/check_mark.gif">';
?>

<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>

<?php 
	echo "Displays site proceedings for sites that have been evaluated and are ready for a directorate recommendation. The following is available:";
	echo "<ul>";
	echo "<li>An edit link to complete the directorate recommendation for this site proceeding.</li>";
	echo "<li>The date this site proceeding was assigned to you</li>";
	echo "<li>The last day you will be able to view the site proceeding</li>";
	echo "<li>Applications submission (if clicked, you can see all the documentation attached by the institution to the application)</li>";
	echo "<li>Institution's profile</li>";
	echo "<li>Evaluator reports per site visit (click on a name to view the report)</li>";
	echo "<li>The recommendation for this application</li>";
	echo "</ul>";
	echo "Note that you will only be able to view these applications until the 'Access ends on' date, as set by the HEQC";
?>
	</td>
</tr>
</table>

<br>

<table width="98%" border=0 align="center" cellpadding="2" cellspacing="2">
	<?php 


		//final evaluation report column added at the end IF chair exists
		$tableHeadings =<<<DISPLAY
				<tr class='oncolourb'>
					<td valign='top'>Edit Dir.<br>recomm.</td>
					<td valign='top'>Institution</td>
					<td valign='top'>Sites</td>
					<td valign='top'>Complete<br>indicator</td>
				</tr>
DISPLAY;

		$SQL =<<<MYSQL
			SELECT inst_site_app_proceedings.inst_site_app_proc_id,
				inst_site_app_proceedings.institution_ref,
				inst_site_app_proceedings.recomm_access_end_date,
				inst_site_app_proceedings.portal_sent_date,
				inst_site_app_proceedings.recomm_complete_ind,
				HEInstitution.HEI_name
			FROM (inst_site_app_proceedings, HEInstitution)
			WHERE inst_site_app_proceedings.recomm_user_ref = ?
			AND inst_site_app_proceedings.institution_ref = HEInstitution.HEI_id
			AND inst_site_app_proceedings.recomm_access_end_date > now()
			AND inst_site_app_proceedings.recomm_access_end_date != '1970-01-01'
			ORDER BY HEInstitution.HEI_name
MYSQL;

 

		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
		if ($conn->connect_errno) {
		    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
		    printf("Error: %s\n".$conn->error);
		    exit();
		}

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
				$site_proc_id = $row['inst_site_app_proc_id'];
                           
				$inst_id = $row['institution_ref'];
                                 
				$link1 = $this->scriptGetForm ('ia_proceedings', $site_proc_id, '_secrRecommForm');
				$link1 = $this->scriptGetForm ('inst_site_app_proceedings', $site_proc_id, '_siteRecommForm');

				$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$inst_id."&DBINF_institutional_profile___institution_ref=".$inst_id;
				$end_access_date = $row['recomm_access_end_date'];
				$dateAssigned = $row['portal_sent_date'];
				$heiProfileLink = '<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$inst_id.'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row['HEI_name'].'</a>';

				$recomm_complete = ($row["recomm_complete_ind"] == 1) ? $check : $cross;



$sqlir = "";
                             $sqlir = <<<SQLi
                                      SELECT siteapp_doc 
                                      FROM (inst_site_application) 
                                      WHERE institution_ref= {$inst_id}
SQLi;

//print_r ($sqlir); 
echo $inst_id;

$srss = mysqli_query($conn, $sqlir);

      while($srow = mysqli_fetch_array($srss)){
            $site_doc = new octoDoc($srow['siteapp_doc']);
						$doc_link2 = "&nbsp;";
						if ($site_doc->isDoc()){
				           $doc_link2 = '<a href="'.$site_doc->url().'" target="_blank">'.$site_doc->getFilename().'</a>';


//print_r ($doc_link2);
						}
}
echo $site_proc_id;

				// Sites data
				$sites_data = "";
				$ssql = <<<SITES
					SELECT inst_site_visit.inst_site_visit_id,
						inst_site_visit.site_visit_report_doc,
						inst_site_visit.site_recomm_decision_ref,
						institutional_profile_sites.site_name,
						institutional_profile_sites.location
					FROM inst_site_visit, institutional_profile_sites
					WHERE inst_site_app_proc_ref = {$site_proc_id}
					AND inst_site_visit.institutional_profile_sites_ref = institutional_profile_sites.institutional_profile_sites_id
SITES;
                        

				$srs = mysqli_query($conn, $ssql);
				if ($srs){
					$sites_data = <<<HTML
						<table>
						<tr>
							<td><b>Name</b></td>
							<td><b>Programmes</b></td>
							<td><b>Evaluation reports</b></td>
							<td><b>Recommendation</b></td>
						</tr>
HTML;
				
					while($srow = mysqli_fetch_array($srs)){

						$site_name = $srow['site_name'] . " " . $srow['location'];

						$prog_link = "";
						$progs_arr = $this->getSelectedApplicationsForSiteVisit($srow["inst_site_visit_id"]);
						foreach($progs_arr as $p){
							$prog_name = $p["program_name"];
							$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_Institutions_application___application_id=".$p["application_ref"];
							$prog_link .= '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$p["application_ref"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$prog_name.'</a>' . "<br>";
						}
						
						$eval_doc = new octoDoc($srow['site_visit_report_doc']);
						$doc_link = "&nbsp;";
						if ($eval_doc->isDoc()){
							$doc_link = '<a href="'.$eval_doc->url().'" target="_blank">'.$eval_doc->getFilename().'</a>';
						}
						$site_outcome = $this->getValueFromTable("lkp_decision_site","lkp_decision_site_id",$srow["site_recomm_decision_ref"],"decision_site_descr");
						$sites_data .= <<<HTML
							<tr>
								<td>$site_name</td>
								<td>$prog_link</td>
								<td>$doc_link</td>
								<td>$site_outcome</td>
							</tr>
HTML;
					}
					$sites_data .= '</table>';
				}


    


				$tableData .=<<< DISPLAY
						<tr class='onblue' valign='top'>
						<td width='7%'><a href='$link1'><img src="images/ico_change.gif"></a></td>
						<td width="17%">
							$heiProfileLink
							<br> 
                                                         <br>
                                                          $doc_link2

							<br>
							<b>Assigned:</b> $dateAssigned
							<br>
							<b>Access until:</b> $end_access_date
						</td>
						<td width="57%">$sites_data</td>
						<td width="5%">$recomm_complete</td>
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
			<td colspan='10' align='center'>-You do not have any site proceedings assigned to you at this time-</td>
		</tr>
DISPLAY;
		}

		echo $displayApplicationsToEvaluate;

	?>
</table>
<br>


