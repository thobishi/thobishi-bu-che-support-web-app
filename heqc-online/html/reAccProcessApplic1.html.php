<?php 

	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;

	$a1 = '';
//	$a1 = '<a href="">Request to change information.</a>';
//	$a2 = '<a href="javascript:moveto(\'_label_reaccProcessDocs\')">Click here to upload documents.</a>';

	// Get payment info, if any, for this application
	$programme_fee = "";
	$prog_fee_additional_sites = "";
	$new_inst_fee = "";
	$invoice_total = "";
	$date_invoice = "";
	$date_first_reminder = "";
	$date_final_reminder = "";
	$aPay = $this->getPaymentInfo("reaccreditation_application_ref",$reaccred_id);

	$div_ptotal = "display:none";
	$div_pnone = "display:none";

	if (count($aPay) > 0){
		$div_ptotal = "display:block";
		$pay_info_flag = true;
		$programme_fee = $aPay["programme_fee"];
		$prog_fee_additional_sites = $aPay["prog_fee_additional_sites"];
		$new_inst_fee = $aPay["new_inst_fee"];
		$invoice_total = $aPay["invoice_total"];
		$date_invoice = $aPay["date_invoice"];
		$date_first_reminder = $aPay["date_first_reminder"];
		$date_final_reminder = $aPay["date_final_reminder"];
	} else {
		$div_pnone = "display:block";
		$pay_info_flag = false;
		$noPay = "-- No payment information is available --";
	}
	
	$reacc_submission_date = $this->getValueFromTable("Institutions_application_reaccreditation","Institutions_application_reaccreditation_id",$reaccred_id,"reacc_submission_date");

	$this->formFields["subm_start_date"]->fieldValue = readPost('subm_start_date');
	$this->formFields["subm_end_date"]->fieldValue = readPost('subm_end_date');
	$this->formFields["search_HEQCref"]->fieldValue = readPost('search_HEQCref');
	$this->formFields["search_progname"]->fieldValue = readPost('search_progname');
	$this->formFields["search_institution"]->fieldValue = readPost('search_institution');
	$this->showField('subm_start_date');
	$this->showField('subm_end_date');
	$this->showField('search_HEQCref');
	$this->showField('search_progname');
	$this->showField('search_institution');

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->displayReaccredHeader ($reaccred_id); ?>

		<?php //$this->showField("invoice_total"); ?>
		<br>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
			This application was submitted to HEQC on <span class="specialb"><?php echo $reacc_submission_date ?></span>.
			</td>
		</tr>
		<tr>
			<td class="loud"><br><b>Payment information:</b></td>
		</tr>
			<tr>
			<td>
				<div id="paytotal" style="<?php echo $div_ptotal; ?>">

					<table align="center" width="60%" border="1">
					<tr>
						<td><b>Invoice item</b> </td><td><b>Amount</b></td>
					</tr>
					<tr>
						<td>Undergraduate/Postgraduate amount </td><td><?php echo $programme_fee; ?></td>
					</tr>
					<tr>
						<td>Additional site of delivery </td><td><?php echo $prog_fee_additional_sites; ?></td>
					</tr>
					<tr>
						<td>Once off administration institution fee </td><td><?php echo $new_inst_fee; ?></td>
					</tr>
					<tr>
						<td><b>Total</b></td><td><?php echo $invoice_total; ?></td>
					</tr>
					</table>

					<table align="center" width="60%" cellpadding="2" cellspacing="2" border="1">
					<tr>
						<td>
						The invoice was sent on <?php echo $date_invoice; ?>.
						</td>
					</tr>
					<tr>
						<td>
						The first reminder was sent on <?php echo $date_first_reminder; ?>.
						</td>
					</tr>
					<tr>
						<td>
						The final reminder was sent on <?php echo $date_final_reminder; ?>
						</td>
					</tr>
					</table>

				</div>
				
				<div id="paynone" style="<?php echo $div_pnone; ?>">
										
					<table align="center" width="80%" cellpadding="2" cellspacing="2">
					<tr>
						<td><?php echo $noPay; ?></td>
					</tr>
					</table>
				</div>

			</td>
		</tr>
		<tr>
			<td class="loud"><br><b>Checklisted information:</b></td>
		</tr>
		<tr>
			<td align="right"><?php echo $a1;?></td>
		</tr>
		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2">
				<tr>
					<td width="25%"><b>Date checklisting complete:</b></td><td><?php $this->showfield("reacc_checklist_date"); ?></td>
				</tr>
				<tr>
					<td colspan="2">Comment/Notes: Please include the name of the individual who checklisted this application</td>
				</tr>
				<tr>
					<td colspan="2"><?php $this->showfield("reacc_checklist_comment"); ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="loud"><br><b>Evaluators</b></td>
		</tr>
		<tr>
			<td align="right"><?php echo $a1;?></td>
		</tr>
		<tr>
			<td>Please enter the date that the evaluation of this application was completed.  This is the date that all
			the evaluator reports are received.</td>
		</tr>
		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2">
				<tr>
					<td width="25%"><b>Evaluation completion date:</b></td><td><?php $this->showfield("reacc_evaluation_date"); ?></td>
				</tr>
				<tr>
					<td colspan="2">
					The following evaluators were assigned to evaluate this application.</a>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						
