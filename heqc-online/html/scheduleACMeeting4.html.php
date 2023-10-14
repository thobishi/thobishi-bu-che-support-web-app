<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>
<?php
	$ac_meeting_id = $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID;
	$this->getACMeetingTableTop($ac_meeting_id);

	//if some applications have already been assigned to this meeting, display them as checked
	$assign_rows = "";
	$app_type_arr = array();
	$i = 1;

/*	$dSQL = <<<APPS
			SELECT p.ia_proceedings_id,
				lkp_proceedings.lkp_proceedings_desc,
				a.program_name,
				a.CHE_reference_code,
				HEInstitution.priv_publ,
				HEInstitution.HEI_name
			FROM Institutions_application a
			JOIN ia_proceedings p ON p.application_ref = a.application_id AND p.ac_meeting_ref= $ac_meeting_id
			INNER JOIN HEInstitution ON HEInstitution.HEI_id = a.institution_id
			LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = p.lkp_proceedings_ref
			WHERE p.ac_meeting_ref= $ac_meeting_id
			ORDER BY lkp_proceedings.order_acagenda, HEInstitution.priv_publ, HEInstitution.HEI_name, a.program_name
APPS;*/
	//2017-09-13: Richard - Added AC agenda type
	$dSQL = <<<APPS
			SELECT p.ia_proceedings_id,
				lkp_proceedings.lkp_proceedings_desc,
				lkp_AC_agenda_type.lkp_AC_agenda_type_desc,
				a.program_name,
				a.CHE_reference_code,
				HEInstitution.priv_publ,
				HEInstitution.HEI_name
			FROM Institutions_application a
			JOIN ia_proceedings p ON p.application_ref = a.application_id AND p.ac_meeting_ref= ?
			INNER JOIN HEInstitution ON HEInstitution.HEI_id = a.institution_id
			LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = p.lkp_proceedings_ref
			LEFT JOIN lkp_AC_agenda_type on lkp_AC_agenda_type.lkp_AC_agenda_type_id = p.lkp_AC_agenda_type_ref
			WHERE p.ac_meeting_ref= ?
			ORDER BY lkp_AC_agenda_type.lkp_AC_agenda_type_desc, lkp_proceedings.order_acagenda, HEInstitution.priv_publ, HEInstitution.HEI_name, a.program_name
APPS;

        $conn = $this->getDatabaseConnection();
        $stmt = $conn->prepare($dSQL);
        $stmt->bind_param("ss", $ac_meeting_id, $ac_meeting_id);
        $stmt->execute();
        $dRs = $stmt->get_result();
	//$dRs = mysqli_query($conn, $dSQL);
	if (mysqli_num_rows($dRs) > 0) {
		array_push($app_type_arr, "applications");
		while ($dRow = mysqli_fetch_array($dRs)) {
/*			$assign_rows .= <<<ASSIGNAPP
				<tr class="onblue">
					<td><input name="inMeeting{$dRow["ia_proceedings_id"]}" type="Checkbox"></td>
					<td>{$i}</td>
					<td>{$dRow["lkp_proceedings_desc"]}</td>
					<td>{$dRow["HEI_name"]}</td>
					<td>{$dRow["CHE_reference_code"]}</td>
					<td>{$dRow["program_name"]}</td>
				</tr>
ASSIGNAPP;*/
			//2017-09-13: Richard - Added AC agenda type
			$assign_rows .= <<<ASSIGNAPP
				<tr class="onblue">
					<td><input name="inMeeting{$dRow["ia_proceedings_id"]}" type="Checkbox"></td>
					<td>{$i}</td>
					<td>{$dRow["lkp_proceedings_desc"]}</td>
					<td>{$dRow["lkp_AC_agenda_type_desc"]}</td>
					<td>{$dRow["HEI_name"]}</td>
					<td>{$dRow["CHE_reference_code"]}</td>
					<td>{$dRow["program_name"]}</td>
				</tr>
ASSIGNAPP;
			$i++;
		}
	}

/*	$sSQL = <<<SITE
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
			AND inst_site_app_proceedings.ac_meeting_ref= $ac_meeting_id
SITE;*/
	//2017-09-13: Richard - Added AC agenda type
	$sSQL = <<<SITE
			SELECT inst_site_app_proc_id,
				inst_site_application.institution_ref,
				HEI_name,
				site_application_no,
				lkp_site_proceedings.lkp_site_proceedings_desc,
				lkp_AC_agenda_type.lkp_AC_agenda_type_desc
			FROM (inst_site_app_proceedings,
				inst_site_application, 
				HEInstitution)
			LEFT JOIN lkp_site_proceedings ON inst_site_app_proceedings.lkp_site_proceedings_ref = lkp_site_proceedings.lkp_site_proceedings_id
			LEFT JOIN lkp_AC_agenda_type on lkp_AC_agenda_type.lkp_AC_agenda_type_id = inst_site_app_proceedings.lkp_AC_agenda_type_ref
			WHERE inst_site_app_proceedings.inst_site_app_ref = inst_site_application.inst_site_app_id
			AND HEInstitution.HEI_id = inst_site_application.institution_ref
			AND inst_site_app_proceedings.ac_meeting_ref= ?
			ORDER BY lkp_AC_agenda_type.lkp_AC_agenda_type_desc, lkp_site_proceedings.lkp_site_proceedings_desc
