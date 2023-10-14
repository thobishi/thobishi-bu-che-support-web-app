<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<?php
		$this->showInstitutionTableTop ();
	?>
	</td>
</tr>
<tr>
	<td align="left">
			The following evaluators have confirmed that they will evaluate this application:
	</td>
</tr>
<tr>
	<td>

		<?php
		// Display evaluators that confirmed "accepted" to evaluate the application
		$criteria = array("evalReport_status_confirm = 1");
		// Display evaluators for this proceeding only
		//$evals = $this->getSelectedEvaluatorsForApplication($this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $criteria);
		$evals = $this->getSelectedEvaluatorsForApplication($app_proc_id, $criteria, "Proceedings");

		// Process cannot continue without evaluators having confirmed.

		$html = '<table width="95%" align="left">';

		$html .= <<<html
			<tr class='oncolourb'>
				<td><b>Evaluator name</b></td>
				<td><b>Email address</b></td>
				<td><b>Work Telephone</b></td>
				<td><b>Chair Person</b></td>
				<td><b>Date Email Sent</b></td>
				<td><b>May other evaluators<br>view this report</b></td>
				<td><b>Upload Final Contract</b></td>
			</tr>
html;

		$n_confirm = count($evals);

		// Only allow workflow to continue (display Next button) if at least one evaluator has confirmed.
		if ($n_confirm == 0){
			$this->formActions["next"]->actionMayShow = false;
			$html .= <<<html
				<tr>
					<td colspan="4">No evaluators have confirmed.  This process cannot continue.
					Please click Previous and confirm which evaluators have accepted.
					</td>
				</tr>
html;
		}

		// Evaluators who have confirmed must be notified of the portal via email.
		if ($n_confirm > 0){
			foreach($evals as $e){
				$chair = $this->getValueFromTable("lkp_yes_no","lkp_yn_id",$e["do_summary"],"lkp_yn_desc");
				$view_by_eval = $this->getValueFromTable("lkp_yes_no", "lkp_yn_id", $e["view_by_other_eval_yn_ref"], "lkp_yn_desc");

				$contract = "Upload contract";
				$contractDoc = new octoDoc($e['eval_contract_doc']);
				if ($contractDoc->isDoc()){
					$contract = "<a href='".$contractDoc->url()."' target='_blank'>".$contractDoc->getFilename()."</a>";
				}
				$clink = $this->scriptGetForm ('evalReport', $e["evalReport_id"], '_startLoadFinalEvalContract');
				$contractLink = "<a href='".$clink."'><img border=\'0\' src=\"images/ico_print.gif\"></a> $contract";

				$html .= <<<html
				<tr class='onblue'>
					<td>$e[Name]</td>
					<td>$e[E_mail]</td>
					<td>$e[Work_Number]</td>
					<td>$chair</td>
					<td>$e[evalReport_date_sent]</td>
					<td>$view_by_eval</td>
					<td>$contractLink</td>
				</tr>
html;
			}
		}

		$html .= "</table>";

		echo $html;
		?>

	</td>
</tr>
<tr>
	<td>
		<br>
		<b>All the above evaluators will receive the following email (if it hasn't been sent to them before - see <i>Date Email Sent</i>) to inform them on how to access the evaluator portal.</b><br>
	</td>
</tr>
<tr>
	<td valign="top">
		<span class="loud">Letter on Evaluator Portal Access:</span>
	</td>
</tr>
<tr>
	<td align="center">
		<?php
		$this->formFields['evaluator_portal_email']->fieldValue = $this->getTextContent("evalSelect4", "Letter on Evaluator Portal Access");
		$this->showField('evaluator_portal_email');
		?>
	</td>
</tr>
</table>