<?php 
					$criteria = array("evalReport_status_confirm = 1");
					$evals = $this->getSelectedEvaluatorsForApplication($reaccred_id, $criteria, "Reaccred");

					$eval_table = <<<ETABLE
						<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
						<tr class='oncolourb'>
							<td><b>Evaluator name</b></td>
							<td><b>Email address</b></td>
							<td><b>Work Telephone</b></td>
							<td><b>Chair Person</b></td>
							<td><b>Access Date</b></td>
							<td><b>Access until Date</b></td>
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
			
						$access_end = $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reaccred_id, "evaluator_access_end_date");
			
						$eval_table .= <<<EBODY
							<tr  class='onblue'>
								<td valign='top' align='left'>$e[Name]</td>
								<td valign='top' align='center'>$e[E_mail]</td>
								<td valign='top' align='center'>$e[Work_Number]</td>
								<td valign='top' align='center'>$chair</td>
								<td valign='top' align='center'>$e[evalReport_date_sent]</td>
								<td valign='top' align='center'>$access_end</td>
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
		<tr>
			<td class="loud"><br><b>Site visit</b></td>
		</tr>
		<tr>
			<td align="right"><?php echo $a1;?></td>
		</tr>
		<tr>
			<td>
			Please enter the site visit information if a site visit is to take place.
			</td>
		</tr>
		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2">
				<tr>
					<td width="25%"><b>Site visit date:</b></td><td><?php $this->showfield("reacc_sitevisit_date"); ?></td>
				</tr>
				<tr>
					<td colspan="2"><b>Comments/Notes:</b> Please include any comments regarding the site visit.</td>
				</tr>
				<tr>
					<td colspan="2"><?php $this->showfield("reacc_sitevisit_comment"); ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="loud"><br><b>Directorate recommendation</b></td>
		</tr>
		<tr>
			<td align="right"><?php echo $a1;?></td>
		</tr>
		<tr>
			<td>Please enter the date that the directorate recommendation was completed.
			</td>
		</tr>
		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2">
				<tr>
					<td width="25%"><b>Directorate recommendation date:</b></td><td><?php $this->showfield("reacc_secretariate_date"); ?></td>
				</tr>
				<tr>
					<td colspan="2"><b>Comments/Notes:</b> Please include any comments regarding the directorate recommendation.</td>
				</tr>
				<tr>
					<td colspan="2"><?php $this->showfield("reacc_secretariate_comment"); ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="loud"><br><b>Deferred</b></td>
		</tr>
		<tr>
			<td align="right"><?php echo $a1;?></td>
		</tr>
		<tr>
			<td>If this application has been deferred please enter the deferred information.
			</td>
		</tr>
		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2">
				<tr>
					<td width="25%"><b>Deferral due date:</b></td><td><?php $this->showfield("reacc_deferdue_date"); ?></td>
				</tr>
				<tr>
					<td width="25%"><b>Deferral completed date:</b></td><td><?php $this->showfield("reacc_defercomplete_date"); ?></td>
				</tr>
				<tr>
					<td colspan="2"><b>Comments/Notes:</b> Please include any comments regarding the deferred process.</td>
				</tr>
				<tr>
					<td colspan="2"><?php $this->showfield("reacc_deferral_comment"); ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="loud"><br><b>AC Meeting</b></td>
		</tr>
		<tr>
			<td>Please enter the AC Meeting information for this application.
			</td>
		</tr>
		<tr>
			<td align="right"><?php echo $a1;?></td>
		</tr>
		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2">
				<tr>
					<td width="25%"><b>AC Meeting date:</b></td><td><?php $this->showfield("reacc_acmeeting_date"); ?></td>
				</tr>
				<tr>
					<td colspan="2"><b>Comments/Notes:</b> Please include any comments regarding the AC Meeting.</td>
				</tr>
				<tr>
					<td colspan="2"><?php $this->showfield("reacc_acmeeting_comment"); ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
