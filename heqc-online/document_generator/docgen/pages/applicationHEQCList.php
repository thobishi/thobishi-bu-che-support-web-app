<?php 

//getRestrictionsIDs(acmemid)

/*
Rebecca
2007-08-23
Displays a list of applications that have been assigned to a specific AC meeting, along with their relevant documents.
*/

	$path="../";

	require_once ("/var/www/common/_systems/heqc-online.php");
	$dbConnect = new dbConnect();
	$app = new HEQConline (1);
	$heqc_meeting_id = readGET("heqc_ref");
	$heqc_member_id = base64_decode(readGET("member_id"));

?>
<title>Report</title>
<link rel=STYLESHEET TYPE="text/css" href="<?php echo $path?>styles.css" title="Normal Style">
<script language="JavaScript" src="../js/che.js"></script>
<table width="100%" cellpadding="2" cellspacing="0" border="0"><tr><td bgcolor="#CC3300" height="2"></td></tr><tr><td bgcolor="#ECF1F6" align="center"><img src="<?php echo $path?>images/help_top.gif" width="255" height="45"></td></tr></table><br>
</head>
<table width="98%" cellspacing="2" cellpadding="2" align="center" border=0>
<tr><td colspan='10'><?php $app->getHEQCMeetingTableTop(base64_decode($heqc_meeting_id));?></td></tr>
<tr><td>
<?php 

$restrictions_arr = $app->getRestrictionsIDs($heqc_member_id);

$total_applications = 0;
$viewable_applications = 0;
$restricted_applications = 0;

$SQL  = <<<SQL
			SELECT * 
			FROM Institutions_application a
			JOIN ia_proceedings p ON p.application_ref = a.application_id AND p.proceeding_status_ind = 0 
SQL;

$SQL .= "WHERE p.heqc_meeting_ref = ".base64_decode($heqc_meeting_id);
$SQL .= " AND a.institution_id NOT IN (1, 2, ".implode(", ", $restrictions_arr).") ";


$totalappsSQL  = "SELECT * FROM Institutions_application a JOIN ia_proceedings p ON p.application_ref = a.application_id AND p.proceeding_status_ind = 0 WHERE p.heqc_meeting_ref = " . base64_decode($heqc_meeting_id);

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}

$totalRs = mysqli_query($conn, $totalappsSQL);
$total_applications = mysqli_num_rows($totalRs);

$rs = mysqli_query($conn, $SQL);
$viewable_applications = mysqli_num_rows($rs);
$restricted_applications = $total_applications-$viewable_applications;

if ($heqc_member_id != "") {
	echo "<tr align='right'>";
	echo "<td colspan='10'>Total applications assigned to this meeting: ".$total_applications."<br>";
	echo "Applications viewable by you: ".$viewable_applications."<br>";
	echo "Restricted applications (not viewable by you): ".$restricted_applications."</td>";
	echo "</tr>";
}

	echo "<tr class='oncolourb' valign='top'>";
	echo "<td width='10%'>HEQC reference number</td>";
	echo "<td width='15%'>Programme name</td>";
	echo "<td width='15%'>Institutional profile</td>";
	echo "<td width='15%'>Evaluator reports</td>";
	echo "<td width='15%'>Final evaluator report</td>";
	echo "<td width='15%'>Directorate recommendation</td>";
	echo "<td width='15%'>AC meeting outcome</td>";
	echo "<td width='15%'>HEQC meeting outcome</td>";
	echo "</tr>";

if (mysqli_num_rows($rs) > 0) {
	while ($row = mysqli_fetch_array($rs)) {
		//tmpSettings for link to application and insitutional profile
		$tmpSettings = "PREV_WORKFLOW=11%7C154&DBINF_HEInstitution___HEI_id=".$row["institution_id"]."&DBINF_institutional_profile___institution_ref=".$row["institution_id"]."&DBINF_Institutions_application___application_id=".$row["application_id"];
		$secretariatRecommendation = new octoDoc($row['recomm_doc']);
		//$evalReportsArray = array();
		$finalEvalDoc = "";
		$outcome = ($row['ac_decision_ref'] > 0) ? $app->getValueFromTable("lkp_desicion", "lkp_id", $row['ac_decision_ref'], "lkp_title") : "";
		$heqc_outcome = ($row['heqc_board_decision_ref'] > 0) ? $app->getValueFromTable("lkp_desicion", "lkp_id", $row['heqc_board_decision_ref'], "lkp_title") : "";
		$linkToApp = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$row["application_id"].'\', \''.base64_encode($tmpSettings).'\', \'../\');">';

		echo "<tr class='onblue' valign='top'>";
		echo "<td valign='top'>".$linkToApp.$row["CHE_reference_code"]."</a></td>\n";
		echo "<td>".$row['program_name']."</td>";
		echo '<td><a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$row['institution_id'].'\', \''.base64_encode($tmpSettings).'\', \'../\');">'.$app->getValueFromTable("HEInstitution", "HEI_id", $row["institution_id"], "HEI_name").'</a></td>';

		$evalReportsArray = $app->listEvaluatorReports($row["application_id"]);
		echo "<td>";
		foreach ($evalReportsArray as $value) {
			echo $value;
		}
		echo "</td>";

		echo "<td>";
		echo ($finalEvalDoc) ? "<a href='".$finalEvalDoc->url()."' target='_blank'>".$finalEvalDoc->getFilename()."</a>" : "&nbsp;";
		echo "</td>";

		echo "<td><a href='".$secretariatRecommendation->url()."' target='_blank'>".$secretariatRecommendation->getFilename()."</a></td>";
		echo "<td>".$outcome."</td>";
		echo "<td>".$heqc_outcome."</td>";
		echo "</tr>";
	}
}
else { echo "<td colspan='10'>No applications have been assigned to this HEQC meeting.</td>"; }


?>
</td></tr></table>
