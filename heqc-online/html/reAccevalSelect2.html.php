<?php 
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>

	<br>
	<?php echo $this->displayReaccredHeader ($reaccred_id)?>
	<br>

	<br>
	The displayed letters of appointment may be sent to the evaluators.
	<b>To send the letter of appointment email to the evaluator click on the checkbox under the heading Send Email.</b> Note: If there is a chair person then he/she
	will be emailed the letter of appointment for a chair person.
	<br>
	<br>
	The following evaluators (and chairman) have been selected to evaluate this application.
	<br>
	<br>
	<?php
	$html = '<table width="80%">';
	$html .= <<<html
		<tr>
			<td><b>Evaluator name</b></td>
			<td><b>Email address</b></td>
			<td><b>Chair Person</b></td>
			<td><b>Email Sent?</b></td>
			<td><b>Date sent</b></td>
			<td><b>Send Email?</b></td>
		</tr>
html;
	$evals = $this->getSelectedEvaluatorsForApplication($reaccred_id,"","Reaccred");

	$chair = false;
	foreach($evals as $e){
		$chair = $this->getValueFromTable("lkp_yes_no","lkp_yn_id",$e["do_summary"],"lkp_yn_desc");
		$sent = ($e["lop_isSent"] == 1) ? "Yes" : "&nbsp";
		$date_sent = $e["lop_isSent_date"];

		$html .= <<<html
		<tr>
			<td>$e[Name]</td>
			<td>$e[E_mail]</td>
			<td>$chair</td>
			<td>$sent</td>
			<td>$date_sent</td>
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
	$this->formFields['evaluator_appointment_email']->fieldValue = $this->getTextContent("reAccevalSelect2", "Letter of appointment");
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

		$this->formFields['chairman_appointment_email']->fieldValue = $this->getTextContent("reAccevalSelect2", "Letter of appointment to chair person");
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
