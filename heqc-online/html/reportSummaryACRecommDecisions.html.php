<?php 
	$ac_meeting_id = readPost('ac_meeting_ref');
?>
	<table width="98%" cellspacing="2" cellpadding="2" align="center" border=0>
	<tr>
		<td colspan='10'>
			<span class="loud">Summary of Accreditation Recommendations and Decisions:</span>
		</td>
	</tr>
	<tr>
		<td>Please select the AC meeting date: <?php $this->showField('ac_meeting_ref'); ?></td>
	</tr>
	<tr>
		<td>
			<input type="submit" class="btn" name="submitButton" value="Search" onClick="moveto('stay');">
		</td>
	</tr>
	</table>
<?php 
if ($ac_meeting_id > 0){

	$this->getACMeetingTableTop($ac_meeting_id);

	$sql = <<<RECOMM
		SELECT lkp_proceedings.lkp_proceedings_desc,
			p.proceeding_status_ind,
			HEInstitution.HEI_name, 
			a.CHE_reference_code,
			a.program_name,
			r.lkp_title as dir_recomm,
			ac.lkp_title as ac_recomm,
			h.lkp_title as heqc_recomm,
			t.lkp_title as app_outcome
		FROM Institutions_application a
		LEFT JOIN ia_proceedings p ON p.application_ref = a.application_id AND p.ac_meeting_ref = ?
		INNER JOIN HEInstitution ON HEInstitution.HEI_id = a.institution_id
		LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = p.lkp_proceedings_ref
		LEFT JOIN lkp_desicion r ON (r.lkp_id = p.recomm_decision_ref)
		LEFT JOIN lkp_desicion ac ON (ac.lkp_id = p.ac_decision_ref)
		LEFT JOIN lkp_desicion  h ON (h.lkp_id = p.heqc_board_decision_ref)
		LEFT JOIN lkp_desicion t ON (t.lkp_id = a.AC_desision)
		WHERE a.ac_meeting_ref = ? OR p.ac_meeting_ref = ?
		ORDER BY lkp_proceedings.order_acagenda, HEInstitution.priv_publ, HEInstitution.HEI_name, a.program_name
RECOMM;
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
		if ($conn->connect_errno) {
		    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
		    printf("Error: %s\n".$conn->error);
		    exit();
		}

		$sm = $conn->prepare($sql);
		$sm->bind_param("sss", $ac_meeting_id, $ac_meeting_id, $ac_meeting_id);
		$sm->execute();
		$rs = $sm->get_result();

	//$rs = mysqli_query($conn, $sql);
	
	$html = <<<HTML
		<table width="98%" cellspacing="2" cellpadding="2" align="center" border=0>
		<tr class='onblueb'>
			<td>No.</td>
			<td>Proceedings type</td>
			<td>Institution</td>
			<td>Programme name</td>
			<td>CHE Reference</td>
			<td>Directorate Recommendation</td>
			<td>Accreditation Committee Recommendation</td>
			<td>HEQC Decision</td>
			<td>Overall application outcome<br><span class="specialsi">(updated after outcome letter process)</span></td>
			</tr>
HTML;
	$rows = "";
	$i = 1;
	while ($row = mysqli_fetch_array($rs,MYSQLI_ASSOC)){
		$proc = ($row["lkp_proceedings_desc"] == NULL) ? 'Application' : $row["lkp_proceedings_desc"];
		$dir_recomm = ($row["dir_recomm"] == NULL) ? '-' : $row["dir_recomm"];
		$ac_recomm = ($row["ac_recomm"] == NULL) ? '-' : $row["ac_recomm"];
		$heqc_recomm = ($row["heqc_recomm"] == NULL) ? '-' : $row["heqc_recomm"];
		$proc_open = $row["proceeding_status_ind"];
		$in_process = "";
		if (!($row["lkp_proceedings_desc"] == NULL) && $proc_open == 0){  // Applications can be without proceedings prior to 1 June 2012. These should not say In process.
			$in_process = "<i>In process</i><br>";
			if ($row["app_outcome"] > '') { 
				$in_process .= "Previous outcome: ";
			}
		}
		$a_outcome = ($row["app_outcome"] == NULL) ? '-' : $row["app_outcome"];
		$app_outcome = $in_process . $a_outcome;
		$rows .= <<<ROWS
			<tr bgcolor="#EAEFF5"><td>$i</td><td>$proc</td><td>$row[HEI_name]</td><td>$row[program_name]</td><td>$row[CHE_reference_code]</td><td>$dir_recomm</td><td>$ac_recomm</td><td>$heqc_recomm</td><td>$app_outcome</td></tr>
ROWS;
		$i++;
	}
	
	$html .= $rows . "</table>";
	
	echo $html;
} 
?>