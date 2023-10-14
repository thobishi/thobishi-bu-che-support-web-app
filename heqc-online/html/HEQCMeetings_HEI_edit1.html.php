<?php
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$app_id = $this->GetValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"application_ref");
	$report = $this->formatOutcomeHeader($app_proc_id,"HEQC");


	$this->formFields["heqc_meeting_ref"]->fieldValue = readPost("heqc_meeting_ref");
	$this->showField("heqc_meeting_ref");
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<br>
		<span class="loud">Edit the HEQC meeting outcome and minutes for this application</span>
	</td>
</tr>
<tr>
	<td>
		<?php echo $report; ?>
	</td>
</tr>
<tr>
	<td>
		<?php $this->edit_outcomes("heqc_board_decision_ref", $app_proc_id); ?>
	</td>
</tr>
<tr>
	<td>
		<b>HEQC Meeting minutes / discussion for this application</b>
		<br>
		<?php $this->showField('heqc_minutes_discussion'); ?>
	</td>
</tr>
</table>
<br>