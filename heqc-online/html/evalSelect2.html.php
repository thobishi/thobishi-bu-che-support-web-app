<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$proc_type = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"lkp_proceedings_ref");
	$letter = "Letter of appointment";
	if ($proc_type == 4){
		$letter = "Letter of appointment for conditional proceeding";
	}
?>
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<?php $this->showInstitutionTableTop (); ?>
	</td>
</tr>
<tr>
	<td>
		It is your responsibility to appoint evaluators to evaluate this application. The displayed letters of appointment may be sent to the evaluators.
		<b>To send the letter of appointment email to the evaluator click on the checkbox under the heading Send Email.</b> Note: If there is a chair person then he/she
		will be emailed the letter of appointment for a chair person.
	</td>
</tr>
<tr>
	<td>
		<br>
		<b>The following evaluators (and chairman) have been selected to evaluate this application.</b>
		<br>
		<?php
		$html = '<table width="80%">';
		$html .= <<<html
			<tr class="oncolourb">
				<td><b>Evaluator name</b></td>
				<td><b>Email address</b></td>
				<td><b>Chair Person</b></td>
				<td><b>Email Sent?</b></td>
				<td><b>Date sent</b></td>
				<td><b>Contract to email</b></td>
				<td><b>Send Email?</b></td>
			</tr>
html;
		// Display evaluators for this proceeding only
		//$evals = $this->getSelectedEvaluatorsForApplication($this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
		$evals = $this->getSelectedEvaluatorsForApplication($app_proc_id,"","Proceedings");

		$chair = false;
		foreach($evals as $e){
			$is_chair = $this->getValueFromTable("lkp_yes_no","lkp_yn_id",$e["do_summary"],"lkp_yn_desc");

			$sent = ($e["lop_isSent"] == 1) ? "Yes" : "&nbsp";
			$date_sent = $e["lop_isSent_date"];
			
			$contract = "Upload contract";
			$contractDoc = new octoDoc($e['eval_contract_doc']);
			if ($contractDoc->isDoc()){
				$contract = "<a href='".$contractDoc->url()."' target='_blank'>".$contractDoc->getFilename()."</a>";
			}
			$clink = $this->scriptGetForm ('evalReport', $e["evalReport_id"], '_startLoadEvalContract');
			$contractLink = "<a href='".$clink."'><img border=\'0\' src=\"images/ico_print.gif\"></a> $contract";

			$html .= <<<html
			<tr>
				<td>$e[Name]</td>
				<td>$e[E_mail]</td>
				<td>$is_chair</td>
				<td>$sent</td>
				<td>$date_sent</td>
				<td>$contractLink</td>
				<td><input name="chkEval$e[Persnr]" type="Checkbox"></td>
			</tr>
html;
			if (2 == $e["do_summary"]){
				$chair = true;
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
		<b>List of evaluation reports for this application</b>
		<?php 
			echo $this->displayListofEvaluations($app_id);
		?>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td><b>The following letter of appointment will be sent to all the checked evaluators (excepting the chair person):</b>
	</td>
</tr>

<tr>
	<td>
	<?php 
	$this->formFields['evaluator_appointment_email']->fieldValue = $this->getTextContent("evalSelect2", $letter);
	$this->showfield('evaluator_appointment_email');
	?>
	</td>
</tr>

<tr>
	<td>&nbsp;</td>
</tr>

<?php

if ($chair){
?>
	<tr>
		<td><b>The following letter will be sent to the chair person only:</b></td>
	</tr>

	<tr>
		<td>
		<?php 
//		$this->showEmailAsHTML("evalSelect2", "Letter of appointment to chair person");

		$this->formFields['chairman_appointment_email']->fieldValue = $this->getTextContent("evalSelect2", "Letter of appointment to chair person");
		$this->showfield('chairman_appointment_email');

		?>
		</td>
	</tr>
<?php
}
?>
	</td>
</tr>
</table>
