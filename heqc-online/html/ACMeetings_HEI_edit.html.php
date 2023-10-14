<?php
	$proceedingId = isset($this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID) ? $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID : 'New';

$ac_meeting_id = isset($this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID) ? $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID : readPost("ac_meeting_ref");
if ($ac_meeting_id == 0){
	echo "This meeting cannot be displayed.  Please return to the AC Meetings menu option to access the meeting again.";
}
if ($ac_meeting_id > 0){
	$this->formFields["ac_meeting_ref"]->fieldValue = $ac_meeting_id;
	$this->showField("ac_meeting_ref");
?>
	<table width="98%" cellspacing="2" cellpadding="2" align="center" border=0>
	<tr>
		<td colspan='10'>
			<span class="loud">Take minutes for meeting:</span>
			<?php $this->getACMeetingTableTop($ac_meeting_id);?>
		</td>
	</tr>
	<tr>
		<td>
<?php 

	$total_applications = 0;

/*$SQL  = <<<SQL
			SELECT lkp_proceedings.lkp_proceedings_desc,
				a.application_id,
				a.program_name,
				a.CHE_reference_code,
				a.institution_id,
				a.secretariat_doc,
				a.AC_desision,
				p.ia_proceedings_id,
				p.recomm_doc,
				p.ac_decision_ref,
				HEInstitution.priv_publ,
				HEInstitution.HEI_name 
			FROM Institutions_application a
			JOIN ia_proceedings p ON p.application_ref = a.application_id 
			INNER JOIN HEInstitution ON HEInstitution.HEI_id = a.institution_id
			LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = p.lkp_proceedings_ref
			WHERE p.ac_meeting_ref= $ac_meeting_id
SQL;*/
//2017-09-13: Richard - Added AC agenda type
$SQL  = <<<SQL
			SELECT lkp_AC_agenda_type.lkp_AC_agenda_type_desc,
				lkp_proceedings.lkp_proceedings_desc,
				a.application_id,
				a.program_name,
				a.CHE_reference_code,
				a.institution_id,
				a.secretariat_doc,
				a.AC_desision,
				p.ia_proceedings_id,
				p.recomm_doc,
				p.ac_decision_ref,
				HEInstitution.priv_publ,
				HEInstitution.HEI_name 
			FROM Institutions_application a
			JOIN ia_proceedings p ON p.application_ref = a.application_id 
			INNER JOIN HEInstitution ON HEInstitution.HEI_id = a.institution_id
			LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = p.lkp_proceedings_ref
			LEFT JOIN lkp_AC_agenda_type on lkp_AC_agenda_type.lkp_AC_agenda_type_id = p.lkp_AC_agenda_type_ref
			WHERE p.ac_meeting_ref= $ac_meeting_id
SQL;

// Add restriction for the AC member logged in.  AC members may not see the applications for their institution
//$SQL .= " ORDER BY lkp_proceedings.order_acagenda, HEInstitution.priv_publ, HEInstitution.HEI_name, a.program_name";
//2017-09-13: Richard - Added AC agenda type
$SQL .= " ORDER BY lkp_AC_agenda_type.lkp_AC_agenda_type_desc, lkp_proceedings.order_acagenda, HEInstitution.priv_publ, HEInstitution.HEI_name, a.program_name";

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}
$rs = mysqli_query($conn, $SQL);
$total_applications = mysqli_num_rows($rs);

	echo "<tr align='right'>";
	echo "<td colspan='10'>Total applications: ".$total_applications."<br>";
	echo "</tr>";

	echo "<tr class='oncolourb' valign='top'>";
	echo "<td width='10%'>Take minutes</td>";
	echo "<td width='4%'>No.</td>";
	echo "<td width='10%'>Proceedings.</td>";
	//2017-09-13: Richard - Added AC agenda type
	echo "<td width='10%'>Type.</td>";
	echo "<td width='10%'>HEQC reference number</td>";
	echo "<td width='15%'>Programme name</td>";
	echo "<td width='15%'>Institutional profile</td>";
	echo "<td width='30%'>Evaluator reports</td>";
	echo "<td width='8%'>Proceedings<br>Directorate recommendation</td>";
	echo "<td width='8%'>Outcome</td>";
	echo "</tr>";

