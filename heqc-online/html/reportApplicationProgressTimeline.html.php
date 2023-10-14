<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>

	<?php 

		$dateFrom   = (isset($_POST['dateFrom']) && $_POST['dateFrom'] != "") ? $_POST['dateFrom'] : 1970-01-01;
		$dateTo     = (isset($_POST['dateTo']) && $_POST['dateTo'] != "") ? $_POST['dateTo'] : 1970-01-01;
		$searchText = (isset($_POST['searchText']) && $_POST['searchText'] != "") ? $_POST['searchText'] : "";


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
			<td colspan="3"><span class="loud">Application Status Report - CHE Active processes (Detailed):</span></td>
		</tr>
		<tr align="right">
			<td width="25%">
				Select date -
			</td>
			<td width="20%">
				From: <?php $this->showField('dateFrom');	?>
			</td>
			<td>&nbsp;</td>

		</tr>
		<tr align="right">
			<td>&nbsp;</td>
			<td>
				To: <?php $this->showField('dateTo');	?>
			</td>
			<td>&nbsp;</td>
		</tr>


		<tr align="right">
			<td colspan="2">Search for HEQC reference number:
				<?php echo $this->showField("searchText"); ?>
			</td>
			<td align="left">
				<input type="submit" class="btn" name="submitButton" value="Search" onClick="moveto('stay');">
			</td>
		</tr>
	</table>
<br>
<?php 
		$is_CHE = true;
	}
?>


<?php 

//count (*) AS total,
if (isset($_POST['submitButton']))
{
$SQL = <<<SQLselect
		SELECT
		DATE_FORMAT(active_processes.last_updated, "%Y-%m-%d") as lastUpdated,
		if (processes_id=90,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as Submission,
		if (processes_id=12,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as Payment,
		if (processes_id=40,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as PaymentInvoice,
		if (processes_id=7,	concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as Checklisting,
		if (processes_id=47,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as Screening,
		if (processes_id=106,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as EvaluatorsAppoint,
		if (processes_id=112,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as EvaluatorsManage,
		if (processes_id=163,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as EvaluatorsApprove,
		if (processes_id=159,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as RecommAppoint,
		if (processes_id=160,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as RecommPrelimApprove,
		if (processes_id=161,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as RecommInterApprove,
		if (processes_id=162,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as RecommFinalApprove,
		if (processes_id=165,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as ACMeeting,
		if (processes_id=173,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as ACOutcome,
		if (processes_id=167,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as HEQCMeeting,
		if (processes_id=168,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as HEQCOutcome,
		if (processes_id=170,concat(IFNULL(CHE_reference_code,"No ref"),",<br>",name),"") as ProcessOutcome
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
SQLselect;
        $selector = 0;
	if ($dateFrom != '0')
		{ $SQL .= " AND active_processes.last_updated >= '".$dateFrom."' ";}
	if ($dateTo != '0')
		{ $SQL .= "AND active_processes.last_updated <= '".$dateTo."' ";}

	if ($searchText != '')
		{ $SQL .= " AND CHE_reference_code LIKE '%".$searchText."%' ";}

		$SQL .= "ORDER BY lastUpdated";
        
        $conn = $this->getDatabaseConnection();
        $rs = mysqli_query($conn, $SQL);
	$numOfRows = mysqli_num_rows($rs);

	if ($numOfRows != 0)
	{
		echo "
					<table border='0' width='100%' align='center' cellpadding='2' cellspacing='2'>
					<tr class='oncolour' align='center'>
						<td><b>Last Updated&nbsp;&nbsp;&nbsp;</b></td>
						<td><b>Submission</b></td>
						<td><b>Payment</b></td>
						<td><b>Payment Invoice</b></td>
						<td><b>Checklisting</b></td>
						<td><b>Screening</b></td>
						<td><b>Evaluators<br>appoint</b></td>
						<td><b>Evaluators<br>manage</b></td>
						<td><b>Evaluators<br>appoint</b></td>
						<td><b>Recommendation<br>appoint</b></td>
						<td><b>Recommendation<br>prelim approval</b></td>
						<td><b>Recommendation<br>inter approval</b></td>
						<td><b>Recommendation<br>final approval</b></td>
						<td><b>AC Meeting</b></td>
						<td><b>AC Outcome</b></td>
						<td><b>HEQC Meeting</b></td>
						<td><b>HEQC Outcome</b></td>
						<td><b>Process Outcome</b></td>
					</tr>";
		while ($row = mysqli_fetch_array($rs))
		{
		echo "
					<tr class='oncoloursoft'>
						<td>".$row["lastUpdated"]."</td>
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
		}

		echo "</table>";
	}

	else
	{
		echo "
		<table border='0' width='100%' align='center' cellpadding='2' cellspacing='2'>
			<tr class='oncolour' align='center'>
				<td colspan='10'><b>No records found</b></td>
			</tr>
		</table>";
	}
}

?>


	</td>
</tr>

</table>