<?php
	$current_user_id = $this->currentUserID;
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	// print_r($app_proc_id);
	$this->showInstitutionTableTop ();
	$report = $this->formatOutcomeHeader($app_proc_id);
	$this->showField('heqc_outcome_approved_user_ref');
	$fullname = $this->getValueFromTable('users', 'user_id', $current_user_id, 'name') . " " .$this->getValueFromTable('users', 'user_id', $current_user_id, 'surname');
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<p></p>
	</td>
</tr>
<tr>
	<td>
	<span class="visi">
	<br> 
	<?php echo "I, " . $fullname . ", confirm that: I have checked the HEQC-online outcome (decision, reasons, conditions) for this application against the outcome recorded in the HEQC meeting minutes and the outcomes match:"?>
	
	<?php $this->showField("heqc_outcome_approved_ind"); // ac_outcome_approved_ind?>
	</span>
	<br>
	</td>
</tr>
</table>
