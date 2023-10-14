<?php
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$app_id = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"application_ref");
	$fld_type = "recomm";
	$show_recomm = "true";
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
		<span class="loud">Approve evaluation of the conditions</span>
		<br>
		Please confirm that you have viewed the evaluators recommendations and comments by checking this box <?php $this->showField("condition_confirm_ind"); ?>
		<br><br>
		The evaluator's decisions and comments display below.  You may approve or reject the evaluators report. 
			<ul>
			<li>To approve return to the previous screen, click on the Approve checkbox and send the process to the next user.</li>
			<li>To reject return to the previous screen and click on the reject and Return Action menu item.  You will be required to enter instructions
			that will be emailed to the person who manages the evaluation process.
			</li>
			</ul>
		
	</td>
</tr>
<tr>
	<td>
		<hr>
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