<?php /*
		<tr>
			<td class="loud"><br><b>Outcomes</b></td>
		</tr>
		<tr>
			<td align="right"></td>
		</tr>
		<tr>
			<td>The outcome of this application is:
				<table align="center" width="70%">
				<tr>
					<td>Outcome date: </td><td></td>
				</tr>
				<tr>
					<td>Outcome:</td><td></td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="loud"><br><b>Conditions</b></td>
		</tr>
		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2">
				<tr>
					<td>Conditions due date: </td><td><?php $this->showfield("reacc_conditiondue_date"); </td>
				</tr>				
				<tr>
					<td>Conditions met date: </td><td><?php $this->showfield("reacc_conditionmet_date"); </td>
				</tr>
				<tr>
					<td>Conditions: </td><td><?php $this->showfield("reacc_conditions"); </td>
				</tr>
				</table>
			</td>
		</tr>
*/
?>
		<tr>
			<td class="loud"><br><b>Representations</b></td>
		</tr>
		<tr>
			<td align="right"><?php echo $a1;?></td>
		</tr>
		<tr>
			<td>If representations have been received then please enter the date received:
			</td>
		</tr>
		<tr>
			<td>
				<table align="center" width="80%" cellpadding="2" cellspacing="2">
				<tr>
					<td width="25%"><b>Representation submitted date:</b></td><td><?php $this->showfield("reacc_reprsubmit_date"); ?></td>
				</tr>
				<tr>
					<td width="25%"><b>Representation completed date:</b></td><td><?php $this->showfield("reacc_reprcomplete_date"); ?></td>
				</tr>

				<tr>
					<td colspan="2"><b>Comments/Notes:</b> Please include any comments regarding the representation.</td>
				</tr>
				<tr>
					<td colspan="2"><?php $this->showfield("reacc_representation_comment"); ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="loud"><br><b>Documents</b></td>
		</tr>
		<tr>
			<td>Please upload any documentation pertaining to this application.  The following required documents should be uploaded:
				<ul>
					<li>Checklisted report</li>
					<li>Evaluators reports</li>
					<li>Directorate recommendation</li>
					<li>AC meeting minutes</li>
					<li>Letters and outcome</li>
					<li>Representation</li>
					<li>Site visit report</li>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="oncolourcolumn">
			
			<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
				<tr>
					<td class="oncoloursoft" align="right" colspan="5">
						<?php if ($this->view != 1){?>
						<a href="javascript:reaccredDocProcess('NEW');moveto('_label_reaccProcessDocForm');">>> Add new document</a>
						<?php }?>
					</td>
				</tr>
				<tr>
					<td class="oncoloursoft">Document Title</td>
					<td class="oncoloursoft">Comment</td>
					<td class="oncoloursoft">Date added</td>
					<td class="oncoloursoft">Edit/Update</td>
				</tr>
<?php 
				$SQL = "SELECT * FROM reaccred_document_process WHERE reaccred_programme_ref=?";
	
                 $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
				if ($conn->connect_errno) {
				    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
				    printf("Error: %s\n".$conn->error);
				    exit();
				}

				$sm = $conn->prepare($SQL);
				$sm->bind_param("s", $reaccred_id);
				$sm->execute();
				$rs = $sm->get_result();

				//$rs = mysqli_query($conn, $SQL);
	
				$docHtml = "";
				if (mysqli_num_rows($rs) > 0) {
					while ($row = mysqli_fetch_array($rs)) {
						$title = $row["reaccred_document_title"];
						$doc_ref = $row["reaccred_document_ref"];
						$dateUpdated  = $this->getValueFromTable("documents", "document_id", $doc_ref , "last_update_date");
						$document = new octoDoc($doc_ref);
						if ($document->url() > ''){
							$docLink	 = "<a href='".$document->url()."' target='_blank'>".$title."</a>";
						} else {
							$docLink = $title;
						}
						$doc_id = $row["reaccred_document_id"];
						$link = "Edit";
						if ($this->view != 1){
							$link = '<a href="javascript:reaccredDocProcess('.$doc_id.');moveto(\'_label_reaccProcessDocForm\');">Edit</a>';
						}
						$docHtml .=<<<TEXT
							<tr>
								<td class="ongreycolumn">$docLink</td>
								<td class="ongreycolumn">$row[reaccred_document_comment]</td>
								<td class="ongreycolumn">$dateUpdated</td>
								<td class="ongreycolumn">$link</td>
							</tr>
TEXT;
					}
					echo $docHtml;
				} else {
					echo "<tr><td> - No documents have been added -</td></tr>";
				}
?>
				</table>

			</td>
		</tr>
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