SITE;
	///$sRs = mysqli_query($this->getDatabaseConnection(), $sSQL);
	$stmt = $conn->prepare($sSQL);
        $stmt->bind_param("s", $ac_meeting_id);
        $stmt->execute();
        $sRs = $stmt->get_result();
	$app_type_arr = array();
	if (mysqli_num_rows($sRs) > 0) {
		array_push($app_type_arr, "applications");
		while ($sRow = mysqli_fetch_array($sRs)) {
			$site_arr = $this->getSiteVisitsForApp($sRow["inst_site_app_proc_id"]);
			$sites = "&nbsp;";
			foreach($site_arr as $s){
				$sites .= $s["site_name"] . "-" . $s["location"] . "<br />";
			}
/*			$assign_rows .= <<<ASSIGNSITE
				<tr class="onblue">
					<td><input name="inMeetSite{$sRow["inst_site_app_proc_id"]}" type="Checkbox"></td>
					<td>{$i}</td>
					<td>{$sRow["lkp_site_proceedings_desc"]}</td>
					<td>{$sRow["HEI_name"]}</td>
					<td>{$sRow["site_application_no"]}</td>
					<td>{$sites}</td>
				</tr>
ASSIGNSITE;*/
			//2017-09-13: Richard - Added AC agenda type
			$assign_rows .= <<<ASSIGNSITE
				<tr class="onblue">
					<td><input name="inMeetSite{$sRow["inst_site_app_proc_id"]}" type="Checkbox"></td>
					<td>{$i}</td>
					<td>{$sRow["lkp_site_proceedings_desc"]}</td>
					<td>{$sRow["lkp_AC_agenda_type_desc"]}</td>
					<td>{$sRow["HEI_name"]}</td>
					<td>{$sRow["site_application_no"]}</td>
					<td>{$sites}</td>
				</tr>
ASSIGNSITE;
			$i++;
		}
	}

	$app_type = implode("and", $app_type_arr);
/*	$html_assign = <<<ASSIGNED
		The following $app_type have already been assigned to this meeting. To <b>prevent</b> an application from being 
		tabled at this meeting, check the relevant application.
		<br />
		<br />
		<table cellspacing="2" cellspacing="2" border="0" width="95%" align="center">
		<tr class='oncolourb'>
			<td>Remove from meeting</td>
			<td>No.</td>
			<td>Proceeding</td>
			<td>Institution</td>
			<td>Reference</td>
			<td>Name</td>
		</tr>
		$assign_rows
		</table>
		<br />
ASSIGNED;*/
	//2017-09-13: Richard - Added AC agenda type
	$html_assign = <<<ASSIGNED
		The following $app_type have already been assigned to this meeting. To <b>prevent</b> an application from being 
		tabled at this meeting, check the relevant application.
		<br />
		<br />
		<table cellspacing="2" cellspacing="2" border="0" width="95%" align="center">
		<tr class='oncolourb'>
			<td>Remove from meeting</td>
			<td>No.</td>
			<td>Proceeding</td>
			<td>Type</td>
			<td>Institution</td>
			<td>Reference</td>
			<td>Name</td>
		</tr>
		$assign_rows
		</table>
		<br />
ASSIGNED;
	
	echo $html_assign;
	
	echo "<hr><br />";
	echo '<span class="specialb">Programme applications</span>: The following applications have been flagged as ready for an AC meeting. To assign applications to this meeting, please check the box next to the relevant application.';
	echo "<br><br>";

//select all those that are ready for AC meeting
/*	$SQL = <<<READY
		SELECT a.*, p.ia_proceedings_id, lkp_proceedings.lkp_proceedings_desc
		FROM Institutions_application a, ia_proceedings p
		LEFT JOIN lkp_proceedings on (lkp_proceedings.lkp_proceedings_id = p.lkp_proceedings_ref)
		WHERE  a.application_id = p.application_ref
		AND p.proceeding_status_ind = 0
 		AND p.application_status_ref = 1
		AND p.ac_meeting_ref = 0
READY;*/
	//2017-09-13: Richard - Added AC agenda type
	$SQL = <<<READY
		SELECT a.*, p.ia_proceedings_id, lkp_proceedings.lkp_proceedings_desc, p.lkp_AC_agenda_type_ref
		FROM Institutions_application a, ia_proceedings p
		LEFT JOIN lkp_proceedings on (lkp_proceedings.lkp_proceedings_id = p.lkp_proceedings_ref)
		WHERE  a.application_id = p.application_ref
		AND p.proceeding_status_ind = 0
 		AND p.application_status_ref = 1
		AND p.ac_meeting_ref = 0
