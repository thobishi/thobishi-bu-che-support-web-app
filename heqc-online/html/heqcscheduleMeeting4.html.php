<br />
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php
        $conn = $this->getDatabaseConnection();
	$heqc_meeting_id = $this->dbTableInfoArray["HEQC_Meeting"]->dbTableCurrentID;
	$this->getHEQCMeetingTableTop($heqc_meeting_id);
	$assign_rows = "";
	
	//if some programme applications have already been assigned to this meeting, display them as checked
	$dSQL = <<<APPS
		SELECT a.*, p.ia_proceedings_id, lkp_proceedings.lkp_proceedings_desc
		FROM Institutions_application a, ia_proceedings p
		LEFT JOIN lkp_proceedings on (lkp_proceedings.lkp_proceedings_id = p.lkp_proceedings_ref)
		WHERE  a.application_id = p.application_ref 
		AND p.proceeding_status_ind = 0
		AND p.application_status_ref IN (5, 6)
		AND (p.heqc_meeting_ref= $heqc_meeting_id)
APPS;
	$dRs = mysqli_query($conn, $dSQL);
	
	if (mysqli_num_rows($dRs) > 0) {
		while ($dRow = mysqli_fetch_array($dRs)) {
			$inst = $this->getValueFromTable("HEInstitution", "HEI_id", $dRow['institution_id'], "HEI_name");
			$assign_rows .= <<<PROCROWS
				<tr class="onblue">
					<td><input name="inMeeting{$dRow["ia_proceedings_id"]}" type="Checkbox"></td>
					<td>{$dRow["lkp_proceedings_desc"]}</td>
					<td>{$inst}</td>
					<td>{$dRow["CHE_reference_code"]}</td>
					<td>{$dRow["program_name"]}</td>
				</tr>
PROCROWS;
		}
	}

	//if some site applications have already been assigned to this meeting, display them as checked
	$sSQL = <<<SITE
		SELECT inst_site_app_proc_id,
			inst_site_application.institution_ref,
			HEI_name,
			site_application_no,
			lkp_site_proceedings.lkp_site_proceedings_desc
		FROM (inst_site_app_proceedings,
			inst_site_application, 
			HEInstitution)
		LEFT JOIN lkp_site_proceedings ON inst_site_app_proceedings.lkp_site_proceedings_ref = lkp_site_proceedings.lkp_site_proceedings_id
		WHERE inst_site_app_proceedings.inst_site_app_ref = inst_site_application.inst_site_app_id
		AND HEInstitution.HEI_id = inst_site_application.institution_ref
		AND inst_site_app_proceedings.heqc_meeting_ref= $heqc_meeting_id
SITE;
  $conn = $this->getDatabaseConnection();
	$sRs = mysqli_query($conn, $sSQL);
	$app_type_arr = array();
	if (mysqli_num_rows($sRs) > 0) {
		array_push($app_type_arr, "applications");
		while ($sRow = mysqli_fetch_array($sRs)) {
			$site_arr = $this->getSiteVisitsForApp($sRow["inst_site_app_proc_id"]);
			$sites = "&nbsp;";
			foreach($site_arr as $s){
				$sites .= $s["site_name"] . "-" . $s["location"] . "<br />";
			}

			$assign_rows .= <<<ASSIGNSITE
				<tr class="onblue">
					<td><input name="inMeetSite{$sRow["inst_site_app_proc_id"]}" type="Checkbox"></td>
					<td>{$sRow["lkp_site_proceedings_desc"]}</td>
					<td>{$sRow["HEI_name"]}</td>
					<td>{$sRow["site_application_no"]}</td>
					<td>{$sites}</td>
				</tr>
ASSIGNSITE;
		}
	}
		
	$assign = <<<HTML
		<br>The following applications have already been assigned to this meeting. To <b>prevent</b> an application from being tabled at this 
		meeting, check the relevant application.
		<br /><br />
		<table cellspacing="2" cellspacing="2" border="0" width="95%" align="center">
		<tr class="oncolourb">
			<td>Remove from meeting</td>
			<td>Proceedings</td>
			<td>Institution</td>
			<td>Reference number</td>
			<td>Programme name</td>
		</tr>
		{$assign_rows}
		</table>
HTML;
	echo $assign;

//====================================================================================================================================
//select all programme applications that are ready for HEQC meeting
	$SQL = <<<READY
		SELECT a.*, p.ia_proceedings_id, lkp_proceedings.lkp_proceedings_desc 
		FROM Institutions_application a, ia_proceedings p
		LEFT JOIN lkp_proceedings on (lkp_proceedings.lkp_proceedings_id = p.lkp_proceedings_ref)
		WHERE  a.application_id = p.application_ref
		AND p.proceeding_status_ind = 0
 		AND p.application_status_ref = 5
