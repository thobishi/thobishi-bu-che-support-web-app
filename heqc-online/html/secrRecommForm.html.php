<?php
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$app_id = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"application_ref");
	$reacc_id = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"reaccreditation_application_ref");
	$proc_type = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"lkp_proceedings_ref");
	$fld_type = "recomm";
?>
	<input type='hidden' name='cmd' value=''>
	<input type='hidden' name='id' value=''>
<?php 
	// The following is required for GridShowRowByRow as well as the inputs above.
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		$cmd = explode("|", $_POST["cmd"]);
		$this->getCMD_action($cmd);
		echo '<!--script>';
		echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
		echo 'document.defaultFrm.MOVETO.value = "stay";';
		echo 'document.defaultFrm.submit();';
		echo '</script-->';
	}

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<span class="loud">Directorate recommendation</span>
		<br>
		<br>Please complete the directorate recommendation below.  Once completed, check this box to indicate to CHE that you have 
		completed the recommendation: <?php $this->showField("recomm_complete_ind"); ?>
	</td>
</tr>
<tr>
	<td>
		<hr>
	</td>
</tr>
<tr>
	<td align="center">
		HIGHER EDUCATION QUALITY COMMITTEE<br>
		ACCREDITATION COMMITTEE<br>
		MEETING TO BE HELD
		<br>
	</td>
</tr>
<tr>
	<td>
		<br>
		Record of proceedings relating to: <?php $this->showField('lkp_proceedings_ref');?>
	</td>
</tr>
<tr>
	<td><?php 
			// Display application header in a table
			if ($proc_type == 5 || $proc_type == 6 || $proc_type == 7 || $proc_type == 8){
				echo $this->getHEQCApplicationTableTop($reacc_id,"int","reacc");
			} else {
				echo $this->getHEQCApplicationTableTop($app_id);
			}
		?>
	</td>
</tr>
<tr>
	<td>
		Background:<br>
		<?php $this->showField('applic_background');?>
	</td>
</tr>
<tr>
	<td>
		Summary of Evaluator's Report:<br>
		<?php $this->showField('eval_report_summary');?>
	</td>
</tr>
<?php // if ($proc_type <> 4): ?>
<tr>
	<td>
		<?php $this->edit_outcomes("recomm_decision_ref", $app_proc_id); ?>
	</td>
</tr>
<?php // endif; ?>
<!--
<?php // if ($proc_type == 4): ?>
<tr>
	<td>
		The outcome of this application based on whether all conditions have been met or not is: <?php $this->showField('recomm_decision_ref');?>
	</td>
</tr>
<tr>
	<td>
		<?php // $this->edit_conditions($fld_type, $app_proc_id); ?>
	</td>
</tr>
<?php // endif; ?>
-->
<tr>
	<td>
		<?php 
		// Display a comment box for the intermediate and final approver.
		if ($this->flowID == 160 || $this->flowID == 161 || $this->flowID == 162) {
		?>
			<span class="specialrb">Reviewer comments (during intermediate and final approval)</span>
		<?php
			$this->showField('recomm_approve_comment');
		}
		?>
	</td>
</tr>
</table>
