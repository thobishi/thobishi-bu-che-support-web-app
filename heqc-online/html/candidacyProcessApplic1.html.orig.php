<?php 

        $conn = $this->getDatabaseConnection();
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

	$cross = 'No';
	$check = 'Yes';

	$this->formFields["search_HEQCref"]->fieldValue = readPost('search_HEQCref');
	$this->formFields["search_progname"]->fieldValue = readPost('search_progname');
	$this->formFields["search_institution"]->fieldValue = readPost('search_institution');
	$this->formFields["subm_start_date"]->fieldValue = readPost('subm_start_date');
	$this->formFields["subm_end_date"]->fieldValue = readPost('subm_end_date');
	$this->formFields["invoice_start_date"]->fieldValue = readPost('invoice_start_date');
	$this->formFields["invoice_end_date"]->fieldValue = readPost('invoice_end_date');
	$this->formFields["evalappoint_start_date"]->fieldValue = readPost('evalappoint_start_date');
	$this->formFields["evalappoint_end_date"]->fieldValue = readPost('evalappoint_end_date');
	$this->formFields["recomm_due_start_date"]->fieldValue = readPost('recomm_due_start_date');
	$this->formFields["recomm_due_end_date"]->fieldValue =  readPost('recomm_due_end_date');
	$this->formFields["acmeeting_start_date"]->fieldValue = readPost('acmeeting_start_date');
	$this->formFields["acmeeting_end_date"]->fieldValue = readPost('acmeeting_end_date');
	$this->formFields["heqcmeeting_start_date"]->fieldValue =  readPost('heqcmeeting_start_date');
	$this->formFields["heqcmeeting_end_date"]->fieldValue =  readPost('heqcmeeting_end_date');
	$this->formFields["outcome_due_start_date"]->fieldValue =  readPost('outcome_due_start_date');
	$this->formFields["outcome_due_end_date"]->fieldValue =  readPost('outcome_due_end_date');
	$this->formFields["search_heqc_decision"]->fieldValue =  readPost('search_heqc_decision');
	$this->formFields["search_outcome"]->fieldValue = readPost('search_outcome');
	$this->formFields["no_outcome"]->fieldValue =  readPost('no_outcome');
	$this->formFields["search_status"]->fieldValue = readPost('search_status');

	$this->showField('search_HEQCref');
	$this->showField('search_progname');
	$this->showField('search_institution');
	$this->showField('subm_start_date');
	$this->showField('subm_end_date');
	$this->showField('invoice_start_date');
	$this->showField('invoice_end_date');
	$this->showField('evalappoint_start_date');
	$this->showField('evalappoint_end_date');
	$this->showField('recomm_due_start_date');
	$this->showField('recomm_due_end_date');
	$this->showField('acmeeting_start_date');
	$this->showField('acmeeting_end_date');
	$this->showField('heqcmeeting_start_date');
	$this->showField('heqcmeeting_end_date');
	$this->showField('outcome_due_start_date');
	$this->showField('outcome_due_end_date');
	$this->showField('search_heqc_decision');
	$this->showField('search_outcome');
	$this->showField('no_outcome');
	$this->showField('search_status');

	// Get application info
	$sql = <<<APPLIC
		SELECT 
			Institutions_application.AC_meeting_ref, AC_Meeting.ac_start_date, Institutions_application.AC_meeting_date,
			Institutions_application.secretariat_doc
		FROM Institutions_application
		LEFT JOIN AC_Meeting ON AC_Meeting.ac_id = Institutions_application.AC_meeting_ref
		WHERE application_id = ?;