READY;
	$rs = mysqli_query($conn, $SQL);

	$ready_rows = "";

	if (mysqli_num_rows($rs) > 0) {
	
		while ($row = mysqli_fetch_array($rs)) {
			//tmpSettings for link to application and insitutional profile
			$tmpSettings = "PREV_WORKFLOW=11%7C154&DBINF_HEInstitution___HEI_id=".$row["institution_id"]."&DBINF_institutional_profile___institution_ref=".$row["institution_id"]."&DBINF_Institutions_application___application_id=".$row["application_id"];
			$linkToApp = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$row["application_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">';
			$inst_link = '<a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$row["institution_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$this->getValueFromTable("HEInstitution", "HEI_id", $row["institution_id"], "HEI_name").'</a>';
			$ready_rows .= <<<READY
				<tr class="onblue">
					<td><input name="notAssigned{$row["ia_proceedings_id"]}" type="Checkbox"></td>
					<td>{$row["lkp_proceedings_desc"]}</td>
					<td>{$linkToApp}{$row["CHE_reference_code"]}</a></td>
					<td>{$row["program_name"]}</td>
					<td>$inst_link</td>
				</tr>
READY;
		}
	}
	else {
		$ready_rows .= <<<READY
			<tr class="onblue" align="center">
				<td colspan="5">No applications are ready to be assigned to HEQC meetings.</td>
			<tr>
READY;
	}
	$ready = <<<READY
		<br /><hr><br />
		<span class="specialb">Programme applications</span>: The following applications have been flagged as ready for a HEQC meeting. To assign applications to this meeting, please check the box next to the relevant application.
		<br /><br />
		<table cellspacing="2" cellspacing="2" border="0" width="95%" align="center">
		<tr class="oncolourb">
			<td>Check</td>
			<td>Proceedings</td>
			<td>HEQC reference number</td>
			<td>Programme name</td>
			<td>Institution</td>
		</tr>
			{$ready_rows}
		</table>
READY;
	echo $ready;
	//select all site applications that are ready for HEQC meeting
?>
	<hr>
		<br>
		<span class="specialb">Site applications</span>: The following site applications have been flagged as ready for an HEQC meeting. 
		To assign site applications to this meeting, please check the box next to the relevant application.
		<br>
		<br>
<?php
		$sql = <<<SITES
			SELECT inst_site_app_proc_id,
				inst_site_application.institution_ref,
				HEI_name,
				site_application_no,
				lkp_site_proceedings.lkp_site_proceedings_desc
			FROM (inst_site_app_proceedings,
				inst_site_application, 
				HEInstitution)
			LEFT JOIN lkp_site_proceedings ON inst_site_app_proceedings.lkp_site_proceedings_ref = lkp_site_proceedings.lkp_site_proceedings_id
			WHERE inst_site_app_proceedings.inst_site_app_ref = inst_site_application.inst_site_app_id
			AND HEInstitution.HEI_id = inst_site_application.institution_ref
			AND application_status_ref = 5
			AND site_proceeding_status_ind = 0
SITES;
		$row_data = <<<DATA
			<tr class="onblue" align="center">
				<td colspan="5">No site applications that are ready to be assigned to HEQC meetings have been found.</td>
			</tr>
DATA;
		$rs = mysqli_query($conn, $sql);

		if ($rs){
			if (mysqli_num_rows($rs) > 0){
				$row_data = "";
				while ($row = mysqli_fetch_array($rs)){
					$site_arr = $this->getSiteVisitsForApp($row["inst_site_app_proc_id"]);
					$sites = "&nbsp;";
					foreach($site_arr as $s){
						$sites .= $s["site_name"] . "-" . $s["location"] . "<br />";
					}
					$row_data .= <<<ROWS
						<tr class="onblue">
							<td><input name="noSite{$row["inst_site_app_proc_id"]}" type="Checkbox"></td>
							<td>{$row["lkp_site_proceedings_desc"]}}</td>
							<td>{$row["HEI_name"]}</td>
							<td>{$row["site_application_no"]}</td>
							<td>{$sites}</td>
						</tr>
ROWS;
				}
			}
		}
		$html = <<<HTML
			<table cellspacing="2" cellspacing="2" border="0" width="95%" align="center">
			<tr class="oncolourb">
				<td>Check</td>
				<td>Site proceedings</td>
				<td>Institution name</td>
				<td>Site application no</td>
				<td>Sites</td>
			</tr>
			$row_data
			</table>
HTML;
		echo $html;
?>
</td></tr>
</table>
<br>
