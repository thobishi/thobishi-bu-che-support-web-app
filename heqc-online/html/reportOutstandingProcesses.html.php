<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>

<table width="100%" border=0	 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="3"><span class="loud">Outstanding CHE processes</span></td>
</tr>
</table>

<?php 

$SQL = <<<SQLselect
		SELECT
		DATE_FORMAT(active_processes.last_updated, "%Y-%m-%d") as lastUpdated,
		if (processes_id=12,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as Payment,
		if (processes_id=40,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as PaymentInvoice,
		if (processes_id=7,	concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as Checklisting,
		if (processes_id=47,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as Screening,
		if (processes_id=11,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as Evaluators,
		if (processes_id=33,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as ACMeeting,
		if (processes_id=85,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as ChooseEval_OS,
		if (processes_id=86,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as ACMeeting_OS,
		if (processes_id=87,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as ACReports_OS
		FROM active_processes
		LEFT JOIN processes ON active_processes.processes_ref = processes_id
		LEFT JOIN users ON active_processes.user_ref = user_id
		LEFT JOIN Institutions_application on Institutions_application.application_id = (IF ( InStr( active_processes.workflow_settings,  "application_id="  )  =0, 0,
			mid( active_processes.workflow_settings,
				InStr( active_processes.workflow_settings,  "application_id="  )  +15,
				Locate(  "&", active_processes.workflow_settings, InStr( active_processes.workflow_settings,  "application_id="  )  +15  )  - ( InStr( active_processes.workflow_settings,  "application_id="  )  +15  )  )  )
		)
		WHERE active_processes.status = 0
		AND processes_ref in (7,12,40,47,11,33,85,86,87)
		AND active_processes.last_updated < (date_sub(current_date(), interval 14 day))
		ORDER BY lastUpdated
SQLselect;
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
if ($RS = mysqli_query($conn, $SQL)) {
	echo "
				<table border='0' width='100%' align='center' cellpadding='2' cellspacing='2'>
				<th colspan='15' class='loud'>PROCESSES PAST THEIR DEADLINE (over 2 weeks old)</th>
				<tr class='oncolour' align='center'>
					<td><b>Last Updated&nbsp;&nbsp;&nbsp;</b></td>
					<td><b>Payment</b></td>
					<td><b>Payment Invoice</b></td>
					<td><b>Checklisting</b></td>
					<td><b>Screening</b></td>
					<td><b>Evaluators</b></td>
					<td><b>AC Meeting</b></td>
					<td><b>Choosing Evaluators_OS</b></td>
					<td><b>AC Meeting_OS</b></td>
					<td><b>AC Reports_OS</b></td>
				</tr>";
	while ($row = mysqli_fetch_array($RS)) {
	echo "
				<tr class='oncoloursoft'>
					<td>".$row["lastUpdated"]."</td>
					<td>".$row["Payment"]."</td>
					<td>".$row["PaymentInvoice"]."</td>
					<td>".$row["Checklisting"]."</td>
					<td>".$row["Screening"]."</td>
					<td>".$row["Evaluators"]."</td>
					<td>".$row["ACMeeting"]."</td>
					<td>".$row["ChooseEval_OS"]."</td>
					<td>".$row["ACMeeting_OS"]."</td>
					<td>".$row["ACReports_OS"]."</td>
				</tr>";
	}

	echo "</table>";

}

?>

<?php 

$SQL = <<<SQLselect
		SELECT
		DATE_FORMAT(active_processes.last_updated, "%Y-%m-%d") as lastUpdated,
		if (processes_id=12,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as Payment,
		if (processes_id=40,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as PaymentInvoice,
		if (processes_id=7,	concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as Checklisting,
		if (processes_id=47,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as Screening,
		if (processes_id=11,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as Evaluators,
		if (processes_id=33,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as ACMeeting,
		if (processes_id=85,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as ChooseEval_OS,
		if (processes_id=86,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as ACMeeting_OS,
		if (processes_id=87,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as ACReports_OS
		FROM active_processes
		LEFT JOIN processes ON active_processes.processes_ref = processes_id
		LEFT JOIN users ON active_processes.user_ref = user_id
		LEFT JOIN Institutions_application on Institutions_application.application_id = (IF ( InStr( active_processes.workflow_settings,  "application_id="  )  =0, 0,
			mid( active_processes.workflow_settings,
				InStr( active_processes.workflow_settings,  "application_id="  )  +15,
				Locate(  "&", active_processes.workflow_settings, InStr( active_processes.workflow_settings,  "application_id="  )  +15  )  - ( InStr( active_processes.workflow_settings,  "application_id="  )  +15  )  )  )
		)
		WHERE active_processes.status = 0
		AND processes_ref in (7,12,40,47,11,33,85,86,87)
		AND active_processes.last_updated <= (date_sub(current_date(), interval 7 day))
		AND active_processes.last_updated >= (date_sub(current_date(), interval 14 day))
		ORDER BY lastUpdated
SQLselect;

if ($RS = mysqli_query($conn, $SQL)) {
	echo "
				<table border='0' width='100%' align='center' cellpadding='2' cellspacing='2'>
				<th colspan='15' class='loud'>PROCESSES NEARING THEIR DEADLINE (over a week old)</th>
				<tr class='oncolour' align='center'>
					<td><b>Last Updated&nbsp;&nbsp;&nbsp;</b></td>
					<td><b>Payment</b></td>
					<td><b>Payment Invoice</b></td>
					<td><b>Checklisting</b></td>
					<td><b>Screening</b></td>
					<td><b>Evaluators</b></td>
					<td><b>AC Meeting</b></td>
					<td><b>Choosing Evaluators_OS</b></td>
					<td><b>AC Meeting_OS</b></td>
					<td><b>AC Reports_OS</b></td>
				</tr>";
	while ($row = mysqli_fetch_array($RS)) {
	echo "
				<tr class='oncoloursoft'>
					<td>".$row["lastUpdated"]."</td>
					<td>".$row["Payment"]."</td>
					<td>".$row["PaymentInvoice"]."</td>
					<td>".$row["Checklisting"]."</td>
					<td>".$row["Screening"]."</td>
					<td>".$row["Evaluators"]."</td>
					<td>".$row["ACMeeting"]."</td>
					<td>".$row["ChooseEval_OS"]."</td>
					<td>".$row["ACMeeting_OS"]."</td>
					<td>".$row["ACReports_OS"]."</td>
				</tr>";
	}

	echo "</table>";

}