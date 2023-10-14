<?php
        $conn = $this->getDatabaseConnection();
	$sql = <<<SITES
		SELECT  inst_site_application.inst_site_app_id,
				inst_site_app_proceedings.inst_site_app_proc_id,
				inst_site_visit.inst_site_visit_id,
				HEInstitution.HEI_name,
				lkp_site_proceedings_ref,
				lkp_site_proceedings_desc,
				recomm_user_ref,
				portal_sent_date,
				ac_meeting_ref,
				institutional_profile_sites_ref,
				final_date_visit,
				initiation_doc,
				schedule_doc,
				institution_notification_doc,
				site_visit_report_doc,
				recomm_doc,
				siteapp_doc,
				submition_date,
				institutional_profile_sites.*
		FROM (inst_site_application, 
			 HEInstitution)
		LEFT JOIN inst_site_app_proceedings ON inst_site_application.inst_site_app_id = inst_site_app_proceedings.inst_site_app_ref
		LEFT JOIN inst_site_visit ON inst_site_app_proceedings.inst_site_app_proc_id = inst_site_visit.inst_site_app_proc_ref
		LEFT JOIN institutional_profile_sites ON institutional_profile_sites.institutional_profile_sites_id = inst_site_visit.institutional_profile_sites_ref
		LEFT JOIN lkp_site_proceedings ON lkp_site_proceedings.lkp_site_proceedings_id = inst_site_app_proceedings.lkp_site_proceedings_ref
		WHERE inst_site_application.institution_ref = HEInstitution.HEI_id
		ORDER BY HEI_name
SITES;

	$rs = mysqli_query($conn, $sql);
	$num_site_visits = mysqli_num_rows($rs);
	$html_row = "";
	while ($row = mysqli_fetch_array($rs)){
		$site_app_id = $row["inst_site_app_id"];
		$site_proc_id  = $row["inst_site_app_proc_id"];
		$site_visit_id  = $row["inst_site_visit_id"];

		$site_name = $row["site_name"] . " - " . $row["location"];
		
		$apps = "&nbsp;";
		if ($site_visit_id > 0){
			$app_arr = $this->getSelectedApplicationsForSiteVisit($site_visit_id);
			foreach ($app_arr as $a){
				$apps .= $a['program_name'] . "<br />";
			}
		}

		$site_docs = array();

		$init_doc = new octoDoc($row['initiation_doc']);
		if ($init_doc->isDoc()) {
			$init_rpt = '<a href="'.$init_doc->url().'" target="_blank">Initiation document</a>';
			array_push($site_docs, $init_rpt);
		}

		$sched_doc = new octoDoc($row['schedule_doc']);
		if ($sched_doc->isDoc()) {
			$sched_rpt = '<a href="'.$sched_doc->url().'" target="_blank">Site visit schedule</a><br>';
			array_push($site_docs, $sched_rpt);
		}

		$note_doc = new octoDoc($row['institution_notification_doc']);
		if ($note_doc->isDoc()) {
			$note_rpt = '<a href="'.$note_doc->url().'" target="_blank">Notification letter</a><br>';
			array_push($site_docs, $note_rpt);
		}
		$newsite_doc = new octoDoc($row['siteapp_doc']);
		if ($newsite_doc->isDoc()) {
			$note_rpt = '<a href="'.$newsite_doc->url().'" target="_blank">New Site Application Document</a><br>';
			array_push($site_docs, $note_rpt);
		}
		
		$docs = "&nbsp;";
		if (count($site_docs) > 0){
			$docs = implode($site_docs,"<br>");
		}
		
		$evals = "&nbsp;";
		if ($site_visit_id > 0){
			$eval_arr = $this->getSelectedEvaluatorsForSiteVisits($site_visit_id, 'visit');
			foreach ($eval_arr as $e){
				$evals .= $e['Name'] . "<br />";
			}
		}
		
		$eval_rpt = "&nbsp;";
		$eval_doc = new octoDoc($row['site_visit_report_doc']);
		if ($eval_doc->isDoc()) {
			$eval_rpt = '<a href="'.$eval_doc->url().'" target="_blank">'.$eval_doc->getFilename().'</a><br>';
		}
		
		$recomm_user = "&nbsp;";
		if ($row["recomm_user_ref"] > 0){
			$recomm_user = $this->getUserName($row["recomm_user_ref"]);
		}

		$recomm_rpt = $recomm_user; 
		$recomm_doc = new octoDoc($row['recomm_doc']);
		if ($recomm_doc->isDoc()) {
			$recomm_rpt = '<a href="'.$recomm_doc->url().'" target="_blank">'.$recomm_user.'</a><br>';
		}
		
		$html_row .= <<<HTMLrow
			<tr>
				<td class="saphireframe">{$row["HEI_name"]}</td>
				<td class="saphireframe">{$row["inst_site_app_id"]}</td>
				<td class="saphireframe">{$site_name}</td>
				<td class="saphireframe">{$apps}</td>
				<td class="saphireframe">{$docs}</td>
				<td class="saphireframe">{$row["submition_date"]}</td>
				<td class="saphireframe">{$row["final_date_visit"]}</td>
				<td class="saphireframe">{$evals}</td>
				<td class="saphireframe">{$eval_rpt}</td>
				<td class="saphireframe">{$row["lkp_site_proceedings_desc"]}</td>
				<td class="saphireframe">{$recomm_rpt}</td>
				<td class="saphireframe">{$row["portal_sent_date"]}</td>
				<td class="saphireframe">{$row["ac_meeting_ref"]}</td>
				<td class="saphireframe">&nbsp;</td>
				<td class="saphireframe">&nbsp;</td>
			</tr>
HTMLrow;
	}
		$html = <<<HTML
		<table class="saphireframe" width="95%" border="0" cellpadding="2" cellspacing="2">
		<tr class="doveblox">
			<td>Institution</td>
			<td>App<br>No.</td>
			<td>Site name</td>
			<td>Programmes</td>
			<td>Documents</td>
			<td>New Site Applicaton Submission Date</td>
			<td>Date of visit</td>
			<td>Evaluators</td>
			<td>Evaluators report</td>
			<td>Proceedings</td>			
			<td>Recomm user</td>			
			<td>Appointed date</td>			
			<td>AC meeting date</td>			
			<td>HEQC meeting date</td>			
			<td>Site visit outcome</td>			
		</tr>
		$html_row
		</table>
HTML;
	echo $html;
?>
