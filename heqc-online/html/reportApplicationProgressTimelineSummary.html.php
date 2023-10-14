<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php 

	$dateFrom  = (isset($_POST['dateFrom']) && $_POST['dateFrom'] != "") ? $_POST['dateFrom'] : 1970-01-01;
	$dateTo  = (isset($_POST['dateTo']) && $_POST['dateTo'] != "") ? $_POST['dateTo'] : 1970-01-01;

		// Make search sticky
	/*if (isset($_POST[$dateFrom]) && $_POST[$dateFrom] > '')
	{$this->formFields[$dateFrom]->fieldValue = $_POST[$dateFrom];}
	if (isset($_POST[$dateTo]) && $_POST[$dateTo] > '')
	{$this->formFields[$dateTo]->fieldValue = $_POST[$dateTo];}
	*/

	$is_CHE = false;
//	$this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable ("users", "user_id", $this->currentUserID, "institution_ref"), "HEI_name") == "CHE"
	if (($this->getValueFromTable ("users", "user_id", $this->currentUserID, "institution_ref") == 2) || ($this->getValueFromTable ("users", "user_id", $this->currentUserID, "institution_ref") == 1))
	{

?>

<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td><span class="loud">Application Status Report - Active Processes (Summary):</span></td>
</tr>
</table>
<br>
<?php 
		$is_CHE = true;
	}
?>


<?php 

//count (*) AS total,
$SQL = <<<SQLselect
		SELECT
		concat(users.surname, ", ", users.name) AS username,
		DATE_FORMAT(active_processes.last_updated, "%Y-%m") as lastUpdated,
		sum(if (processes_id=90, 1, 0)) as Submission,
		sum(if (processes_id=12, 1, 0)) as Payment,
		sum(if (processes_id=40, 1, 0)) as PaymentInvoice,
		sum(if (processes_id=7,  1, 0)) as Checklisting,
		sum(if (processes_id=47, 1, 0)) as Screening,
		sum(if (processes_id=106, 1, 0)) as EvaluatorsAppoint,
		sum(if (processes_id=112, 1, 0)) as EvaluatorsManage,
		sum(if (processes_id=163, 1, 0)) as EvaluatorsApprove,
		sum(if (processes_id=159, 1, 0)) as RecommAppoint,
		sum(if (processes_id=160, 1, 0)) as RecommPrelimApprove,
		sum(if (processes_id=161, 1, 0)) as RecommInterApprove,
		sum(if (processes_id=162, 1, 0)) as RecommFinalApprove,
		sum(if (processes_id=165, 1, 0)) as ACMeeting,
		sum(if (processes_id=173, 1, 0)) as ACOutcome,
		sum(if (processes_id=167, 1, 0)) as HEQCMeeting,
		sum(if (processes_id=168, 1, 0)) as HEQCOutcome,
		sum(if (processes_id=170, 1, 0)) as ProcessOutcome,
		count(*) as total
		FROM active_processes
		LEFT JOIN processes ON active_processes.processes_ref = processes_id
		LEFT JOIN users ON active_processes.user_ref = user_id
		LEFT JOIN Institutions_application on Institutions_application.application_id = (IF ( InStr( active_processes.workflow_settings,  "application_id="  )  =0, 0,
			mid( active_processes.workflow_settings,
				InStr( active_processes.workflow_settings,  "application_id="  )  +15,
				Locate(  "&", active_processes.workflow_settings, InStr( active_processes.workflow_settings,  "application_id="  )  +15  )  - ( InStr( active_processes.workflow_settings,  "application_id="  )  +15  )  )  )
		)
		WHERE active_processes.status = 0
		AND processes_ref in (90,12,40,7,47,106,112,163,159,160,161,162,165,173,167,168,170)
		GROUP BY username, lastUpdated
		ORDER BY username, lastUpdated
SQLselect;
$conn = $this->getDatabaseConnection();
if ($RS = mysqli_query($conn, $SQL)) {
	$prevUser = "";
	$bgColor = "#EAEFF5";
	$n=0;
	echo "
				<table border='0' width='100%' align='center' cellpadding='2' cellspacing='2'>
				<tr class='onblueb' align='center'>
					<td>&nbsp;</td>
					<td colspan='20'><b>Process name</b></td>
				</tr>
				<tr class='onblueb' align='center'>
					<td><b>User</b></td>
					<td><b>Last Updated</b></td>
					<td><b>Total</b></td>
					<td><b>Sub-<br>mission</b></td>
					<td><b>Payment</b></td>
					<td><b>Payment Invoice</b></td>
					<td><b>Check-<br>listing</b></td>
					<td><b>Screen</b></td>
					<td><b>Eval<br>appoint</b></td>
					<td><b>Eval<br>manage</b></td>
					<td><b>Eval<br>appoint</b></td>
					<td><b>Recomm<br>appoint</b></td>
					<td><b>Recomm<br>prelim approval</b></td>
					<td><b>Recomm<br>inter approval</b></td>
					<td><b>Recomm<br>final approval</b></td>
					<td><b>AC Meeting</b></td>
					<td><b>AC Outcome</b></td>
					<td><b>HEQC<br>Meeting</b></td>
					<td><b>HEQC<br>Outcome</b></td>
					<td><b>Process<br>Outcome</b></td>
				</tr>";
	while ($row = mysqli_fetch_array($RS)) {
		if ($row["username"]!= $prevUser){
			$n+=1;
		}
	$bgColor = (fmod($n,2)) ?("#EAEFF5"):("#d6e0eb");
	echo "<tr bgcolor='" . $bgColor . "'>
					<td>".$row["username"]."</td>
					<td>".$row["lastUpdated"]."</td>
					<td align='right'><b>".$row["total"]."</b></td>
					<td>".$row["Submission"]."</td>
					<td>".$row["Payment"]."</td>
					<td>".$row["PaymentInvoice"]."</td>
					<td>".$row["Checklisting"]."</td>
					<td>".$row["Screening"]."</td>
					<td>".$row["EvaluatorsAppoint"]."</td>
					<td>".$row["EvaluatorsManage"]."</td>
					<td>".$row["EvaluatorsApprove"]."</td>
					<td>".$row["RecommAppoint"]."</td>
					<td>".$row["RecommPrelimApprove"]."</td>
					<td>".$row["RecommInterApprove"]."</td>
					<td>".$row["RecommFinalApprove"]."</td>
					<td>".$row["ACMeeting"]."</td>
					<td>".$row["ACOutcome"]."</td>
					<td>".$row["HEQCMeeting"]."</td>
					<td>".$row["HEQCOutcome"]."</td>
					<td>".$row["ProcessOutcome"]."</td>
				</tr>";
	$prevUser = $row["username"];
	}

	echo "</table>";
}

?>
</td></tr>
</table>

<br>