APPLIC;
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $app_id);
        $stmt->execute();
        $rs = $stmt->get_result();
	//$rs = mysqli_query($sql);
	$row = mysqli_fetch_array($rs);
	
	$secrDoc = "&nbsp;";
	$secrRecom = new octoDoc($row['secretariat_doc']);
	if ($secrRecom->isDoc()) {
		$secrDoc = '<a href="'.$secrRecom->url().'" target="_blank">'.$secrRecom->getFilename().'</a>';
	}

	$ac_date = ($row["ac_start_date"] > '1970-01-01') ? $row["ac_start_date"] : ($row["AC_meeting_date"] > '1970-01-01') ? $row["AC_meeting_date"] : "&nbsp;";

	// Get payment info, if any, for this application
	$programme_fee = "";
	$prog_fee_additional_sites = "";
	$new_inst_fee = "";
	$invoice_total = "";
	$date_invoice = "";
	$date_first_reminder = "";
	$date_final_reminder = "";

	$aPay = $this->getPaymentInfo("application_ref",$app_id);

	$noPay = "";
	$div_ptotal = "display:none";
	$div_pnone = "display:none";

	if (count($aPay) > 0){
		$div_ptotal = "display:block";
		$pay_info_flag = true;
		$programme_fee = $aPay["programme_fee"];
		$prog_fee_additional_sites = $aPay["prog_fee_additional_sites"];
		$new_inst_fee = $aPay["new_inst_fee"];
		$invoice_total = $aPay["invoice_total"];
		$date_invoice = ($aPay["date_invoice"] > '1970-01-01') ? $aPay["date_invoice"] : "&nbsp;";
		$date_first_reminder = ($aPay["date_first_reminder"] > '1970-01-01') ? $aPay["date_first_reminder"] : "&nbsp;";
		$date_final_reminder = ($aPay["date_final_reminder"] > '1970-01-01') ? $aPay["date_final_reminder"] : "&nbsp;";
		$invoice_sent = ($aPay["invoice_sent"] == 1) ? $check . ' ' : $cross;
		$recv_confirm = ($aPay["received_confirmation"] == 1) ? $check . ' ' : $cross;
	} else {
		$div_pnone = "display:block";
		$pay_info_flag = false;
		$noPay = "-- No payment information is available --";
	}
	
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?phpif ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites($app_id); } ?>

		<?php//$this->showField("invoice_total"); ?>

		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">

		<tr>
			<td class="loud"><br><b>Payment information:</b></td>
		</tr>
		<tr>
			<td>
				<div id="paytotal" style="<?php echo $div_ptotal; ?>">

					<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
					<tr class='oncolourb'>
						<td><b>Invoice Date</b></td>
						<td>Invoice Sent</td>
						<td>Undergraduate/<br>Postgraduate<br>amount</td>
						<td>Additional<br>site of delivery<br>fee</td>
						<td>Administration fee</td>
						<td><b>Invoice Total</b></td>
						<td>First reminder</td>
						<td>Final reminder</td>
						<td><b>Payment received</b></td>
					</tr>
					<tr  class='onblue'>
						<td valign='top' align='center'><?php echo $date_invoice; ?></td>
						<td valign='top' align='center'><?php echo $invoice_sent; ?></td>
						<td valign='top' align='center'><?php echo $programme_fee; ?></td>
						<td valign='top' align='center'><?php echo $prog_fee_additional_sites; ?></td>
						<td valign='top' align='center'><?php echo $new_inst_fee; ?></td>
						<td valign='top' align='center'><b><?php echo $invoice_total; ?></b></td>
						<td valign='top' align='center'><?php echo $date_first_reminder; ?></td>
						<td valign='top' align='left'><?php echo $date_final_reminder; ?></td>
						<td valign='top' align='left'><?php echo $recv_confirm; ?></td>
					</tr>

				</div>
				
				<div id="paynone" style="<?php echo $div_pnone; ?>">
										
					<table align="center" width="80%" cellpadding="2" cellspacing="2">
					<tr>
						<td><?php echo $noPay;?></td>
					</tr>
					</table>
				</div>

			</td>
		</tr>

		<tr>
			<td class="loud"><br><b>Evaluators</b></td>
		</tr>

		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2">

				<tr>
					<td colspan="2">
					The following evaluators were assigned to evaluate this application.
					</td>
				</tr>
				<tr>
					<td colspan="2">
						
<?php 
					$criteria = array("evalReport_status_confirm = 1");
					$evals = $this->getSelectedEvaluatorsForApplication($app_id, $criteria);

					$eval_table = <<<ETABLE
						<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
						<tr class='oncolourb'>
							<td><b>Evaluator name</b></td>
							<td><b>Email address</b></td>
							<td><b>Work Telephone</b></td>
							<td><b>Chair Person</b></td>
							<td><b>Access Date</b></td>
							<td><b>Access until Date</b></td>
							<td><b>Date completed</b></td>
							<td><b>Report link</b></td>
						</tr>
