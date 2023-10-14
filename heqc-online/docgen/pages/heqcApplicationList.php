<?php 

/*
Displays a list of applications that have been assigned to a specific HEQC meeting, along with their relevant documents.
*/

	$path="../";

	require_once ("/var/www/common/_systems/heqc-online.php");
	$dbConnect = new dbConnect();
	$app = new HEQConline (1);
	$heqc_meeting_id = base64_decode(readGET("heqc_ref"));
	$heqc_member_id = $app->currentUserID;


?>
<title>Report</title>
<link rel=STYLESHEET TYPE="text/css" href="<?php echo $path?>styles.css" title="Normal Style">
<script language="JavaScript" src="../js/che.js"></script>
<table width="100%" cellpadding="2" cellspacing="0" border="0"><tr><td bgcolor="#CC3300" height="2"></td></tr><tr><td bgcolor="#ECF1F6" align="center"><img src="<?php echo $path?>images/help_top.gif" width="255" height="45"></td></tr></table><br>
</head>
<table width="98%" cellspacing="2" cellpadding="2" align="center" border=0>
<tr><td colspan='10'><?php $app->getHEQCMeetingTableTop($heqc_meeting_id);?></td></tr>
<tr><td>
<?php 

$total_applications = 0;

$SQL  = <<<SQL
			SELECT lkp_proceedings.lkp_proceedings_desc,
					a.institution_id,
					a.application_id,
					p.heqc_board_decision_ref,
					a.CHE_reference_code,
					a.program_name,
					HEInstitution.HEI_name,
					p.ia_proceedings_id
			FROM Institutions_application as a
			JOIN ia_proceedings p ON p.application_ref = a.application_id AND p.heqc_meeting_ref = ?
			LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = p.lkp_proceedings_ref
			INNER JOIN HEInstitution ON HEInstitution.HEI_id = a.institution_id
			WHERE p.heqc_meeting_ref = ?
			ORDER BY lkp_proceedings.order_acagenda, HEInstitution.priv_publ, HEInstitution.HEI_name, a.program_name
SQL;

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
$stmt = $conn->prepare($SQL);

$stmt->bind_param("ss", $heqc_meeting_id, $heqc_meeting_id);

$stmt->execute();

$rs = $stmt->get_result();
//$rs = mysqli_query($SQL);
$total_applications = mysqli_num_rows($rs);

if ($heqc_member_id != "") {
	echo "<tr align='right'>";
	echo "<td colspan='10'>Total applications assigned to this meeting: ".$total_applications."<br>";
	echo "</tr>";
}

	echo "<tr class='oncolourb' valign='top'>";
	echo "<td width='10%'>Proceedings.</td>";
	echo "<td width='10%'>HEQC reference number</td>";
	echo "<td width='15%'>Programme name</td>";
	echo "<td width='15%'>Institutional profile</td>";
	echo "<td width='15%'>AC meeting recommendation</td>";
	echo "<td width='15%'>HEQC meeting outcome</td>";
	echo "</tr>";

if (mysqli_num_rows($rs) > 0) {
	while ($row = mysqli_fetch_array($rs)) {
		//tmpSettings for link to application and insitutional profile
		$tmpSettings = "PREV_WORKFLOW=11%7C154&DBINF_HEInstitution___HEI_id=".$row["institution_id"]."&DBINF_institutional_profile___institution_ref=".$row["institution_id"]."&DBINF_Institutions_application___application_id=".$row["application_id"];
		$outcome = ($row['heqc_board_decision_ref'] > 0) ? $app->getValueFromTable("lkp_desicion", "lkp_id", $row['heqc_board_decision_ref'], "lkp_title") : "";
		$linkToApp = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$row["application_id"].'\', \''.base64_encode($tmpSettings).'\', \'../\');">';

		echo "<tr class='onblue' valign='top'>";
		echo "<td valign='top'>".$row["lkp_proceedings_desc"]."</a></td>\n";
		echo "<td valign='top'>".$linkToApp.$row["CHE_reference_code"]."</a></td>\n";
		echo "<td>".$row['program_name']."</td>";
		echo '<td><a href="javascript:winPrintInstProfileForm(\'Institutional Profile\',\''.$row['institution_id'].'\', \''.base64_encode($tmpSettings).'\', \'../\');">'.$app->getValueFromTable("HEInstitution", "HEI_id", $row["institution_id"], "HEI_name").'</a></td>';

		echo "<td>&nbsp;</td>";
		echo "<td>".$outcome."</td>";
		echo "</tr>";
	}
}
else { echo "<td colspan='10'>No applications have been assigned to this HEQC meeting.</td>"; }


?>
</td></tr></table>
