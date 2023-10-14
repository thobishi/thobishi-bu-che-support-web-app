<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<?php /*
//	if ($no_manager) {
//		$this->formActions["next"]->actionMayShow = false;

<tr>
	<td><b>You have not selected any QA managers. Please click "Previous" and select a manager.</b></td>
</tr><tr>
	<td>&nbsp;</td>
</tr>
//	}else{
*/ ?>

<tr>
	<td>This is where we should list the evaluators (with checkboxes next to them, if we want to email them) - must be able to OVERRIDE!
	<br>Email programme information and documentation OR access to online page depending on size of documents) to each evaluator.
	 Large documents may exceed the max size restrictions set on email. Documents:
	 <ul>
	 <li>Contract / Paye information that evaluator must fill in and send back.  Evaluator name is filled in before being sent</li>
	 <li>Evaluator Report Template (Need Programme reference number and details to be incorporated.)</li>
	 <li>Programme Submission information and documents for the programme that they will be evaluating.</li>
	 </ul>
	 These documents are confidential and if an Evaluator portal is set up where they can access all information for a programme then this needs to be very secure.  The evaluators must also be allowed to access this information for a specific timeframe only.  Evaluators have 14 to 20 days from receiving the programming information to submission of their evaluator report.
	 <b>Tamara</b> will provide Octoplus with examples of the email that she currently sends to evaluators so that we may use the text as a basis to create the email from the system
	 </td>
</tr

<tr>
	<td><b>Please click "Next" to send the following letter of appointment to the evaluators:</b></td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
<td>
<?php 
$this->showEmailAsHTML("evalCheckForm2", "Letter of appointment");
?>
</td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td><b>The following letter will be sent to the chair person:</b></td>
</tr><tr>
<td>
<?php 
$this->showEmailAsHTML("evalCheckForm2", "Letter of appointment to chair person");
?>
</td>
</tr>
<?php 
//	}
	if ($set_eval_id > 0) {
		echo '<input type="hidden" name="eval_id" value="'.$set_eval_id.'">';
	}
?>
</table>
</td></tr></table>