READY;
// 		AND (a.application_status = 1 OR p.application_status_ref = 1)

	$rs = mysqli_query($conn, $SQL);

	echo '<table cellspacing="2" cellspacing="2" border="0" width="95%" align="center">';

	if (mysqli_num_rows($rs) > 0) {
	echo "<tr class='oncolourb'>";
	//echo "<td>Check</td>";
	//2017-09-13: Richard - Added AC agenda type
	echo "<td>Consent</td>";
	echo "<td>Discussion</td>";
	echo "<td>Proceedings</td>";
	echo "<td>Institution</td>";
	echo "<td>HEQC reference number</td>";
	echo "<td>Programme name</td>";
	echo "</tr>";
		while ($row = mysqli_fetch_array($rs)) {
			//tmpSettings for link to application and insitutional profile
			$tmpSettings = "PREV_WORKFLOW=11%7C154&DBINF_HEInstitution___HEI_id=".$row["institution_id"]."&DBINF_institutional_profile___institution_ref=".$row["institution_id"]."&DBINF_Institutions_application___application_id=".$row["application_id"];
			$linkToApp = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$row["application_id"].'\', \''.base64_encode($tmpSettings).'\', \'\');">';
						
			echo "\n<tr class='onblue'>";
			//echo "<td><input name='notAssigned".$row['ia_proceedings_id']."' type='Checkbox'></td>";
			//2017-09-13: Richard - Added AC agenda type
			echo "<td><input name='consent".$row['ia_proceedings_id']."' type='Checkbox'></td>";
			echo "<td><input name='discussion".$row['ia_proceedings_id']."' type='Checkbox'></td>";
			echo "<td>".$row['lkp_proceedings_desc']."</td>";
			echo '<td><a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$row['institution_id'].'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$this->getValueFromTable("HEInstitution", "HEI_id", $row["institution_id"], "HEI_name").'</a></td>';
			echo "<td>".$linkToApp.$row["CHE_reference_code"]."</a></td>";
			echo "<td>".$row['program_name']."</td>";
			echo "</tr>";
		}
	}
	else {
		echo "<tr class='onblue' align='center'>";
		echo "<td colspan='4'>No applications are ready to be assigned to AC meetings.</td>";
		echo "<tr>";
	}
	echo "</table>";
?>
	<hr>
		<br>
		<span class="specialb">Site applications</span>: The following site applications have been flagged as ready for an AC meeting. 
		To assign site applications to this meeting, please check the box next to the relevant application.
		<br>
		<br>
<?php
/*		$sql = <<<SITES
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
			AND application_status_ref = 1
			AND site_proceeding_status_ind = 0
SITES;*/
		//2017-09-13: Richard - Added AC agenda type
		$sql = <<<SITES
			SELECT inst_site_app_proc_id,
				inst_site_application.institution_ref,
				HEI_name,
				site_application_no,
				lkp_site_proceedings.lkp_site_proceedings_desc,
				lkp_AC_agenda_type.lkp_AC_agenda_type_desc
			FROM (inst_site_app_proceedings,
				inst_site_application, 
				HEInstitution)
			LEFT JOIN lkp_site_proceedings ON inst_site_app_proceedings.lkp_site_proceedings_ref = lkp_site_proceedings.lkp_site_proceedings_id
			LEFT JOIN lkp_AC_agenda_type on lkp_AC_agenda_type.lkp_AC_agenda_type_id = inst_site_app_proceedings.lkp_AC_agenda_type_ref
			WHERE inst_site_app_proceedings.inst_site_app_ref = inst_site_application.inst_site_app_id
			AND HEInstitution.HEI_id = inst_site_application.institution_ref
			AND application_status_ref = 1
			AND site_proceeding_status_ind = 0
SITES;
		$row_data = "<tr><td>No site applications that are ready to be assigned to AC meetings have been found.</td></tr>";
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
/*					$row_data .= <<<ROWS
						<tr class="onblue">
							<td><input name="noSite{$row["inst_site_app_proc_id"]}" type="Checkbox"></td>
							<td>{$row["lkp_site_proceedings_desc"]}}</td>
							<td>{$row["HEI_name"]}</td>
							<td>{$row["site_application_no"]}</td>
							<td>{$sites}</td>
						</tr>
ROWS;*/
					//2017-09-13: Richard - Added AC agenda type
					$row_data .= <<<ROWS
						<tr class="onblue">
							<td><input name="consentSite{$row["inst_site_app_proc_id"]}" type="Checkbox"></td>
							<td><input name="discussSite{$row["inst_site_app_proc_id"]}" type="Checkbox"></td>
							<td>{$row["lkp_site_proceedings_desc"]}</td>
							<td>{$row["HEI_name"]}</td>
							<td>{$row["site_application_no"]}</td>
							<td>{$sites}</td>
						</tr>
ROWS;
				}
			}
		}
/*		$html = <<<HTML
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
HTML;*/
//2017-09-13: Richard - Added AC agenda type
		$html = <<<HTML
			<table cellspacing="2" cellspacing="2" border="0" width="95%" align="center">
			<tr class="oncolourb">
				<td>Consent</td>
				<td>Discussion</td>
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