if (mysqli_num_rows($rs) > 0) {
	$i = 1;
	while ($row = mysqli_fetch_array($rs)) {
		//tmpSettings for link to application and insitutional profile
		$tmpSettings = "PREV_WORKFLOW=11%7C154&DBINF_HEInstitution___HEI_id=".$row["institution_id"]."&DBINF_institutional_profile___institution_ref=".$row["institution_id"]."&DBINF_Institutions_application___application_id=".$row["application_id"];
		
		$recomm = "";
		if ($row['secretariat_doc'] > 0){
			$secretariatRecommendation = new octoDoc($row['secretariat_doc']);
			$recomm = "<a href='".$secretariatRecommendation->url()."' target='_blank'>".$secretariatRecommendation->getFilename()."</a>";
		}
		if ($row['recomm_doc'] > 0){
			$dirRecomm = new octoDoc($row['recomm_doc']);
			if ($recomm > ""){
				$recomm .= "<br>";
			}
			$recomm .= "<a href='".$dirRecomm->url()."' target='_blank'>".$dirRecomm->getFilename()."</a>";
		}

		//$evalReportsArray = array();
		$finalEvalDoc = "";
		// Set outcome to application outcome.  If there is a proceeding outcome then set it to proceeding outcome.
		$outcome = ($row['AC_desision'] > 0) ? $this->getValueFromTable("lkp_desicion", "lkp_id", $row['AC_desision'], "lkp_title") : "";
		if ($row["ac_decision_ref"] > 0){
			$outcome = $this->getValueFromTable("lkp_desicion", "lkp_id", $row['ac_decision_ref'], "lkp_title");
		}
		$linkToApp = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$row["application_id"].'\', \''.base64_encode($tmpSettings).'\', \'../\');">';
		$link1 = $this->scriptGetForm ('ia_proceedings', $row['ia_proceedings_id'], '_label_ACminute_edit1');

		echo "<tr id = " . $row['ia_proceedings_id'] . " class='onblue' valign='top'>";
		echo "<td><a href='$link1'>Edit minutes</a></td>";
		echo "<td valign='top'>".$i."</a></td>\n";
		echo "<td valign='top'>".$row["lkp_proceedings_desc"]."</a></td>\n";
		//2017-09-13: Richard - Added AC agenda type
		echo "<td valign='top'>".$row["lkp_AC_agenda_type_desc"]."</a></td>\n";
		echo "<td valign='top'>".$linkToApp.$row["CHE_reference_code"]."</a></td>\n";
		echo "<td>".$row['program_name']."</td>";
		echo '<td><a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$row['institution_id'].'\', \''.base64_encode($tmpSettings).'\', \'../\');">'.$row["HEI_name"].'</a></td>';

		$evalReportsArray = $this->listEvaluatorReports($row["application_id"]);
		echo "<td>";
		foreach ($evalReportsArray as $value) {
			echo $value;
		}
		echo ($finalEvalDoc) ? "<br><a href='".$finalEvalDoc->url()."' target='_blank'>".$finalEvalDoc->getFilename()."</a>" : "&nbsp;";
		echo "</td>";

		echo "<td>$recomm</td>";
		echo "<td>".$outcome."</td>";
		echo "</tr>";
		$i++;
	}
}
else { 
	echo "<td colspan='10'>No applications have been assigned to this AC meeting.</td>"; 
}


?>	
	</td></tr></table>
<?php
}
?>
<input name = "proceedingId" id = <?php echo $proceedingId; ?> type = "hidden" value = <?php echo $proceedingId; ?> >
<script>
	var rowId = document.getElementsByName("proceedingId")[0].value;
	var target = document.getElementById(rowId); 
	target.scrollIntoView();
</script>