ETABLE;

					foreach ($evals as $e){
						
						$chair = $this->getValueFromTable("lkp_yes_no","lkp_yn_id",$e["do_summary"],"lkp_yn_desc");
						
						$a_eReport = "&nbsp;";
						$eReport = new octoDoc($e['evalReport_doc']);
						if ($eReport->isDoc()) {
							$a_eReport = '<a href="'.$eReport->url().'" target="_blank">'.$eReport->getFilename().'</a>';
						}
			
						$a_eSummary = "&nbsp;";
						$eSummary = new octoDoc($e['application_sum_doc']);
						if ($eSummary->isDoc()) {
							$a_eSummary = '<a href="'.$eSummary->url().'" target="_blank">'.$eSummary->getFilename().'</a>';
						}
			
						$access_end = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "evaluator_access_end_date");
			
						$eval_table .= <<<EBODY
							<tr  class='onblue'>
								<td valign='top' align='left'>$e[Name]</td>
								<td valign='top' align='center'>$e[E_mail]</td>
								<td valign='top' align='center'>$e[Work_Number]</td>
								<td valign='top' align='center'>$chair</td>
								<td valign='top' align='center'>$e[evalReport_date_sent]</td>
								<td valign='top' align='center'>$access_end</td>
								<td valign='top' align='center'>$e[evalReport_date_completed]</td>
								<td valign='top' align='left'>$a_eReport<br>$a_eSummary</td>
							</tr>
EBODY;
					}

					$eval_table .= <<<ETAIL
							</table>
ETAIL;
					echo $eval_table;
?>						
					</td>
				</tr>

				</table>
			</td>
		</tr>
<!--
		<tr>
			<td class="loud"><br><b>Site visit</b></td>
		</tr>


		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2">
				<tr>
					<td width="25%"><b>Site visit date:</b></td><td><?php?></td>
				</tr>
				<tr>
					<td colspan="2"><b>Comments/Notes:</b> Please include any comments regarding the site visit.</td>
				</tr>
				<tr>
					<td colspan="2"><?php ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
-->
		<tr>
			<td class="loud"><br><b>Directorate recommendation</b></td>
		</tr>

		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2">
				<tr>
					<td width="25%"><b>Directorate recommendation:</b></td><td><?php echo $secrDoc  ?></td>
				</tr>
				</table>
			</td>
		</tr>
<!--
		<tr>
			<td class="loud"><br><b>Deferred</b></td>
		</tr>


		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2">
				<tr>
					<td width="25%"><b>Deferral due date:</b></td><td><?php ?></td>
				</tr>
				<tr>
					<td width="25%"><b>Deferral completed date:</b></td><td><?php ?></td>
				</tr>
				<tr>
					<td colspan="2"><b>Comments/Notes:</b></td>
				</tr>
				<tr>
					<td colspan="2"><?php ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
-->
		<tr>
			<td class="loud"><br><b>AC Meeting</b></td>
		</tr>
		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2">
				<tr>
					<td width="25%"><b>AC Meeting date:</b></td><td><?php echo $ac_date; ?></td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="loud"><br><b>Outcomes</b></td>
		</tr>
		<tr>
			<td>The outcome of this application is:
				<br>
				<?php echo $this->get_outcome_history($app_id); ?>
			</td>
		</tr>
<!--
		<tr>
			<td class="loud"><br><b>Conditions</b></td>
		</tr>
		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2">
				<tr>
					<td>Conditions due date: </td><td><?php?> </td>
				</tr>				
				<tr>
					<td>Conditions met date: </td><td><?php?> </td>
				</tr>
				<tr>
					<td>Conditions: </td><td><?php?> </td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="loud"><br><b>Representations</b></td>
		</tr>

		<tr>
			<td>If representations have been received then please enter the date received:
			</td>
		</tr>
		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2">
				<tr>
					<td width="25%"><b>Representation submitted date:</b></td><td><?php ?></td>
				</tr>
				<tr>
					<td width="25%"><b>Representation completed date:</b></td><td><?php ?></td>
				</tr>

				<tr>
					<td colspan="2"><b>Comments/Notes:</b> Please include any comments regarding the representation.</td>
				</tr>
				<tr>
					<td colspan="2"><?php?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
-->
<?php /*
		<tr>
			<td><br><b>Audit Trail</b></td>
		</tr>
*/?>
		</table>
	</td>
</tr>
</table>
<script>
function reaccredDocProcess(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='reaccred_document_process|'+val;
}
</script>

