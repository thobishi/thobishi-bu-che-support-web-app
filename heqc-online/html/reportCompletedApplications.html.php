<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php 
?>

<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td><span class="loud">Submitted Applications:</span></td>
</tr>
</table>
<br>

<?php 

//count (*) AS total,
$sql = <<<SQLselect
	CREATE TEMPORARY TABLE tmp_ap AS
	SELECT (
		IF( InStr( active_processes.workflow_settings, "application_id=" ) =0, 0, mid( active_processes.workflow_settings, InStr( active_processes.workflow_settings, "application_id=" ) +15, Locate( "&", active_processes.workflow_settings, InStr( active_processes.workflow_settings, "application_id=" ) +15 ) - ( InStr( active_processes.workflow_settings, "application_id=" ) +15 ) ) ) 
		) AS application_id, active_processes.*
	FROM active_processes
	ORDER BY application_id
SQLselect;

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
$rs = mysqli_query($conn, $sql);

$sSQL = <<<sSQL
	CREATE TEMPORARY TABLE tmp_submitted AS
	SELECT application_id, min(last_updated) as last_updated
	FROM tmp_ap
	WHERE processes_ref not in (5,100)
	GROUP BY application_id
	ORDER BY application_id
sSQL;
	$rsSQL = mysqli_query($conn, $sSQL);

$aSql = <<<aSql
	SELECT a.institution_id, 
		a.application_id, 
		a.CHE_reference_code, 
		a.program_name, 
		a.user_ref,
		a.application_printed, 
		a.submission_date, 
		a.application_status, 
		a.AC_Meeting_date, 
		a.AC_desision, 
		a.AC_conditions,
		s.last_updated
	FROM Institutions_application AS a, tmp_submitted AS s
	WHERE a.application_id = s.application_id 
	AND (CHE_reference_code > '')
aSql;

if ($aRs = mysqli_query($conn, $aSql)) {
	$tot_rows = mysqli_numrows($aRs);
	$bgColor = "#EAEFF5";
	echo "
				<table border='0' width='100%' align='center' cellpadding='2' cellspacing='2'>
				<tr><td colspan='10' align='right'>".$tot_rows."</td></td></tr>
				<tr class='onblueb' align='center'>
					<td></td>
					<td><b>Institution</b></td>
					<td><b>CHE Reference No.</b></td>
					<td><b>Program Name</b></td>
					<td><b>Submission Date</b></td>
					<td><b>Currently with(Process/Person)</b></td>
					<td><b>Processed</b></td>
					<td><b>Evaluators</b></td>
					<td><b>AC Meeting Date</b></td>
					<td><b>AC Meeting Report</b></td>
					<td><b>Accreditation Status</b></td>
				</tr>";
	while ($row = mysqli_fetch_array($aRs)) {
//		$bgColor = (fmod($n,2)) ?("#EAEFF5"):("#d6e0eb");

/*
		// Check whether submitted and get submission date
		$sSQL = <<<sSQL
			SELECT min(last_updated) as last_updated
			FROM tmp_ap
			WHERE application_id = $row[application_id]
			AND processes_ref != 5
sSQL;
		$rsSQL = mysqli_query($sSQL);

*/
//		$submitted = "no";
//		$dateSubmitted = "&nbsp;";
/*		if ($rsSQL && $sRow = mysqli_fetch_array($rsSQL)){
			if ($sRow["last_updated"] != NULL){
				$submitted = "yes";
				$dateSubmitted =  ($row["submission_date"]>'1970-01-01') ? $row["submission_date"] : $sRow["last_updated"];
			}
		}
		else { die(mysqli_error()); }
*/
		$dateSubmitted =  ($row["submission_date"]>'1970-01-01') ? $row["submission_date"] : $row["last_updated"];
		echo "<tr bgcolor='" . $bgColor . "'>
					<td>".$row["application_id"]."</td>
					<td>".$row["institution_id"]."</td>
					<td>".$row["CHE_reference_code"]."</td>
					<td>".$row["program_name"]."</td>
					<td>".$dateSubmitted."</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>".$row["AC_Meeting_date"]."</td>
					<td>&nbsp;</td>
					<td>".$row["AC_desision"]."</td>
				</tr>";
	}

	echo "</table>";
}

?>
</td></tr>
</table>