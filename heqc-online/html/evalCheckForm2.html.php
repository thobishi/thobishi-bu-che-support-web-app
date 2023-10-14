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
