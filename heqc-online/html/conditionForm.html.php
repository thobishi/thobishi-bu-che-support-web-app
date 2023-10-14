<?php
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$app_id = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"application_ref");
	$fld_type = "eval";
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
	<td><?php 
			$this->showInstitutionTableTop ($app_id);
		?>
	</td>
</tr>
<tr>
	<td>
		<br>
		<span class="loud">Evaluate whether conditions have been met</span>
		<br>
		<br>
		Please indicate whether the conditions below have been met or not.  
		Check this box to indicate to CHE that you have completed the evaluation: 
		<?php 
		$this->showField("condition_complete_ind"); ?>
	</td>
</tr>
<tr>
	<td>
		<hr>
	</td>
</tr>
<tr>
	<td>
		<br>
		Record of proceedings relating to: <?php $this->showField('lkp_proceedings_ref');?>
	</td>
</tr>
<tr>
	<td>
		<?php $this->edit_conditions($fld_type, $app_proc_id); ?>
	</td>
</tr>
<tr>
	<td>
		<hr>
	</td>
</tr>
<tr>
	<td>
		<br>
		Current status of all conditions for this application
	</td>
</tr>
<tr>
	<td>
		<?php echo $this->displayConditions($app_id, "application",$fld_type); ?>
	</td>
</tr>
</table>
